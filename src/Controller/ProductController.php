<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * Get one product
     * @Route("/product/{id}", name="product_detail")
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function detail($id, EntityManagerInterface $entityManager): Response
    {
        $productRepository = $entityManager->getRepository(Product::class );
        /**
         * @var $product Product
         */
        $product = $productRepository->find($id);
        $name = $product->getName();
        $brand = $product->getBrand();
        $price = $product->getPrice();


        return new Response($name . $brand . $price);
    }

    /**
     * Insert one product
     * @Route("/product/insert", name="product_insert")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function insert(EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $product->setName("Iphone 12");
        $product->setBrand("Apple");
        $product->setPrice(909);
        return new Response("test");
    }

    /**
     * Loop over a collection of objects
     * @Route("/product/insert", name="product_insert")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function loop(EntityManagerInterface $entityManager): Response
    {
        $productRepository = $entityManager->getRepository(Product::class);
        $products = $productRepository->findBy(["name" => "Apple"]);

        /**
         * @var $product Product
         */
        foreach ($products as $product) {
            var_dump($product->getName().$product->getBrand().$product->getPrice());
        }
        return new Response("test");
    }
}
