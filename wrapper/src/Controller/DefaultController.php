<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DefaultController extends Controller
{

    public function login(AuthenticationUtils $authUtils)
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    public function logout(Request $request, TokenStorageInterface $storage)
    {
        $storage->setToken(null);
        $request->getSession()->invalidate();

        return $this->redirect($this->generateUrl('login'));
    }


    public function catchall(Request $request, TokenStorageInterface $storage)
    {
        // Catch logout action, to redirect to own logout function
        if ($request->query->get('logout') !== null) {
            return $this->redirect('logout');
        }

        $client = new Client();

        $baseURL = getenv('ORIGINAL_URL');
        $cookieJar = unserialize($request->getSession()->get('appcookie'));
        $slug = $request->getPathInfo() == '/' ? '' : $request->getPathInfo();
        $url = $baseURL . $slug;
        $urlparts = parse_url($url);

        $headerparams = $request->headers->all();
        $headerparams['host'] = $urlparts['host'];
        $formparams = $request->request->all();
        $query = $request->query->all();

        $result = null;

        try {
            $result = $client->request($request->getMethod(), $url, [
                'headers' => $headerparams,
                'form_params' => $formparams,
                'query' => $query,
                'cookies' => $cookieJar
            ]);
        } catch(ClientException $e) {
            if ($e->hasResponse()) {
                $result = $e->getResponse();
            }
        }

        return new Response($result->getBody(), $result->getStatusCode(), $result->getHeaders());
    }
}