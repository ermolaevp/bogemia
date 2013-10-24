<?php
/**
 * Created at: 06.03.13
 */
date_default_timezone_set('Europe/Moscow');

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/lib/Autoloader.php';

require_once __DIR__ . '/doctrine.php';

//require_once __DIR__ . '/phpQuery-onefile.php';

$app = new Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\FormServiceProvider());

$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->register(new Silex\Provider\TranslationServiceProvider());

$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/templates',
    'twig.cache' => __DIR__ . '/templates_c',
));

/*$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/log/'. date('Y-m-d') .'_development.log',
    'monolog.level' => \Symfony\Bridge\Monolog\Logger::NOTICE,
));*/

//$app->register(new Providers\ImagineServiceProvider());

$app['twig']->addExtension(new \lib\Twig\Extension\BohemiaTwigExtension($app));

$app['em'] = $em;

//$app->register(new lib\Provider\DatabaseServiceProvider());
