<?php
namespace PH7\ApiSimpleMenu;


use PH7\ApiSimpleMenu\Validation\Exception\InvalidValidationException;
use PH7\ApiSimpleMenu\Validation\UserValidation;
use Respect\Validation\Validator as v;


class User
{
    public readonly ?string $userid;

    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $phoneNumber
    ) {

    }

    public function create(mixed $data): object
    {

        $userValidation = new UserValidation($data);

        if ($userValidation->isCreationSchemaValid()) {
            return $data;

        }

        throw new InvalidValidationException('Invalid Data');

        // return $this;
    }

    public function retrieveAll(): array
    {
        return [];
    }

    public function retrieve(string $userid): self
    {
        if (v::uuid()->validate($userid)) {
            $this->userid = $userid;
            return $this;
        }


        throw new InvalidValidationException("Invalid user UUID");
    }

    public function update(mixed $data): object
    {
        // TODO Update `$postBody` to the DAL later on (for updating the database)
        $userValidation = new UserValidation($data);


        if ($userValidation->isUpdateSchemaValid()) {
            return $data;
        }

        throw new InvalidValidationException('Invalid Data');

    }

    public function remove(string $userid): bool
    {
        if (v::uuid()->validate($userid)) {
            $this->userid = $userid;
            return true;
        }
        throw new InvalidValidationException("Invalid user UUID");



    }


}