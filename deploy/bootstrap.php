<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
date_default_timezone_set('America/Lima');
require_once "vendor/autoload.php";
$isDevMode = true;
$config = Setup::createYAMLMetadataConfiguration(array(__DIR__ . "/config/yaml"), $isDevMode);
$conn = array(
'host' => 'dpg-ceu0vdha6gdut0r00pl0-a',
'driver' => 'pdo_pgsql',
'user' => 'projetwebcnam_user',
'password' => 'rflXMO4LctNLZVwSZM9qEYlk7uVex9OS',
'dbname' => 'projetwebcnam',
'port' => '5432'
);
$entityManager = EntityManager::create($conn, $config);