<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @dataProvider loadFixture
     */
    public function testIndexNoAuthentified($param)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testIndexAuthentified()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'member',
            'PHP_AUTH_PW'   => 'password'
        ));

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function loadFixture()
    {
        echo shell_exec('php bin/console doctrine:schema:drop --force --env=test');
        echo shell_exec('php bin/console doctrine:schema:create --env=test');
        echo shell_exec('php bin/console doctrine:fixtures:load --env=test');

        return [
            [""]
        ];
    }
}
