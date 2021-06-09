<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminLoginControllerTest extends WebTestCase
{
    public function testLoginRedirect()
    {
        $client = self::createClient();
        $client->request('GET', '/admin/login');
        $crawler = $client->getCrawler();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('html h1', 'Calculateur de voyage');
        $this->assertSelectorTextContains('html h2', 'Page de connexion des admins');

        $link = $crawler
            ->filter('html a')
            ->eq(0)
            ->link()
        ;

        $client->click($link);
        $this->assertSelectorTextContains('html', 'Log activé');
    }

    /**
     * @group legacy
     */
    public function testLogin()
    {
        $client = self::createClient();
        $client->request('GET', '/admin/login');
        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Me connecter')
            ->form();

        $formName = $form->getName();
        $form[$formName.'[username]'] = 'admin';
        $form[$formName.'[password]'] = 'root';
        $form[$formName.'[remember_me]'] = true;

        $client->submit($form);

//        $client->submitForm(
//            'Me connecter',
//            [
//                'login[username]' => 'admin',
//                'login[password]' => 'root',
//                'login[remember_me]' => true,
//            ]
//        );

        $this->assertResponseRedirects('/admin/');
        $client->followRedirect();

        $this->assertSelectorTextContains('html', 'Bonjour, comment allez-vous ?');
    }
}