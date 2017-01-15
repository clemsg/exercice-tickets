<?php
/*
 * bootstrap pour initialiser silex, la config de la bdd, le kernel et les routes
 */
require_once __DIR__ . '/vendor/autoload.php';

$app = new Silex\Application();

require __DIR__ . '/app/config.php';
require __DIR__ . '/app/app.php';
require __DIR__ . '/app/routes.php';

$app->run();