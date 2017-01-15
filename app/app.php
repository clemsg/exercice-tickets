<?php

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use SrcTickets\Repository\TicketsRepository;
use SrcTickets\Services\TicketsService;

ErrorHandler::register();
ExceptionHandler::register();

/*
 * Appel du bundle doctrine dbal
 */
$app->register(new \Silex\Provider\DoctrineServiceProvider());
/*
 * appel du bundle form
 */
$app->register(new \Silex\Provider\FormServiceProvider());
/*
 * appel du bundle traduction (obliger de l'intialiser pour le form bundle)
 */
$app->register(new \Silex\Provider\TranslationServiceProvider(), array(
    'translator.domains' => array(),
    'locale' => 'fr'
));
/*
 * appel de twig
 */
$app->register(new \Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../views'
));

/*
 * appel de ma class de requetes
 */
$app['dao.tickets'] = function ($app) {
	return new TicketsRepository($app['db']);
};

/*
 * appel de mon service pour traiter le csv
 */
$app['services.tickets'] = function($app){
    return new TicketsService($app['dao.tickets']);
};