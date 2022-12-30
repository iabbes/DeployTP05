<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
date_default_timezone_set('America/Lima');
require_once "vendor/autoload.php";
$isDevMode = true;
$config = Setup::createYAMLMetadataConfiguration(array(__DIR__ . "/config/yaml"), $isDevMode);
$conn = array(
'host' => 'dpg-cdr3h51gp3jnj84cbjag-a',
'driver' => 'pdo_pgsql',
'user' => 'tp05_web_cnam_user',
'password' => 'S6MCVRGsBs0mro4gQqXNOUdY2htJPlLC',
'dbname' => 'tp05_web_cnam',
'port' => '5432'
);
$entityManager = EntityManager::create($conn, $config);