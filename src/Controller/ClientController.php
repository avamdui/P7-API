<?php

namespace App\Controller;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use OpenApi\Annotations as OA;

class ClientController extends AbstractController
{
    private $repo;
    private $cache;
    private $serializer;
    public function __construct(ClientRepository $ClientRepository, CacheInterface $cache, SerializerInterface $serializer)
    {
        $this->repo = $ClientRepository;
        $this->cache = $cache;
        $this->serializer = $serializer;
    }
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
     *        @OA\Items(ref=@Model(type=Client::class, groups={"list"}))
     *     )
     * )
    * @OA\Response(response=404, description="Not found" ),
    * @OA\Response(response=400, description="Bad Request"))
    * @OA\Tag(name="Client")
    * @Security(name="Bearer")
     */
    public function listClient(Request $request)
    {
        $CurrentPage = $request->get('page', 1);
        $PageItemsLimit =  $request->get('item', 5);

        $query = $this->cache->get('Clients_list', function (ItemInterface $item) {
            $item->expiresAfter(10);
            return  $this->repo->findAll();
        });
        
        $adapter = new ArrayAdapter($query);

        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($PageItemsLimit);
        $pagerfanta->setCurrentPage($CurrentPage);
        $clients = $pagerfanta->getCurrentPageResults();
      
        $fullProductsCount = $this->cache->get('count', function (ItemInterface $item) use ($query) {
            $item->expiresAfter(10);
            $list = count($query);
            return  $list;
        });

        $content = [
            'meta' => [
                'Total customers' => $fullProductsCount,
                'Customers per page (item)' => $PageItemsLimit,
                'Current page (page)' => $CurrentPage,
                'Last Page' => $pagerfanta->getNbPages()
             ],
            'data' => $clients
        ];

        $data =  $this->serializer->serialize($content, 'json', SerializationContext::create()->setGroups(array('list')));
    
        return new JsonResponse($data, '200', [], true);
    }


    /**
     * @OA\Get(path="/api/client/{id<\d+>}")
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
    public function show($id, EntityManagerInterface $entityManager)
    {
        $client = $entityManager->getRepository(Client::class)->find($id);
        if (!$client) {
            $data= [
            'status' => 404,
            'message' => 'ClientID not found'
        ];
            return $this->json($data, 404);
        }

        $id = $client->getId();
        $client = $entityManager->getRepository(Client::class)->findOneBy(['id' => $id]);
        $data = $this->serializer->serialize($client, 'json', SerializationContext::create()->setGroups(array('detail')));
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }
}
