<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    /**
     * @dataProvider listOfRoute
     */
    public function testNoForAnonymous($url)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider listOfRouteWithNoAction
     */
    public function testRouteWithNoAction($url)
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'member',
            'PHP_AUTH_PW'   => 'password'
        ));
        $crawler = $client->request('GET', $url);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testRemoveByBadAuthor()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'password'
        ));

        $crawler = $client->request('GET', '/tasks/1/delete');

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testRemoveByGoodAuthor()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'member',
            'PHP_AUTH_PW'   => 'password'
        ));

        $crawler = $client->request('GET', '/tasks/1/delete');

        $crawler = $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testRemoveByMemberAnonymousTask()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'member',
            'PHP_AUTH_PW'   => 'password'
        ));

        $crawler = $client->request('GET', '/tasks/3/delete');

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testRemoveByAdminAnonymousTask()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'password'
        ));

        $crawler = $client->request('GET', '/tasks/3/delete');

        $crawler = $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testToggle()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'password'
        ));

        $crawler = $client->request('GET', '/tasks/2/toggle');

        $crawler = $client->followRedirect();

        $this->assertEquals(1, $crawler->filter('span.glyphicon.glyphicon-ok')->count());
    }


    public function testAddTask()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'member',
            'PHP_AUTH_PW'   => 'password'
        ));

        $crawler = $client->request('GET', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form();

        $form["task[title]"] = "Tache de test 1";
        $form["task[content]"] = "Contenu de la tache";

        $crawler = $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        $this->assertSame(1, $crawler->filter('span:contains("De member")')->count());
    }

    public function testEditTask()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'password'
        ));

        $crawler = $client->request('GET', '/tasks/2/edit');

        $form = $crawler->selectButton('Modifier')->form();

        $form["task[title]"] = "Tache de test 2";
        $form["task[content]"] = "Contenu de la tache";

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        $this->assertSame(1, $crawler->filter('h4 > a:contains("Tache de test 2")')->count());
    }

    /**
     * @dataProvider badData
     */
    public function testAddTaskBadData($data)
    {

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'member',
            'PHP_AUTH_PW'   => 'password'
        ));

        $crawler = $client->request('GET', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form();

        $form["task[title]"] = $data["task[title]"];
        $form["task[content]"] = $data["task[content]"];

        $crawler = $client->submit($form);

        $this->assertGreaterThanOrEqual(1, $crawler->filter('span.help-block')->count());
    }

    public function listOfRoute()
    {
        return [
            ['/tasks'],
            ['/tasks/create'],
            ['/tasks/1/edit'],
            ['/tasks/1/toggle'],
            ['/tasks/1/delete']
        ];
    }

    public function listOfRouteWithNoAction()
    {
        return [
            ['/tasks'],
            ['/tasks/create'],
            ['/tasks/1/edit']
        ];
    }

    public function badData()
    {
        return [
            [
                [
                    "task[title]" => "",
                    "task[content]" => "Mon Contenu"
                ]
            ],
            [
                [
                    "task[title]" => "Ma tache",
                    "task[content]" => ""
                ]
            ],
            [
                [
                    "task[title]" => "",
                    "task[content]" => ""
                ]
            ],
        ];
    }
}
