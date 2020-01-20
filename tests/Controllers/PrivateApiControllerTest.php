<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PrivateAPiControllerTest extends WebTestCase
{
	protected function createAuthenticatedClient($username = 'user', $password = 'password')
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
	 * test getPagesAction
	 */
	public function testGetPages()
	{
	    $client = $this->createAuthenticatedClient();
	    $client->request('GET', '/api/event/all');
	    // ... 
	}
}