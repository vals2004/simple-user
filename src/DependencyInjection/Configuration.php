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
            ->end()
        ;

        return $treeBuilder;
    }
}
