<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

class WebTestCase extends BaseWebTestCase
{
    protected KernelBrowser $client;

    public function setUp()
    {
        $this->client = self::createClient();
    }

    public function logIn(string $username)
    {
        /** @var UserInterface $user */
        $user = self::$container->get('doctrine.orm.default_entity_manager')
            ->getRepository(User::class)
            ->findOneBy(['username' => $username])
        ;
        $firewallName = 'main';

        $token = new UsernamePasswordToken($user, null, $firewallName, $user->getRoles());
        $session = self::$container->get('session');
        $session->set('_security_'.$firewallName, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}