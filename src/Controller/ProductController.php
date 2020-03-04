<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="products")
     */
    public function index()
    {
        $pdo = $this->getDoctrine()->getManager();

        $products = $pdo->getRepository(Product::class)->findAll();
        /*
        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
        */
        return $this->render('product/index.html.twig',['products' => $products]);
    }
}
