<?php
/**
 * Initial a dependency injection container that implemented PSR-11 and return the container.
 */

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

use Hyperf\Di\Container;
use Hyperf\Config\ProviderConfig;
use Hyperf\Di\Annotation\Scanner;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Di\Definition\DefinitionSource;

$configFromProviders = ProviderConfig::load();
$definitions = include __DIR__ . '/dependencies.php';
$serverDependencies = array_replace($configFromProviders['dependencies'] ?? [], $definitions['dependencies'] ?? []);

$annotations = include __DIR__ . '/autoload/annotations.php';
$scanDirs = $configFromProviders['scan']['paths'];
$scanDirs = array_merge($scanDirs, $annotations['scan']['paths'] ?? []);

$container = new Container(new DefinitionSource($serverDependencies, $scanDirs, new Scanner()));

if (! $container instanceof \Psr\Container\ContainerInterface) {
    throw new RuntimeException('The dependency injection container is invalid.');
}
return ApplicationContext::setContainer($container);