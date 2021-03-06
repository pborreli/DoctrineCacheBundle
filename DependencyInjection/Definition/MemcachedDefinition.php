<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Doctrine\Bundle\DoctrineCacheBundle\DependencyInjection\Definition;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Memcached definition.
 *
 * @author Fabio B. Silva <fabio.bat.silva@gmail.com>
 */
class MemcachedDefinition extends CacheDefinition
{
    /**
     * {@inheritDoc}
     */
    public function configure($name, array $config, Definition $service, ContainerBuilder $container)
    {
        $connClass  = '%doctrine_cache.memcached.connection.class%';
        $connId     = sprintf('doctrine_cache.services.%s.connection', $name);
        $connDef    = new Definition($connClass);

        foreach ($config['memcached']['servers'] as $host => $server) {
            $connDef->addMethodCall('addServer', array($host, $server['port']));
        }

        $connDef->setPublic(false);
        $container->setDefinition($connId, $connDef);
        $service->addMethodCall('setMemcached', array($connDef));
    }
}
