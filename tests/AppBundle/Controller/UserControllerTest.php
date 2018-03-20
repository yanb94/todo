<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
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
     * @dataProvider listOfRoute
     */
    public function testNoForMember($url)
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'member',
            'PHP_AUTH_PW'   => 'password'
        ));

        $crawler = $client->request('GET', $url);

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider listOfRoute
     */
    public function testOnlyForAdmin($url)
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'password'
        ));

        $crawler = $client->request('GET', $url);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAddUser()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'password'
        ));
        $crawler = $client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form();

        $form['user[username]'] = "new";
        $form['user[password][first]'] = "password";
        $form['user[password][second]'] = "password";
        $form['user[email]'] = "monnouvelle@email.com";
        $form['user[roles]'] = "ROLE_USER";

        $crawler = $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }

    public function testEditUser()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'password'
        ));
        $crawler = $client->request('GET', '/users/1/edit');

        $form = $crawler->selectButton('Modifier')->form();

        $form['user[username]'] = "member";
        $form['user[password][first]'] = "password";
        $form['user[password][second]'] = "password";
        $form['user[email]'] = "member33@email.com";
        $form['user[roles]'] = "ROLE_USER";

        $crawler = $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }

    /**
     * @dataProvider badDataAddUser
     */
    public function testBadUserAdd($data)
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'password'
        ));
        $crawler = $client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form();

        $form['user[username]'] = $data['user[username]'];
        $form['user[password][first]'] = $data['user[password][first]'];
        $form['user[password][second]'] = $data['user[password][second]'];
        $form['user[email]'] = $data['user[email]'];
        $form['user[roles]'] = $data['user[roles]'];

        $crawler = $client->submit($form);

        $this->assertGreaterThanOrEqual(1, $crawler->filter('span.help-block')->count());
    }


    public function listOfRoute()
    {
        return [
            ['/users'],
            ['/users/create'],
            ['/users/1/edit']
        ];
    }

    public function badDataAddUser()
    {
        return [
            // username already use
            [
                [
                    "user[username]"=>"admin",
                    "user[password][first]"=> "password",
                    "user[password][second]"=> "password",
                    "user[email]"=> "other@email.com",
                    "user[roles]"=> "ROLE_USER"
                ]
            ],
            // email already use
            [
                [
                    "user[username]"=>"othernew",
                    "user[password][first]"=> "password",
                    "user[password][second]"=> "password",
                    "user[email]"=> "admin@email.com",
                    "user[roles]"=> "ROLE_USER"
                ]
            ],
            // not same password
            [
                [
                    "user[username]"=>"othernew",
                    "user[password][first]"=> "password",
                    "user[password][second]"=> "pasrd",
                    "user[email]"=> "other@email.com",
                    "user[roles]"=> "ROLE_USER"
                ]
            ],
            // blank fields
            [
                [
                    "user[username]"=>"",
                    "user[password][first]"=> "",
                    "user[password][second]"=> "",
                    "user[email]"=> "",
                    "user[roles]"=> "ROLE_USER"
                ]
            ],
        ];
    }
}
