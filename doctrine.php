<?php

use Doctrine\Common\ClassLoader,
    Doctrine\ORM\Configuration,
    Doctrine\ORM\EntityManager,
    Doctrine\Common\Cache\ApcCache;

// Set up class loading. You could use different autoloaders, provided by your favorite framework,
// if you want to.
$classLoader = new ClassLoader('Doctrine\ORM');
$classLoader->register();
$classLoader = new ClassLoader('Doctrine\DBAL');
$classLoader->register();
$classLoader = new ClassLoader('Doctrine\Common');
$classLoader->register();
$classLoader = new ClassLoader('Symfony');
$classLoader->register();
$classLoader = new ClassLoader('Entities');
$classLoader->register();
$classLoader = new ClassLoader('Proxies');
$classLoader->register();

// Set up caches
$config = new Configuration;
$cache = new ApcCache;
$config->setMetadataCacheImpl($cache);
$driverImpl = $config->newDefaultAnnotationDriver(array(__DIR__."/Entities"));
$config->setMetadataDriverImpl($driverImpl);
//$config->setQueryCacheImpl($cache);

// Proxy configuration
$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);

// Database connection information

$conf = parse_ini_file(__DIR__ . '/config/config.ini', true);

$connectionOptions = $conf['db'];

// Create EntityManager
$em = EntityManager::create($connectionOptions, $config);