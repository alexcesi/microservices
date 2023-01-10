<?php
namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
    {
        /**
        * @Route("/api/cart/addProduct", methods={"POST"})
        */
        public function addProduct(Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository, UserRepository $userRepository)
        {
        $data = json_decode($request->getContent(), true);
        $userId = $data['userId'] ?? null;
        
        $productId = $data['productId'] ?? null;

        if (!is_int($userId) || !is_int($productId)) {
        return new JsonResponse(['error' => 'User ID and product ID must be integers'], 400);
        }

        $user = $userRepository->find($userId);
        $product = $productRepository->find($productId);


        if (!$user instanceof User) {
        return new JsonResponse(['error' => 'User not found'], 404);
        }

        if (!$product instanceof Product) {
        return new JsonResponse(['error' => 'Product not found'], 404);
        }

        $cart = $user->getCart();

        if (!$cart instanceof Cart) {
            $cart = new Cart();
            $cart->setUser($user);
            $entityManager->persist($cart);
        }

        $cart->addProduct($product);
        $entityManager->flush();

        return new JsonResponse(['success' => 'Product added to cart']);
    }
}