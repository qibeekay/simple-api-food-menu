<?php

// Defining the namespace for the class, which helps with organizing code.
namespace PH7\ApiSimpleMenu;

use Exception;

use PH7\ApiSimpleMenu\Exception\InvalidValidationException;

// Requiring the User class from the specified file path, which will be used for user actions.
require dirname(__DIR__) . '/endpoints/User.php';

// Defining an enum (introduced in PHP 8.1) called `UserAction`. Enums represent a fixed set of possible values.
enum UserAction: string
{
    // Defining the possible cases for the UserAction enum, each associated with a string.
    case CREATE = 'create';
    case RETRIEVE_ALL = 'retrieveAll';
    case RETRIEVE = 'retrieve';
    case UPDATE = 'update';
    case REMOVE = 'remove';

    // This method returns a JSON response based on the action being performed.
    public function getResponse(): string
    {
        // The null coalescing operator (??) is used here. It assigns 0 if 'user_ud' is not present in the GET request.
        $userid = !empty($_GET['user_id']) ? (int) $_GET['user_id'] : 0;

        $postBody = file_get_contents('php://input');
        $postBody = json_decode($postBody);

        // Creating a new User object with the given parameters.
        $user = new User('Evans', 'evanskyrie5@gmail.com', '09073216155');

        // Using `match` (introduced in PHP 8.0) to handle the action based on the current enum case.
        // It matches the enum case with the corresponding user action method.
        try {
            $response = match ($this) {
                self::CREATE => $user->create($postBody),               // If the action is 'CREATE', call the create method.
                self::RETRIEVE => $user->retrieve($userid),    // If the action is 'RETRIEVE', call the retrieve method with the user ID.
                self::RETRIEVE_ALL => $user->retrieveAll(),    // If the action is 'RETRIEVE_ALL', call the retrieveAll method.
                self::REMOVE => $user->remove($userid),               // If the action is 'REMOVE', call the remove method.
                self::UPDATE => $user->update($postBody),               // If the action is 'UPDATE', call the update method.
            };
        } catch (InvalidValidationException | Exception $e) {
            // TODO Send 400 status code with header()
            $response = [
                'errors' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),

                ]
            ];
        }

        // Encode the response as a JSON string and return it.
        return json_encode($response);
    }
}

// Retrieving the 'action' parameter from the URL query string (e.g., ?action=create).
// If 'action' is not present, it defaults to null.
$action = $_GET['action'] ?? null;

// Using the match expression to map the 'action' query parameter to the corresponding UserAction enum case.
// If the 'action' does not match any predefined case, it defaults to `RETRIEVE_ALL`.
$userAction = match ($action) {
    'create' => UserAction::CREATE,
    'retrieve' => UserAction::RETRIEVE,
    'remove' => UserAction::REMOVE,
    'update' => UserAction::UPDATE,
    default => UserAction::RETRIEVE_ALL, // Default action if none of the above cases match.
};

// Calling the `getResponse` method of the matched `UserAction` enum case, which will perform the user action
// and output the result as a JSON string.
echo $userAction->getResponse();
