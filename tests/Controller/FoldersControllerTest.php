<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FoldersControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/v1/folders/test');
        $expected = ['test' => 'test'];
        
        $this->assertResponseIsSuccessful();
        $this->assertEquals($expected, json_decode($client->getResponse()->getContent(), true));
    }
}
