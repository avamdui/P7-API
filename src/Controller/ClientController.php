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
    /**
     * @OA\Post(path="/api/login_check")
    * @OA\Response(
     *     response=200,
     *     description="Successful authentication'",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Client::class, groups={"login"}))
     *     )
     * )
     * @Route("/api/login_check", name="login_check", methods={"POST"})
     * @OA\Tag(name="Client")
     */
    public function login()
    {
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
    public function listClient(ClientRepository $clientRepository, Request $request, PaginatorInterface $paginator, SerializerInterface $serializer, CacheInterface $cache)
    {
        $clients = $clientRepository->findAll();
        $CurrentPage = $request->get('page', 1);
        $PageItemsLimit =  $request->get('item', 5);
        $fullProductsCount = count($clients);
        // $fullProductsCount = '25';
        $lastPage = ceil($fullProductsCount / $PageItemsLimit);
        $clients = $paginator->paginate($clients, $request->get('page', 1), $PageItemsLimit);

        $content = [
            'meta' => [
                'Totalclients' => $fullProductsCount,
                'maxclientsPerPage(item)' => $PageItemsLimit,
                'currentPage(page)' => $CurrentPage,
                'lastPage' => $lastPage
                
            ],
            'data' => $clients
        ];
        $data = $serializer->serialize($content, 'json', ['groups' => 'Full']);
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);


        // $result = $cache->get('clients', function (ItemInterface $item) use ($data, $clients) {
        //     $item->expiresAfter(3600);
        //     return new JsonResponse($data, JsonResponse::HTTP_OK, ["test"], true);
        // });
        // return $result;
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
    public function show($id, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $client = $entityManager->getRepository(Client::class)->find($id);
        if (!$client) {
            $data= [
            'status' => 404,
            'message' => 'Clients not found'
        ];
            return $this->json($data, 404);
        }

        $id = $client->getId();
        $client = $entityManager->getRepository(Client::class)->findOneBy(['id' => $id]);
        $data = $serializer->serialize($client, 'json', ['groups' => 'detail']);
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }
}
