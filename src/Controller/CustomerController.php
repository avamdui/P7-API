<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class CustomerController extends AbstractController
{

    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    /**
     * @OA\Get(path="/api/customers", @OA\Response(response="200", description="All customers"))
     * @Route("/api/customers", name="api_customers", methods={"GET"})
     */
    public function list(CustomerRepository $customerRepository, SerializerInterface $serializer): JsonResponse
    {
        $customers = $customerRepository->findAll();
        $data = $serializer->serialize($customers, 'json', ['groups' => 'customers:readall']);
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
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
}
