<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    /**
     * @param Request $request
     * @return string
     */
    public function getClientTokenFromHeader(Request $request): string
    {
        return str_replace('Bearer ', '', $request->headers->get('Authorization'));
    }

}
