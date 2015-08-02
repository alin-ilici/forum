<?php

namespace Forum\CoreBundle\Services;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerAware;

class LogoutSuccessHandler extends ContainerAware implements LogoutSuccessHandlerInterface
{
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function onLogoutSuccess(Request $request)
    {
        // redirect the user to where they were before the login process begun.
        $referer_url = $request->headers->get('referer');

        if ($referer_url === null) {
            $referer_url = "/";
        }

        $response = new RedirectResponse($referer_url);
        return $response;
    }
}