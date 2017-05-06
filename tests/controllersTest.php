<?php declare(strict_types = 1);

use Silex\WebTestCase;

class controllersTest extends WebTestCase
{
    /**
     * @dataProvider randomDateProvider
     */
    public function testRandomDate($path, $expectedMessage)
    {
        $client = $this->createClient();
        $client->request('GET', $path);

        $this->assertSame(
            ['message' => $expectedMessage],
            json_decode($client->getResponse()->getContent(), true)
        );
    }

    public function randomDateProvider()
    {
        $days = [];
        for ($day = 0; $day < 365; $day++) {
            $date = DateTime::createFromFormat('Y-z', '2001-'.$day);
            $days[] = [
                sprintf(
                    '/%s/%s/%s',
                    $date->format('Y'),
                    $date->format('m'),
                    $date->format('d')
                ),
                sprintf('Its been %s years since this day in %s', $day, 2001 - $day),
            ];
        }

        return $days;
    }

    public function createApplication()
    {
        $app = require __DIR__.'/../src/app.php';
        require __DIR__.'/../config/test.php';
        require __DIR__.'/../src/controllers.php';
        $app['session.test'] = true;

        return $this->app = $app;
    }
}
