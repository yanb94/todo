<?php

namespace Tests\AppBundle\Form;

use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Symfony\Component\Form\Test\TypeTestCase;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validation;

class UserTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        return array(new ValidatorExtension(Validation::createValidator()));
    }

    public function testSubmitValidData()
    {
        $formData = array(
            'username' => 'user',
            'password' => [
                "first" => "password",
                "second" => "password"
            ],
            'email' => "email@email.com",
            'roles' => "ROLE_USER"
        );

        $form = $this->factory->create(UserType::class);

        $object = new User();

        $object->setUsername('user');
        $object->setPassword('password');
        $object->setEmail("email@email.com");
        $object->setRoles(['ROLE_USER']);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());


        $this->assertEquals($object->getUsername(), $form->getData()['username']);
        $this->assertEquals($object->getPassword(), $form->getData()['password']);
        $this->assertEquals($object->getEmail(), $form->getData()['email']);
        $this->assertEquals($object->getRoles(), $form->getData()['roles']);


        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
