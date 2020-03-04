<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="products")
     */
    public function index(Request $request)
    {
        $pdo = $this->getDoctrine()->getManager();

        $products = $pdo->getRepository(Product::class)->findAll();
        /*
        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
        */

        $product = new Product();
        $form = $this->createForm(ProductType::class,$product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $pdo->persist($product);
            $pdo->flush();
        }

        return $this->render('products/index.html.twig',[
            'products' => $products,
            'add_form' => $form->createView()
        ]);
    }
}
