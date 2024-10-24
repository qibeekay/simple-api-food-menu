<?php
namespace PH7\ApiSimpleMenu;


use PH7\ApiSimpleMenu\Exception\InvalidValidationException;
use Respect\Validation\Validator as v;

class User
{
    public readonly int $userid;

    public function __construct(public readonly string $name, public readonly string $email, public readonly string $phoneNumber)
    {

    }

    public function create(mixed $data): object
    {

        // validation schema
        $schemaValidation = v::attribute('first', v::stringType()->length(3, 60))->attribute('last', v::stringType()->length(3, 60))->attribute('email', v::email(), mandatory: false)->attribute('phone', v::phone(), mandatory: false);

        if ($schemaValidation->validate($data)) {
            return $data;

        }

        throw new InvalidValidationException('Invalid Data');

        // return $this;
    }

    public function retrieveAll(): array
    {
        return [];
    }

    public function retrieve(int $userid): self
    {
        $this->userid = $userid;
        return $this;
    }

    public function update(mixed $data): self
    {
        // TODO Update `$postBody` to the DAL later on (for updating the database)
        return $this;

    }

    public function remove(string $userid): bool
    {
        // TODO lookup the DB user row with this userid
        return true;
    }


}