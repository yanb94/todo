<?php

namespace Tests\AppBundle\Entity;

use PHPUnit\Framework\TestCase;
use AppBundle\Entity\Task;
use AppBundle\Entity\User;

class UserTest extends TestCase
{
    public function testGetterAndSetter()
    {
        $user = new User();

        $username = "user";

        $user->setUsername($username);

        $this->assertEquals($username, $user->getUsername());

        $password = "password";

        $user->setPassword($password);

        $this->assertEquals($password, $user->getPassword());

        $email = "email@email.com";

        $user->setEmail($email);

        $this->assertEquals($email, $user->getEmail());

        $roles = ['ROLE_USER'];

        $user->setRoles($roles);

        $this->assertEquals($roles, $user->getRoles());

        $this->assertEquals(null, $user->getSalt());
    }
}
