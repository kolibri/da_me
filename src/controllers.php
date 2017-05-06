<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Yaml\Yaml;
use DaMe\DaMe;

$app
    ->get(
        '/{year}/{month}/{day}',
        function ($year, $month, $day) use($app) {
            /** @var DaMe $daMe */
            $daMe = $app['da_me'];

            $date = DateTime::createFromFormat('Y-m-d', sprintf('%s-%s-%s', $year, $month, $day));

            try {
                $dateMessage = $daMe->getRandomDateMessage($date);
            } catch (\DaMe\NotFoundException $e) {
                return new JsonResponse(['error' => $e->getMessage()], 404);
            }

            return new JsonResponse(['message' => $dateMessage->format($date)]);
        }
    )
    ->value('year', (new \DateTime())->format('Y'))
    ->value('month', (new \DateTime())->format('m'))
    ->value('day', (new \DateTime())->format('d'))
    ->assert('year', '\d\d\d\d')
    ->assert('month', '\d\d')
    ->assert('day', '\d\d')
    ->bind('random');

$app
    ->get('/fixtures', function (){

        $fixtures = [];
        for ($day = 0; $day < 365; $day++) {
            $date = DateTime::createFromFormat('Y-z', '2001-'.$day);

            $monthDay =  $date->format(DaMe::FORMAT_MONTH_DAY);
            if (!array_key_exists($monthDay, $fixtures)) {
                $fixtures[$monthDay] = [];
            }

            $fixtures[$monthDay][] = sprintf('Its been %s years since this day in %s', $day, 2001 - $day);
        }

        return new Response(Yaml::dump($fixtures));

    });

$app->error(
    function (\Exception $e, Request $request, $code) use ($app) {
        if ($app['debug']) {
            return;
        }

        // 404.html, or 40x.html, or 4xx.html, or error.html
        $templates = array(
            'errors/'.$code.'.html.twig',
            'errors/'.substr($code, 0, 2).'x.html.twig',
            'errors/'.substr($code, 0, 1).'xx.html.twig',
            'errors/default.html.twig',
        );

        return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
    }
);
