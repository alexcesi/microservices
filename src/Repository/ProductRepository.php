<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }

    public function findById(int $id): ?Product
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function findByName(string $name): array
    {
        $product = $this->findBy(['name' => $name]);
        return $product;
    }

    public function save(Product $product): void
    {
        $this->_em->persist($product);
        $this->_em->flush();
    }

    public function delete(Product $product): void
    {
        $this->_em->remove($product);
        $this->_em->flush();
    }
}