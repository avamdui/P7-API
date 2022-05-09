<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
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

class CustomerController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    /**
     * @OA\Get(path="/api/customers", @OA\Response(response="200", description="All customer's client"))
     * @Route("/api/customers", name="api_customers", methods={"GET"})
     */
    public function ListCustumerClient(CustomerRepository $customerRepository, Request $request, PaginatorInterface $paginator, CacheInterface $cache)
    {
        $client = $this->getUser();
        $id = $client->getId();
        $customers = $customerRepository->findBy(array('client'=>$id));
        $customers = $paginator->paginate($customers, $request->get('page', 1), 5);
        $data = $this->serializer->serialize($customers, 'json', ['groups' => 'customers:readall']);

        $result = $cache->get('resultat', function (ItemInterface $item) use ($data, $customers) {
            $item->expiresAfter(3600);
            return new Response($data, 200, array('Content-Type' => 'application/json'), $customers);
        });
        return $result;
    }

    
    /**
     * @OA\Get(path="/api/customer/{id}", @OA\Response(response="200", description="Customer détail"))
     * @Route("/api/customer/{id}", name="api_customer_id", methods={"GET"})
     */
    public function show(Customer $customer, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $id = $customer->getId();
        $customer = $entityManager->getRepository(Customer::class)->findOneBy(['id' => $id]);
        $data = $serializer->serialize($customer, 'json', ['groups' => 'customer:detail']);
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }

    public function showCustumerClient(Customer $customer, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        //TODO
    }

    public function addCustumerClient(Customer $customer, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        //TODO
    }
    public function DeleteCustumerClient(Customer $customer, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        //TODO
    }
}
