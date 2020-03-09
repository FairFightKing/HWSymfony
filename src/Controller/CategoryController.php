<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categories", name="categories")
     */
    public function index(Request $request)
    {
        $pdo = $this->getDoctrine()->getManager();


        $categories = $pdo->getRepository(Category::class)->findAll();
        /*
        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
        */

        $category = new Category();
        $form = $this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $pdo->persist($category);
            $pdo->flush();
        }

        return $this->render('category/index.html.twig',[
            'categories' => $categories,
            'add_form' => $form->createView()
        ]);
    }
    /**
     * @Route("/category/{id}", name="a_category")
     */
    public function category(Category $category, Request $request){

        $form = $this->createForm(CategoryType::class,$category);
           $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->persist($category);
            $pdo->flush();
        }

        return $this->render('category/category.html.twig', [
            'category' => $category,
            'form_edit' => $form->createView()
            ]);
    }
    /**
     * @Route("/category/delete/{id}", name="delete_category")
     * */
    public function delete(Category $category=null){

        if ($category != null){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->remove($category);
            $pdo->flush();

            $this->addFlash('success', 'delete successfull');
        } else{
            $this->addFlash('error','Not found');
        }
        return $this->redirectToRoute('categories');
    }

}
