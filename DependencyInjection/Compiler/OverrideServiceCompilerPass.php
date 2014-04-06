<?php
/**
 * User: Mario Giustiniani
 * Date: 06/04/14
 * Time: 19.22
 */

namespace Manticora\OAuthBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('fos_oauth_server.security.authentication.provider');
        $definition->setClass('Manticora\OAuthBundle\Security\Authentication\Provider\OAuthProvider');
    }
}