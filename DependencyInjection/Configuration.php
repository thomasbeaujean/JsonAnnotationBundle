<?php

namespace tbn\JsonAnnotationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\NodeInterface;

/**
 * FrameworkExtraBundle configuration structure.
 *
 * @author Thomas Beaujean
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return NodeInterface
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('json_annotation', 'array');


        $rootNode
        ->children()
            ->scalarNode('exception_code')
                ->defaultValue(500)
            ->end()
            ->scalarNode('data_key')
                ->defaultValue('data')
            ->end()
            ->scalarNode('exception_message_key')
                ->defaultValue('message')
            ->end()
            ->scalarNode('success_key')
                ->defaultValue('success')
            ->end()
            ->scalarNode('post_query_back')
                ->defaultValue(false)
            ->end()
            ->scalarNode('post_query_key')
                ->defaultValue('query')
            ->end()
        ->end();

        return $treeBuilder;
    }
}
