<?php
declare(strict_types=1);

namespace Eos\Bundle\VueAuthenticate\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('eos_vue_authenticate');
        $root = $treeBuilder->getRootNode()->children();

        $twitter = $root->arrayNode('twitter')->children();
        $twitter->scalarNode('key')->defaultNull();
        $twitter->scalarNode('secret')->defaultNull();

        $google = $root->arrayNode('google')->children();
        $google->scalarNode('key')->defaultNull();
        $google->scalarNode('secret')->defaultNull();

        $facebook = $root->arrayNode('facebook')->children();
        $facebook->scalarNode('key')->defaultNull();
        $facebook->scalarNode('secret')->defaultNull();

        return $treeBuilder;
    }
}
