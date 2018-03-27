<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use AppBundle\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $member = new User();
        $member->setEmail('member@email.com');
        $member->setUsername('member');
        $member->setPassword('password');
        $member->setRoles(['ROLE_USER']);

        $admin = new User();

        $admin->setEmail('admin@email.com');
        $admin->setUsername('admin');
        $admin->setPassword('password');
        $admin->setRoles(['ROLE_ADMIN']);

        $encoder = $this->container->get('security.password_encoder');

        $passwordMember = $encoder->encodePassword($member, $member->getPassword());
        $passwordAdmin = $encoder->encodePassword($admin, $admin->getPassword());

        $member->setPassword($passwordMember);
        $admin->setPassword($passwordAdmin);

        $taskOne = new Task();

        $taskOne->setTitle('Tache 1');
        $taskOne->setContent('Contenu n°1');
        $taskOne->setAuthor($member);

        $taskTwo = new Task();

        $taskTwo->setTitle('Tache 2');
        $taskTwo->setContent('Contenu n°2');
        $taskTwo->setAuthor($admin);

        $taskAnonyme = new Task();

        $taskAnonyme->setTitle("Tache 3");
        $taskAnonyme->setContent('Contenu 3');

        $manager->persist($member);
        $manager->persist($admin);

        $manager->persist($taskOne);
        $manager->persist($taskTwo);
        $manager->persist($taskAnonyme);

        $manager->flush();
    }
}
