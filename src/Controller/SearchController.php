<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\SearchRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class SearchController extends AbstractController
{
    /**
     * @Route("/api/search", methods={"POST"})
     */
    public function search(Request $request, SerializerInterface $serializer, SearchRepository $searchRepository)
    {
        // Récupérez le nom du produit à partir du corps de la requête
        $data = json_decode($request->getContent(), true);
        $name = $data['name'] ?? null;
        
        // Recherchez le produit par son nom
        $product = $searchRepository->findOneByName($name);
        
        if (!$product instanceof Product) {
            return new JsonResponse(['error' => 'Product not found'], 404);
        }

        return new JsonResponse([
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'description' => $product->getDescription(),
            'stock' => $product->getStock(),
        ], 200);
    }
}
