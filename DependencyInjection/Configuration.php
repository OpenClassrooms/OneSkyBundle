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
            ->scalarNode('project_id')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('file_format')->cannotBeEmpty()->defaultValue('xliff')->end()
            ->scalarNode('source_locale')->cannotBeEmpty()->defaultValue('en')->end()
            ->arrayNode('locales')->requiresAtLeastOneElement()->prototype('scalar')->end()->end()
            ->arrayNode('file_paths')->requiresAtLeastOneElement()->prototype('scalar')->end()->end()
            ->scalarNode('keep_all_strings')->cannotBeEmpty()->defaultValue(true)->end()
            ->end();

        return $treeBuilder;
    }
}
