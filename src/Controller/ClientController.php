<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use OpenApi\Annotations as OA;
use Knp\Component\Pager\PaginatorInterface;

class ClientController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    /**
     * @OA\Get(path="/api/clients", @OA\Response(response="200", description="All clients"))
     * @Route("/api/clients", name="api_clients", methods={"GET"})
     */
    public function listClient(ClientRepository $clientRepository, Request $request, PaginatorInterface $paginator, CacheInterface $cache)
    {
        $clients = $clientRepository->findAll();
        $clients = $paginator->paginate($clients, $request->get('page', 1), 5);
        $data = $this->serializer->serialize($clients, 'json', ['groups' => 'clients:readall']);

        $result = $cache->get('clients', function (ItemInterface $item) use ($data, $clients) {
            $item->expiresAfter(3600);
            return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
        });
        return $result;
    }


    /**
     * @OA\Get(path="/api/client/{id}", @OA\Response(response="200", description="client détail"))
     * @Route("/api/client/{id}", name="api_client_id", methods={"GET"})
     */
    public function show(Client $client, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $id = $client->getId();
        $client = $entityManager->getRepository(Client::class)->findOneBy(['id' => $id]);
        $data = $serializer->serialize($client, 'json', ['groups' => 'client:detail']);
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }
}
