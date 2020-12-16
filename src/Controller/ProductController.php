<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
        $productRepository = $entityManager->getRepository(Product::class);
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

        $entityManager->persist($product);
        $entityManager->flush();
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

    /**
     * Update an entity
     * @Route("/update", name="product_update")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function update(EntityManagerInterface $entityManager): Response
    {
        $productRepository = $entityManager->getRepository(Product::class);
        $product = $productRepository->find(1);

        $product->setPrice(809);
        $entityManager->flush();

        return new Response("test");
    }

    /**
     * Delete an entity
     * @Route("/delete", name="product_delete")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(EntityManagerInterface $entityManager): Response
    {
        $productRepository = $entityManager->getRepository(Product::class);

        $product = $productRepository->find(1);

        $entityManager->remove($product);
        $entityManager->flush();

        return new Response("test");
    }

    /**
     * Custom find
     * @Route("/test", name="product_delete")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function test(EntityManagerInterface $entityManager): Response
    {
        $productRepository = $entityManager->getRepository(Product::class);
        /**
         * @var $product Product
         */
        $products = $productRepository->findAndOrderBy(1);

        foreach ($products as $product) {
            var_dump($product->getName());
        }

        return new Response("test");
    }

    /**
     * Custom find
     * @Route("/all", name="product_delete")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function all(EntityManagerInterface $entityManager): Response
    {
        $productRepository = $entityManager->getRepository(Product::class);
        /**
         * @var $product Product
         */
        $products = $productRepository->findAll();

        foreach ($products as $product) {
            var_dump($product->getName());
        }

        return new Response("test");
    }

    /**
     * Create new product from form
     * @Route("/new", name="product_new")
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function newProduct(Request $request, ValidatorInterface $validator) : Response {

        //all the constraint here https://symfony.com/doc/current/validation.html#number-constraints
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);

        $errors = [];
        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            if ($form->isSubmitted() && $form->isValid()) {
                $errors = $validator->validate($product);

                if($errors->count() ===  0) {
                    $product = $form->getData();

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($product);
                    $entityManager->flush();

                    return $this->render('product/new.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }
            }
            else {
                foreach ($form->getErrors() as $error) {
                    $errors[] = $error;
                };
            }
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
            'error' => $errors
        ]);
    }
}
