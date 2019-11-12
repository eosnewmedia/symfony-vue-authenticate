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

        $twitter = $root->arrayNode('twitter')
            ->treatNullLike([])
            ->children();
        $twitter->scalarNode('key')->isRequired();
        $twitter->scalarNode('secret')->isRequired();

        return $treeBuilder;
    }
}