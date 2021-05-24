<?php

require('../vendor/autoload.php');
require dirname(__FILE__).'/../php/functions/functions.php';

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// auxiliary rendering function. such a bad design
function render(string $templateName, array $args = null) {

    start_session();
    $args['user'] = $_SESSION['user'] ?? null;
    return $GLOBALS['app']['twig']->render($templateName, $args);
}

// Our web handlers
include_once 'routes.php';
use Symfony\Component\HttpFoundation\Request;
$app->error(function(\Exception $e, Request $request, $code) {
    $exceptionCode = $e->getCode();
    if (!empty($exceptionCode))
        $code = $exceptionCode;
    switch ($code) {
        case 404:
            $message = "Страница не найдена";
            break;
        case 403:
            $message = "Доступ воспрещён";
            break;
        default:
            $message = "Произошла какая-то ошибка";
    }
    return render('error_page.twig',
        array('message' => $message));
});

$app->run();
