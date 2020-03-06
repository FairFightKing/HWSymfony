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
    /**
     * @Route("/product/edit/{id}", name="a_product")
     * */
    public function product(Product $product, Request $request)
    {
        if ($product != null) {
            $form = $this->createForm(ProductType::class, $product);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $pdo = $this->getDoctrine()->getManager();
                $pdo->persist($product);
                $pdo->flush();
            }

            return $this->render('products/product.html.twig', [
                'product' => $product,
                'form_edit' => $form->createView()
            ]);
        } else {
            return $this->redirectToRoute('products');
        }
    }
    /**
     * @Route("/product/delete/{id}", name="delete_product")
     * */
    public function delete(Product $product=null){

        if ($product != null){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->remove($product);
            $pdo->flush();
            $this->addFlash('success', 'delete successfull');
        } else{
            $this->addFlash('error','Not found');
        }
        return $this->redirectToRoute('products');
    }
}
