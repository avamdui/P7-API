<?php

namespace App\Controller;

use App\Repository\PhoneRepository;
use JMS\Serializer\SerializationContext;

use JMS\Serializer\Annotation\Groups;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PhoneController extends AbstractController
{
    /**
     * @route("/api/phones", name="api_phones", methods={"GET"})
     * @Groups({"phone:readall"})
     */
    public function list(PhoneRepository $phoneRepository, SerializerInterface $serializer): JsonResponse
    {
        $phones = $phoneRepository->findAll();
        $data = $serializer->serialize($phones, 'json', ['groups' => 'phone:readall']);
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }


    public function show()
    {
    }
}
