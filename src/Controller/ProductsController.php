<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

class ProductController extends AbstractController
{
    private $entityManager;
    private $productRepository;

    public function __construct(EntityManagerInterface $entityManager, ProductRepository $productRepository)
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/api/products", methods={"GET"})
     */
    public function getAllProducts(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        $data = [];

        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'description' => $product->getDescription(),
                'stock' => $product->getStock(),
            ];
        }

        return new JsonResponse($data);
    }


    /**
     * @Route("/api/product/{id}", methods={"GET"})
     */
    public function getProduct(int $id, ProductRepository $productRepository)
    {
        $product = $productRepository->findById($id);

        if (!$product) {
            return new JsonResponse(null, 404);
        }

        return new JsonResponse([
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'description' => $product->getDescription(),
            'stock' => $product->getStock(),
        ]);
    }

    /**
     * @Route("/api/product", methods={"POST"})
     */
    public function create(Request $request, ProductRepository $productRepository)
    {
        $data = json_decode($request->getContent(), true);

        $product = new Product();
        $product->setName($data['name']);
        $product->setPrice($data['price']);
        $product->setDescription($data['description']);
        $product->setStock($data['stock']);

        $productRepository->save($product);

        return new JsonResponse(['id' => $product->getId()], 201);
    }

    /**
     * @Route("/api/product/{id}", methods={"PUT"})
     */
    public function update(int $id, Request $request, ProductRepository $productRepository)
    {
        $product = $productRepository->find($id);

        if (!$product) {
            return new JsonResponse(null, 404);
        }

        $data = json_decode($request->getContent(), true);
        $product->setName($data['name']);
        $product->setPrice($data['price']);
        $product->setDescription($data['description']);
        $product->setStock($data['stock']);

        $productRepository->save($product);

        return new JsonResponse(['id' => $product->getId()], 200);
    }

    /**
     * @Route("/api/product/{id}", methods={"DELETE"})
     */
    public function delete(int $id, ProductRepository $productRepository)
    {
        $product = $productRepository->find($id);

        if (!$product) {
            return new JsonResponse(null, 404);
        }

        $productRepository->delete($product);

        return new JsonResponse(null, 204);
    }
}