<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Annotations as OA;
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

    /**
     * @route("/api/phone/{id}", name="api_phone", methods={"GET"})
     * @Groups({"phone:showone"})
     * @param $phone
     * @param EntityManagerInterface $manager
     */
    public function show(Phone $phone, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $id = $phone->getId();
        $phone = $entityManager->getRepository(Phone::class)->findOneBy(['id' => $id]);
        $data = $serializer->serialize($phone, 'json', ['groups' => 'phone:showone']);
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }
}
