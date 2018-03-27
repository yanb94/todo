<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginShow()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAuthentificationAdmin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();

        $form['_username'] = "admin";
        $form['_password'] = "password";

        $crawler = $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertSame(1, $crawler->filter('a.btn.btn-primary:contains("Créer un utilisateur")')->count());
        $this->assertSame(1, $crawler->filter('a.btn.btn-danger:contains("Se déconnecter")')->count());
    }

    public function testAuthentificationMember()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();

        $form['_username'] = "member";
        $form['_password'] = "password";

        $crawler = $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertSame(0, $crawler->filter('a.btn.btn-primary:contains("Créer un utilisateur")')->count());
        $this->assertSame(1, $crawler->filter('a.btn.btn-danger:contains("Se déconnecter")')->count());
    }

    public function testBadAuthentification()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();

        $form['_username'] = "member";
        $form['_password'] = "pass";

        $crawler = $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertSame(1, $crawler->filter('div.alert.alert-danger')->count());
    }
}
