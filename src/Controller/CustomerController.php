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
use App\Entity\Customer;
use App\Repository\CustomerRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CustomerController extends AbstractController
{
    private $repo;
    private CacheInterface $cache;

    private SerializerInterface $serializer;
    public function __construct(CustomerRepository $CustomerRepository, CacheInterface $cache, SerializerInterface $serializer)
    {
        $this->repo = $CustomerRepository;
        $this->cache = $cache;
        $this->serializer = $serializer;
    }

    /**
    * @Groups({"list"})
    * @OA\Get(path="/api/customers")
    * @Route("/api/customers", name="api_customers", methods={"GET"})
    * @OA\Response(
     *     response=200,
     *     description="Returns the Customer's client'",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Customer::class, groups={"list"}))
     *     )
     * )
    * @OA\Response(response=404, description="ressource not found" ),
    * @OA\Response(response=400, description="Bad Request"))
    * @OA\Tag(name="Customer")
    * @Security(name="Bearer")
    */
    public function ListCustumerClient(Request $request)
    {
        $client = $this->getUser();
   
        $CurrentPage = $request->get('page', 1);
        $PageItemsLimit =  $request->get('item', 5);

        $query = $this->cache->get('Customers_list', function (ItemInterface $item) {
            $item->expiresAfter(10);
            return  $this->repo->findAll();
        });
      
        $adapter = new ArrayAdapter($query);

        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($PageItemsLimit);
        $pagerfanta->setCurrentPage($CurrentPage);
        $customers = [];
        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $customers[] = $result;
        }
      
        $fullProductsCount = $this->cache->get('count', function (ItemInterface $item) use ($query) {
            $item->expiresAfter(10);
            $list = count($query);
            return  $list;
        });
        
    
      
        // $lastPage = ceil($fullProductsCount / $PageItemsLimit);
        $content = [
            'meta' => [
                'Total customers' => $fullProductsCount,
                'Customers per page (item)' => $PageItemsLimit,
                'Current page (page)' => $CurrentPage,
                'Last Page' => $pagerfanta->getNbPages()
             ],
            'data' => $customers
        ];

        $data =  $this->serializer->serialize($content, 'json', SerializationContext::create()->setGroups(array('list')));
    
        return new JsonResponse($data, '200', [], true);
    }

    
    /**
    * @Route("/api/customer/{id<\d+>}", name="api_customer", methods={"GET"})
    * @OA\Get(path="/api/customer/{id}")
    * @OA\Response(
     *     response=200,
     *     description="Returns specific customer according to his id",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Customer::class, groups={"detail"}))
     *     )
     * )
    * @OA\Response(response=404, description="customer not found" ),
    * @OA\Response(response=400, description="Bad Request"))
    * @OA\Tag(name="Customer")
    * @Security(name="Bearer")
     */
    public function show($id, EntityManagerInterface $entityManager)
    {
        $customer = $entityManager->getRepository(Customer::class)->find($id);
        if (!$customer) {
            $data= [
            'status' => 404,
            'message' => 'CustomerID not found'
        ];
            return $this->json($data, 404);
        }
        
        $id = $customer->getId();
        $customer = $entityManager->getRepository(Customer::class)->findOneBy(['id' => $id]);
        $data = $this->serializer->serialize($customer, 'json', SerializationContext::create()->setGroups(array('detail')));
        return new JsonResponse($data, 200, [], true);
    }

    /**
    * @Route("/api/customer/create", name="api_customer_create", methods={"POST"})
    * @OA\Get(path="/api/customer/create")
        * @OA\Response(
     *     response=200,
     *     description="Create an customer associated to a client",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Customer::class, groups={"create"}))
     *     )
     * )
    * @OA\Response(response=201,description="Customer created")
    * @OA\Response(response=404, description="Not found" ),
    * @OA\Response(response=400, description="Bad Request"))
    * @OA\Tag(name="Customer")

    * @Security(name="Bearer")

     */
    public function create(Request $request, EntityManagerInterface $em, ValidatorInterface $validator) : JsonResponse
    {
        try {
            $customer = $request->getContent();
            $customer = $this->serializer->deserialize($customer, Customer::class, 'json');
            $customer->setCreatedAt(new DateTime())
                     ->setClient($this->getUser());
            $error = $validator->validate($customer);

            if (count($error)>0) {
                return $this->json($error, 400);
            }
            $em->persist($customer);
            $em->flush();
            $data = $this->serializer->serialize($customer, 'json', SerializationContext::create()->setGroups(array('detail')));
            return new JsonResponse($data, Response::HTTP_CREATED, [], true);
        } catch (NotEncodableValueException $e) {
            return $this->json(array('status'=>400, 'message'=>$e->getMessage()), 400);
        }
    }

    /**
    * @OA\Delete(path="/api/customer/{id}", @OA\Response(response="204", description="delete a client", @OA\JsonContent(type="string")))
    * @Route("/api/customer/{id}", name="api_delete_customer_id",requirements={"id":"\d+"}, methods = {"DELETE"})
    * @OA\Tag(name="Customer")
    * @Security(name="Bearer")
    */
    public function DeleteCustumerClient($id, EntityManagerInterface $entityManager)
    {
        $customer = $entityManager->getRepository(Customer::class)->find($id);
        if (!$customer) {
            $data= [
            'status' => 404,
            'message' => 'Customer not found'
        ];
            return $this->json($data, 404);
        }
        $entityManager->remove($customer);
        $entityManager->flush();
        return new JsonResponse('', '204', [], true);
    }
}
