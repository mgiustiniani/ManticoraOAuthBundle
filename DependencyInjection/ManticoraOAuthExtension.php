<?php

namespace Manticora\OAuthBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ManticoraOAuthExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
    }

    /**
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {

        // process the configuration of AcmeHelloExtension
        $configs = $container->getExtensionConfig($this->getAlias());
        // use the Configuration class to generate a config array with the settings ``acme_hello``
        $config = $this->processConfiguration(new Configuration(), $configs);

        $configFOSOAUTHServer = array(
            'db_driver' => 'orm',
            "client_class" =>          $config['client_class'],
            "access_token_class" =>   "Manticora\\OAuthBundle\\Entity\\AccessToken",
            "refresh_token_class" =>  "Manticora\\OAuthBundle\\Entity\\RefreshToken",
            "auth_code_class" =>      "Manticora\\OAuthBundle\\Entity\\AuthCode"
        );

        $configFOSUserBundle = array(
            'db_driver' => 'orm',
            "user_class" =>          $config['user_class']
        );
        $configORM = array(
            'orm'=>array(
                'resolve_target_entities'=>array(
                    'FOS\OAuthServerBundle\Model\ClientInterface'=> $config['client_class'],
                    'Manticora\OAuthBundle\Model\UserOauthInterface'=> $config['user_class']
                )

            )
        );
        $container->prependExtensionConfig('doctrine', $configORM);
        $container->prependExtensionConfig('fos_user', $configFOSUserBundle);
        $container->prependExtensionConfig('fos_oauth_server',  $configFOSOAUTHServer);

    }
}
