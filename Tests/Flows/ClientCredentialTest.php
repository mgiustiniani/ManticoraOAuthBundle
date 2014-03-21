<?php
/**
 * User: Mario Giustiniani
 * Date: 25/12/13
 * Time: 21.26
 */

namespace Manticora\OAuthBundle\Tests\Flows;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClientCredentialTest extends WebTestCase {
    /**
     * @var
     */
    private $client;
    /**
     * @var \Manticora\OAuthBundle\Entity\Client
     */
    private $clientCredential;
    public function setUp()
    {

        $this->client = static::createClient();
        $clientManager = $this->client->getContainer()->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setName('test');
        $client->setRedirectUris(array('test'));
        $client->setAllowedGrantTypes(array('client_credentials'));
        $clientManager->updateClient($client);
        $this->clientCredential = $client;
    }


    public function testGetToken() {
        $crawler = $this->client->request('GET', '/oauth/v2/token?grant_type=client_credentials&client_id='.$this->clientCredential->getPublicId().'&client_secret='.$this->clientCredential->getSecret());
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(
            $response->token_type,
            'bearer'
        );

        $this->assertEquals(
            200,
            $this->client->getResponse()->getStatusCode()
        );
    }
    public function testClientCredentialsAccessAllowed()
    {

        $crawler = $this->client->request('GET', '/oauth/v2/token?grant_type=client_credentials&client_id='.$this->clientCredential->getPublicId().'&client_secret='.$this->clientCredential->getSecret());
 $response = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals(
            $response->token_type,
           'bearer'
        );

        $this->assertEquals(
            200,
            $this->client->getResponse()->getStatusCode()
        );

        $this->client->request('GET', '/api/client',
        array(),
        array(),
        array('HTTP_AUTHORIZATION'=>'Bearer '.$response->access_token)
        );
      //  var_dump($response);
        $this->assertEquals(
            200,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testNoClientCredentialsAccessDenied() {
        $crawler = $this->client->request('GET', '/api/client');
        $response = json_decode($this->client->getResponse()->getContent());


        $this->assertEquals(
            $response->error,
            'access_denied'
        );

        $this->assertEquals(
            401,
            $this->client->getResponse()->getStatusCode()
        );

    }



} 