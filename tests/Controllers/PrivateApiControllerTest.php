<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
//use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;

class PrivateApiControllerTest extends WebTestCase
{
	protected function createAuthenticatedClient($username = 'admin', $password = '123456')
	{
	    $client = static::createClient();

	    $client->request(
	      'POST',
	      '/api/login_check',
	      array(),
	      array(),
	      array('CONTENT_TYPE' => 'application/json'),
	      json_encode(array(
	        'username' => $username,
	        'password' => $password,
	        ))
	      );

	    $data = json_decode($client->getResponse()->getContent(), true);

	    $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

	    return $client;
	}

	public function testGetEventEndpoint()
	{
		$client = $this->createAuthenticatedClient();

		$client->request('GET', '/api/event/all');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}
}