<?php

namespace Prismic\Bundle\PrismicBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 *
 * Class Configuration
 *
 * @package Prismic\Bundle\PrismicBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('prismic')
            ->children()
                ->arrayNode('api')
                    ->isRequired()
                    ->children()
                        ->scalarNode('endpoint')->isRequired()->end()
                        ->scalarNode('access_token')->defaultNull()->end()
                        ->scalarNode('client_id')->defaultNull()->end()
                        ->scalarNode('client_secret')->defaultNull()->end()
                    ->end()
                ->end()
                ->arrayNode('oauth')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('redirect_route')->defaultValue('home')->end()
                        ->scalarNode('redirect_route_params')->defaultValue([])->end()
                    ->end()
                ->end()
                ->scalarNode('cache')->defaultTrue()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
