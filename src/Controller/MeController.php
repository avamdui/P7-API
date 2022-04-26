<?php

namespace App\Controller;

use OpenApi\Examples\Polymorphism\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Client;

use Symfony\Component\Security\Core\Security;

class MeController extends AbstractController
{
    public function __construct(private Security $security)
    {
    }
    public function __invoque()
    {
        $client = $this->security->getUser();
        return $client;
    }
}
