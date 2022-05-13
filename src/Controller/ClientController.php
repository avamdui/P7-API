<?php

namespace App\Controller;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
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
     * @OA\Get(path="/api/clients")
     * @Route("/api/clients", name="api_clients", methods={"GET"})
    * @OA\Response(
     *     response=200,
     *     description="Returns the list of clients",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Client::class, groups={"Full"}))
     *     )
     * )
    * @OA\Response(response=404, description="Not found" ),
    * @OA\Response(response=400, description="Bad Request"))
    * @OA\Tag(name="Client")
    * @Security(name="Bearer")
     */
    public function listClient(ClientRepository $clientRepository, Request $request, PaginatorInterface $paginator, CacheInterface $cache)
    {
        $clients = $clientRepository->findAll();
        $clients = $paginator->paginate($clients, $request->get('page', 1), 5);
        $data = $this->serializer->serialize($clients, 'json', ['groups' => 'Full']);

        $result = $cache->get('clients', function (ItemInterface $item) use ($data, $clients) {
            $item->expiresAfter(3600);
            return new JsonResponse($data, JsonResponse::HTTP_OK, ["test"], true);
        });
        return $result;
    }


    /**
     * @OA\Get(path="/api/client/{id}")
     * @Route("/api/client/{id}", name="api_client_id", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns specific client according to his id",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Client::class, groups={"detail"}))
     *     )
     * )
    * @OA\Response(response=404, description="Not found" ),
    * @OA\Response(response=400, description="Bad Request"))
    * @OA\Tag(name="Client")
        * @Security(name="Bearer")
     */
    public function show(Client $client, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $id = $client->getId();
        $client = $entityManager->getRepository(Client::class)->findOneBy(['id' => $id]);
        $data = $serializer->serialize($client, 'json', ['groups' => 'detail']);
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }
}
