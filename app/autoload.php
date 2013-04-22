<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = require __DIR__.'/../vendor/autoload.php';
$loader->add('FOS', __DIR__.'/../vendor/bundles');
$loader->add('FOS\\Rest',  __DIR__.'/../vendor/fos');
$loader->add('JMS',  __DIR__.'/../vendor/jms');
$loader->add('Metadata',  __DIR__.'/../vendor/metadata/src');


// intl
if (!function_exists('intl_get_error_code')) {
    require_once __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';
}

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
