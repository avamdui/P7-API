<?php

namespace App\Controller;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
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
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class PhoneController extends AbstractController
{
    private $repo;
    private $cache;
    private $serializer;
    public function __construct(PhoneRepository $PhoneRepository, CacheInterface $cache, SerializerInterface $serializer)
    {
        $this->repo = $PhoneRepository;
        $this->cache = $cache;
        $this->serializer = $serializer;
    }
    /**
    * @OA\Get(path="/api/phones")
    * @Route("/api/phones", name="api_phones", methods={"GET"})
    * @OA\Response(
     *     response=200,
     *     description="Returns the list of all smartphones",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Phone::class, groups={"list"}))
     *     )
     * )
    * @OA\Response(response=404, description="Not found" ),
    * @OA\Response(response=400, description="Bad Request"))
    * @OA\Tag(name="Phones")
    * @Security(name="Bearer")
     */
    public function list(Request $request): Response
    {
        $CurrentPage = $request->get('page', 1);
        $PageItemsLimit =  $request->get('item', 5);

        $query = $this->cache->get('Phones_list', function (ItemInterface $item) {
            $item->expiresAfter(10);
            return  $this->repo->findAll();
        });
        
        $adapter = new ArrayAdapter($query);

        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($PageItemsLimit);
        $pagerfanta->setCurrentPage($CurrentPage);
        $phones = $pagerfanta->getCurrentPageResults();
      
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
            'data' => $phones
        ];

        $data =  $this->serializer->serialize($content, 'json', SerializationContext::create()->setGroups(array('list')));
    
        return new JsonResponse($data, '200', [], true);
    }


    /**
    * @OA\Get(path="/api/phone/{id<\d+>}")
    * @Route("/api/phone/{id}", name="api_phone", methods={"GET"})
    * @OA\Response(
     *     response=200,
     *     description="Get detail about a specific smartphone",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Phone::class, groups={"detail"}))
     *     )
     * )
    * @OA\Response(response=404, description="Not found" ),
    * @OA\Response(response=400, description="Bad Request"))
    * @OA\Tag(name="Phones")
    * @Security(name="Bearer")
     */
    public function show(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $phone = $entityManager->getRepository(Phone::class)->find($id);
        if (!$phone) {
            $data= [
            'status' => 404,
            'message' => 'PhoneID not found'
        ];
            return $this->json($data, 404);
        }
       
        $id = $phone->getId();
        $phone = $entityManager->getRepository(Phone::class)->findOneBy(['id' => $id]);
        $data =  $this->serializer->serialize($phone, 'json', SerializationContext::create()->setGroups(array('detail')));
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }
}
