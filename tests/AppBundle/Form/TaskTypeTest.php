<?php

namespace Tests\AppBundle\Form;

use AppBundle\Form\TaskType;
use AppBundle\Entity\Task;
use Symfony\Component\Form\Test\TypeTestCase;

class TaskTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = array(
            'title' => 'Ma tache',
            'content' => 'Je suis le contenu',
        );

        $form = $this->factory->create(TaskType::class);

        $object = new Task();

        $object->setTitle($formData['title']);
        $object->setContent($formData['content']);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object->getTitle(), $form->getData()['title']);
        $this->assertEquals($object->getContent(), $form->getData()['content']);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
