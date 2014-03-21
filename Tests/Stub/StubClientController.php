<?php
/**
 * User: Mario Giustiniani
 * Date: 25/12/13
 * Time: 21.31
 */

namespace Manticora\OAuthBundle\Tests\Stub;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Tests\Controller;

class StubClientController  {

    public function indexAction(){
        return new Response('clientSuccess');
    }

}
