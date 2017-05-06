<?php

use Silex\WebTestCase;

class controllersTest extends WebTestCase
{
    public function testGetHomepage()
    {
        $client = $this->createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertContains('Welcome', $crawler->filter('body')->text());
    }

    public function testRandomDate()
    {
        $client = $this->createClient();

        for ($day = 0; $day < 365; $day++) {
            $date = DateTime::createFromFormat('Y-z', '2001-'.$day);
            $crawler = $client->request(
                'GET',
                sprintf(
                    '/%s/%s/%s', 
                    $date->format('Y'),
                    $date->format('m'),
                    $date->format('d')
                )
            );

            $expected = [
                'message' => sprintf('Its been %s years since this day in %s', $day, 2001 - $day),
            ];
            
            $response = json_decode($client->getResponse()->getContent());
            
            $this->assertSame($expected, $response);
        }
    }

    public function createApplication()
    {
        $app = require __DIR__.'/../src/app.php';
        require __DIR__.'/../config/dev.php';
        require __DIR__.'/../src/controllers.php';
        $app['session.test'] = true;

        return $this->app = $app;
    }
}
