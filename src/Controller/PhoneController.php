<?php

namespace App\Controller;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
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
    * @OA\Get(path="/api/phones")
    * @Route("/api/phones", name="api_phones", methods={"GET"})
    * @OA\Response(response=200,description="Returns the list of all smartphones")
    * @OA\Response(response=404, description="Not found" ),
    * @OA\Response(response=400, description="Bad Request"))
    * @OA\Tag(name="Phones")
    * @Security(name="Bearer")
     */
    public function list(PhoneRepository $phoneRepository, SerializerInterface $serializer, Request $request, PaginatorInterface $paginator, CacheInterface $cache): Response
    {
        $phones = $phoneRepository->findAll();
        $phones = $paginator->paginate($phones, $request->get('page', 1), 5);
        
        $data = $serializer->serialize($phones, 'json', ['groups' => 'phone:readall']);

        $result = $cache->get('phones', function (ItemInterface $item) use ($data, $phones) {
            $item->expiresAfter(3600);
            return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
        });
        return $result;
    }

    /**
    * @OA\Get(path="/api/phone/{id}")
    * @Route("/api/phone/{id}", name="api_phone", methods={"GET"})
    * @OA\Response(response=200,description="Get detail about a specific smartphone")
    * @OA\Response(response=404, description="Not found" ),
    * @OA\Response(response=400, description="Bad Request"))
    * @OA\Tag(name="Phones")
    * @Security(name="Bearer")
     */
    public function show(Phone $phone, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        $id = $phone->getId();
        $phone = $entityManager->getRepository(Phone::class)->findOneBy(['id' => $id]);
        $data = $serializer->serialize($phone, 'json', ['groups' => 'phone:showone']);
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }
}
