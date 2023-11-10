<?php declare(strict_types=1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Match

/*
$name = 'Adam';

switch ($name) {
    case 'Robert':
        $message = 'Hello Robert';
        break;
    case 'John':
        $message = 'Hello John';
        break;
    case 'Jane':
        $message = 'Hello Jane';
        break;
    case 'Adam':
    case 'Ewa':
        $message = 'Hello there. You are the firsts on the Earth!';
        break;
    default:
        $message = 'Hello unknown';
}

$message = match ($name) {
    'Robert'      => 'Hello Robert',
    'John'        => 'Hello John',
    'Jane'        => 'Hello Jane',
    'Adam', 'Ewa' => 'Hello there. You are the firsts on the Earth!',
    default       => 'Hello unknown',
};

echo $message;
*/

// Argument names

/*
function person($name, $lastName, $age, $address = null, $bio = null): void
{
    echo 'Hello ' . $name . ' ' . $lastName . '. You are ' . $age . ' years old.';

    if ($address) echo ' You live in: ' . $address;
    if ($bio) echo ' This is your bio: ' . $bio;
}

//person('Robert', 'Apollo', 14, null, 'My bio');

person(name: 'Robert', lastName: 'Apollo', age: 14, bio: 'My bio');

class A
{

    public function __construct($name, $age)
    {
        echo '<br>Hello ' . $name . ' You are ' . $age . ' years old.';
    }
}

new A(age: 3, name: 'Adam');

*/

// Constructor Property Promotion

/*
class User
{
    public $name;
    public $age;

    public function __construct($name, $age)
    {
        $this->name = $name;
        $this->age = $age;
    }
}

class User
{
    public function __construct(public string $name, public int $age)
    {
    }
}

$user = new User('John', 45);

echo $user->name . ' ' . $user->age;
*/

// Null Safe Operator

class Address
{
    public function country(): string
    {
        return 'USA';
    }
}


class User
{
    public function __construct(public string $name, public int $age)
    {
    }

    public function address()
    {
        //       return new Address();
        return null;
    }
}

$user = new User('John', 45);

echo $user?->name . ' ' . $user?->age . ' ' . ($user?->address()?->country() ?? 'homeless');
