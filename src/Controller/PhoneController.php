<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Knp\Component\Pager\PaginatorInterface;
use JMS\Serializer\Annotation\Groups;

class PhoneController extends AbstractController
{
    /**
     * @OA\Get(path="/api/phones", @OA\Response(response="200", description="All smartphones"))
     * @Route("/api/phones", name="api_phones", methods={"GET"})
     * @Groups({"phone:readall"})
     */
    public function list(PhoneRepository $phoneRepository, SerializerInterface $serializer, Request $request, PaginatorInterface $paginator, CacheInterface $cache): Response
    {
        $phones = $phoneRepository->findAll();
        $phonesPaginate = $paginator->paginate($phones, $request->get('page', 1), 5);
        $data = $serializer->serialize($phonesPaginate, 'json', ['groups' => 'phone:readall']);
        $result = $cache->get('resultat', function (ItemInterface $item) use ($data, $phones) {
            $item->expiresAfter(3600);
            return new Response($data, 200, array('Content-Type' => 'application/json'), $phones);
        });
        return $result;
    }

    /**
     * @OA\Get(path="/api/phone/{id}", @OA\Response(response="200", description="Get detail about a specific smartphone"))
     * @Route("/api/phone/{id}", name="api_phone", methods={"GET"})
     * @Groups({"phone:showone"})
     */
    public function show(Phone $phone, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $id = $phone->getId();
        $phone = $entityManager->getRepository(Phone::class)->findOneBy(['id' => $id]);
        $data = $serializer->serialize($phone, 'json', ['groups' => 'phone:showone']);
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }
}
