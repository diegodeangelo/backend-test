<?php
namespace App\Tests\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Tests\Functional\TestCase;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;

class PrivateApiControllerTest extends TestCase
{
	protected $token;

	public function setUp()
	{
		static::$client = static::createClient();
	}

	public function testGetToken()
	{
	    static::$client->request('POST', '/login_check', ['_username' => 'lexik', '_password' => 'dummy']);

	    $response = static::$client->getResponse();

        $this->assertInstanceOf(JWTAuthenticationSuccessResponse::class, $response);
        $this->assertTrue($response->isSuccessful());

        $body = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('token', $body, 'The response should have a "token" key containing a JWT Token.');
	    
	    $this->assertEquals(200, static::$client->getResponse()->getStatusCode());

	    $this->token = $body['token'];
	}

	public function testGetEventEndpoint()
	{
    	static::$client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $this->token));
		static::$client->request('GET', '/event/all');

		$this->assertEquals(200, static::$client->getResponse()->getStatusCode());
	}
}