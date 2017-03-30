<?php

namespace OpenClassrooms\Bundle\OneSkyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Romain Kuzniak <romain.kuzniak@openclassrooms.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('openclassrooms_onesky');
        $rootNode->children()
            ->scalarNode('api_key')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('api_secret')->isRequired()->cannotBeEmpty()->end()
            ->arrayNode('projects')
            ->prototype('array')
                ->children()
                    ->scalarNode('file_format')->cannotBeEmpty()->defaultValue('xliff')->end()
                    ->scalarNode('source_locale')->cannotBeEmpty()->defaultValue('en')->end()
                    ->arrayNode('file_paths')->cannotBeEmpty()->prototype('scalar')->end()->end()
                    ->arrayNode('locales')->cannotBeEmpty()->prototype('scalar')->end()->end()
                    ->scalarNode('keep_all_strings')->cannotBeEmpty()->defaultValue(true)->end()
                ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
