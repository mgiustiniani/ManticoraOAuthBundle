<?php
/**
 * User: Mario Giustiniani
 * Date: 25/12/13
 * Time: 19.25
 */

namespace Manticora\OAuthBundle\Entity;

use FOS\OAuthServerBundle\Entity\Client as BaseClient;
use Doctrine\ORM\Mapping as ORM;
use Manticora\OAuthBundle\Model\ClientRoleableInterface;

/**
 * @ORM\Entity
 */
class Client extends BaseClient implements ClientRoleableInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *  @ORM\Column(type="string")
     */
    protected $name;

    /**
     *  @ORM\Column(type="array")
     */
    protected $roles =  array();

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

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
     * Set name
     *
     * @param string $name
     * @return Client
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    public function getRoles()
    {
        return $this->roles;
    }
}