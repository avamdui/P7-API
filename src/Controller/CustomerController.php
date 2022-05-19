<?php

namespace App\Controller;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use App\Entity\Client;
use App\Entity\Customer;
use App\Repository\CustomerRepository;
use DateTime;
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
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CustomerController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    /**
    * @OA\Get(path="/api/customers")
    * @Route("/api/customers", name="api_customers", methods={"GET"})
    * @OA\Response(
     *     response=200,
     *     description="Returns the Customer's client'",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Customer::class, groups={"Full"}))
     *     )
     * )
    * @OA\Response(response=404, description="ressource not found" ),
    * @OA\Response(response=400, description="Bad Request"))
    * @OA\Tag(name="Customer")
    * @Security(name="Bearer")
    */
    public function ListCustumerClient(CustomerRepository $customerRepository, Request $request, PaginatorInterface $paginator, CacheInterface $cache)
    {
        $client = $this->getUser();
        $id = $client->getId();
        $customers = $customerRepository->findBy(array('client'=>$id));
        $customersPaginate = $paginator->paginate($customers, $request->get('page', 1), 5);
        $page =  $request->query->getInt('page', 1);
          
        $data = $this->serializer->serialize($customersPaginate, 'json', ['groups' => 'Full']);

        // $result = $cache->get('customers', function (ItemInterface $item) use ($data, $customers) {
        //     $item->expiresAfter(3600);
        //     return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
        // });
        // return $result;

        return new JsonResponse($data, JsonResponse::HTTP_OK, ['page' => $page ], true);
    }

    
    /**
    * @Route("/api/customer/{id}", name="api_customer", methods={"GET"})
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
    public function show($id, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $customer = $entityManager->getRepository(Customer::class)->find($id);
        if (!$customer) {
            $data= [
            'status' => 404,
            'message' => 'Customer not found'
        ];
            return $this->json($data, 404);
        }

        $id = $customer->getId();
        $customer = $entityManager->getRepository(Customer::class)->findOneBy(['id' => $id]);
        $data = $serializer->serialize($customer, 'json', ['groups' => 'detail']);
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
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
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer, ValidatorInterface $validator) : JsonResponse
    {
        try {
            $customer = $request->getContent();
            $customer = $serializer->deserialize($customer, Customer::class, 'json');
            $customer->setCreatedAt(new DateTime())
                     ->setClient($this->getUser());
            $error = $validator->validate($customer);

            if (count($error)>0) {
                return $this->json($error, 400);
            }
            $em->persist($customer);
            $em->flush();
            $data = $serializer->serialize($customer, 'json', ['groups' => 'detail']);
            return new JsonResponse($data, Response::HTTP_CREATED, [], true);
        } catch (NotEncodableValueException $e) {
            return $this->json(array('status'=>400, 'message'=>$e->getMessage()), 400);
        }

        // return $this->json(['result' => 'Customer created']);
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
