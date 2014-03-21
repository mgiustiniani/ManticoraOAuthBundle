<?php
/**
 * User: Mario Giustiniani
 * Date: 25/12/13
 * Time: 20.32
 */

namespace Manticora\OAuthBundle\Entity;
use FOS\OAuthServerBundle\Entity\AuthCode as BaseAuthCode;
use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Model\ClientInterface;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * @ORM\Entity
 */
class AuthCode extends BaseAuthCode
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="\FOS\OAuthServerBundle\Model\ClientInterface")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $client;


    /**
     * @ORM\ManyToOne(targetEntity="Manticora\OAuthBundle\Model\UserOauthInterface")
     */
    protected $user;
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set client
     *
     * @param \FOS\OAuthServerBundle\Model\ClientInterface $client
     * @return AuthCode
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
    
        return $this;
    }

    /**
     * Get client
     *
     * @return \Manticora\OAuthBundle\Entity\Client 
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set user
     *
     * @param \ToroEdizioni\BetFansBundle\Entity\User $user
     * @return AuthCode
     */
    public function setUser(UserInterface $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \ToroEdizioni\BetFansBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}