<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
            $file = $form->get('pictureUpload')->getData();
            if ($file){
                $fileName = uniqid('', true) . '.' . $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('upload_dir'),
                        $fileName
                    );

                } catch (FileException $e){
                    $this->addFlash('danger', 'Impossible');
                    return $this->redirectToRoute('home');
                }
                $product->setPicture($fileName);
            }
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
                $this->addFlash('success', 'add successfull');

            }

            return $this->render('products/product.html.twig', [
                'product' => $product,
                'form_edit' => $form->createView()
            ]);
        } else {
            return $this->redirectToRoute('products');
            $this->addFlash('error', 'Not found');

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
