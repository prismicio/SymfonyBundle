<?php

namespace Prismic\Bundle\PrismicBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class PrismicExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $container->setParameter($this->getAlias() . '.api.endpoint', $config['api']['endpoint']);
        $container->setParameter($this->getAlias() . '.api.accessToken', $config['api']['access_token']);
        $container->setParameter($this->getAlias() . '.api.clientId', $config['api']['client_id']);
        $container->setParameter($this->getAlias() . '.api.clientSecret', $config['api']['client_secret']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}
