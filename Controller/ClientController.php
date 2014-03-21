<?php
/**
 * User: Mario Giustiniani
 * Date: 25/12/13
 * Time: 21.31
 */

namespace Manticora\OAuthBundle\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Tests\Controller;

class ClientController  {

    public function indexAction(){
        return new Response('clientSuccess');
    }

}
