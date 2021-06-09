<?php

namespace App\Tests\Controller;


use App\Tests\WebTestCase;

class PhantomControllerTest extends WebTestCase
{
    /**
     * @dataProvider provideUrls
     */
    public function testPageIsSuccessful($url)
    {
        $this->logIn('admin');
        $this->client->request('GET', $url);

        $this->assertResponseIsSuccessful();
    }

    public function provideUrls()
    {
        return [
            ['/'],
            ['/admin/'],
            ['/admin/login'],
        ];
    }
}