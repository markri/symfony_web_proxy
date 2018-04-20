<?php

namespace App\EventListener;

use App\Entity\PowerAdminUser;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticator;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;


class LoginListener
{

    /**
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        // Login is succesfull, so retrieve password
        $password = $event->getRequest()->request->get('_password');
        $username = $event->getRequest()->request->get('_username');
        $loginUrl = getenv('LOGIN_URL');

        // Pass it as a login request to underlying application to get the session cookie
        $cookieJar = new CookieJar();
        $client = new Client();

        $client->request('POST', $loginUrl, [
            'form_params' => [
                'query_string' => '',
                'username' => $username,
                'password' => $password,
                'userlang' => 'en_EN',
                'authenticate' => 'Go'
            ],
            'cookies' => $cookieJar
        ]);

        // Store it into our own session
        $event->getRequest()->getSession()->set('appcookie', serialize($cookieJar));
    }

}