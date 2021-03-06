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
 * Redis definition.
 *
 * @author Fabio B. Silva <fabio.bat.silva@gmail.com>
 */
class RedisDefinition extends CacheDefinition
{
    /**
     * {@inheritDoc}
     */
    public function configure($name, array $config, Definition $service, ContainerBuilder $container)
    {
        $host       = $config['redis']['host'];
        $port       = $config['redis']['port'];
        $connClass  = '%doctrine_cache.redis.connection.class%';
        $connId     = sprintf('doctrine_cache.services.%s_redis.connection', $name);
        $connDef    = new Definition($connClass);

        $connDef->setPublic(false);
        $connDef->addMethodCall('connect', array($host, $port));

        $container->setDefinition($connId, $connDef);
        $service->addMethodCall('setRedis', array($container->setDefinition($connId, $connDef)));
    }
}
