<?php

namespace SimpleUser\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('simple_user');

        $rootNode
            ->children()
                ->scalarNode('user_class')
                    ->isRequired()
                ->end()
                ->scalarNode('role_class')
                    ->isRequired()
                ->end()
                ->scalarNode('redirect_after_login')
                    ->isRequired()
                ->end()
                ->scalarNode('firewall_name')
                    ->isRequired()
                ->end()
                ->arrayNode('email')
                    ->children()
                        ->scalarNode('from')->isRequired()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
