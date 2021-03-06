<?php

/*
 * This file is part of the FOSOAuthServerBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Manticora\OAuthBundle\Security\Authentication\Provider;

use FOS\OAuthServerBundle\Model\Token;
use FOS\OAuthServerBundle\Security\Authentication\Token\OAuthToken;
use FOS\OAuthServerBundle\Security\Authentication\Provider\OAuthProvider as BaseOAuthProvider;
use Manticora\OAuthBundle\Entity\Client;
use Manticora\OAuthBundle\Model\ClientRoleableInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

use OAuth2\OAuth2;
use OAuth2\OAuth2ServerException;
use OAuth2\OAuth2AuthenticateException;

/**
 * OAuthProvider class.
 *
 * @author  Arnaud Le Blanc <arnaud.lb@gmail.com>
 */
class OAuthProvider extends BaseOAuthProvider
{

    /**
     * {@inheritdoc}
     */
    public function authenticate(TokenInterface $token)
    {
        if (!$this->supports($token)) {
            return null;
        }

        try {
            $tokenString = $token->getToken();
            if ($accessToken = $this->serverService->verifyAccessToken($tokenString)) {
                $scope = $accessToken->getScope();
                $user  = $accessToken->getUser();


                $roles = (null !== $user) ? $user->getRoles() : array();
                if($accessToken instanceof Token
                    &&  $accessToken->getClient() instanceof ClientRoleableInterface
                      ) {

                    $client =$accessToken->getClient();


                   $roles = array_merge($roles,  $client->getRoles() );

                }

                if (!empty($scope)) {
                    foreach (explode(' ', $scope) as $role) {
                        $roles[] = 'ROLE_' . strtoupper($role);
                    }
                }

                $token = new OAuthToken($roles);
                $token->setAuthenticated(true);
                $token->setToken($tokenString);

                if (null !== $user) {

                    try {
                        $this->userChecker->checkPostAuth($user);
                    } catch (AccountStatusException $e) {
                        throw new OAuth2AuthenticateException(OAuth2::HTTP_UNAUTHORIZED,
                            OAuth2::TOKEN_TYPE_BEARER,
                            $this->serverService->getVariable(OAuth2::CONFIG_WWW_REALM),
                            'access_denied',
                            $e->getMessage()
                        );
                    }

                    $token->setUser($user);
                }

                return $token;
            }
        } catch (OAuth2ServerException $e) {
            if (!method_exists('Symfony\Component\Security\Core\Exception\AuthenticationException', 'setToken')) {
                // Symfony 2.1
                throw new AuthenticationException('OAuth2 authentication failed', null, 0, $e);
            }
            
            throw new AuthenticationException('OAuth2 authentication failed', 0, $e);
        }

        throw new AuthenticationException('OAuth2 authentication failed');
    }

}
