<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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

	/**
     * @dataProvider endpointUrlProvider
     */
	public function testGetUnauthorizedEndpoint($endpoint)
	{
		$client = static::createClient();

		$client->request('GET', $endpoint);

		$this->assertEquals(401, $client->getResponse()->getStatusCode());
		$this->assertArrayHasKey("message", json_decode($client->getResponse()->getContent(), true));
	}

	public function endpointUrlProvider()
    {
		return [
			["/api/event/all"]
		];
    }
}