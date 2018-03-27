<?php

namespace Tests\AppBundle\Entity;

use PHPUnit\Framework\TestCase;
use AppBundle\Entity\Task;
use AppBundle\Entity\User;

class TaskTest extends TestCase
{
    public function testGetterAndSetter()
    {
        $task = new Task();

        $this->assertEquals(false, $task->isDone());

        $this->assertInstanceOf(\DateTime::class, $task->getCreatedAt());

        $date = new \Datetime('now');

        $task->setCreatedAt($date);

        $this->assertEquals($date, $task->getCreatedAt());

        $title = "Mon Titre";

        $task->setTitle($title);

        $this->assertEquals($title, $task->getTitle());

        $content = "Je suis le contenu";

        $task->setContent($content);

        $this->assertEquals($content, $task->getContent());

        $task->toggle(true);

        $this->assertEquals(true, $task->isDone());

        $user = new User();

        $task->setAuthor($user);

        $this->assertEquals($user, $task->getAuthor());
    }
}
