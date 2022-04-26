<?php

namespace App\Controller;

use OpenApi\Examples\Polymorphism\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Client;

class SecurityController extends AbstractController
{
    public function login(Request $request)
    {
    }
}
