<?php
declare(strict_types=1);

namespace Eos\Bundle\VueAuthenticate\DependencyInjection;

use Abraham\TwitterOAuth\TwitterOAuth;
use Eos\Bundle\VueAuthenticate\Controller\FacebookController;
use Eos\Bundle\VueAuthenticate\Controller\GoogleController;
use Eos\Bundle\VueAuthenticate\Controller\InternalUserController;
use Eos\Bundle\VueAuthenticate\Controller\TwitterController;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class EosVueAuthenticateExtension extends ConfigurableExtension
{
    /**
     * @param array $mergedConfig
     * @param ContainerBuilder $container
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $container->autowire(InternalUserController::class)
            ->setPublic(true);

        if (
            array_key_exists('twitter', $mergedConfig)
            && $mergedConfig['twitter']['key']
            && $mergedConfig['twitter']['secret']
        ) {
            $container->autowire(TwitterOAuth::class)
                ->addArgument($mergedConfig['twitter']['key'])
                ->addArgument($mergedConfig['twitter']['secret'])
                ->setPublic(false);

            $container->autowire(TwitterController::class)
                ->setPublic(true);
        }

        $container->autowire(GoogleController::class)
            ->setPublic(true);

        $container->autowire(FacebookController::class)
            ->setPublic(true);
    }
}
