<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Products;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index()
    {

    	$flashbag = $this->get('session')->getFlashBag();


		$repository = $this->getDoctrine()->getRepository(Products::class);	
		$products = $repository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
            
        ]);
    }
    /**
     * @Route("/create", name="createProduct")
     */
    public function create()
    {
    	$message = null;
		$request = Request::createFromGlobals();
    	
    	if ($_POST) 
    	{
    		$entityManager = $this->getDoctrine()->getManager();
	        $product = new Products();
	        $product->setName($_POST['product']);       
	        $product->setDescription($_POST['description']);
	        $entityManager->persist($product);
	        $entityManager->flush();

	        $message = "Product has been saved";
        }

        return $this->render('product/create.html.twig', [
            'message' => $message,
        ]);
    }


    /**
     * @Route("/product/{id}", name="showProduct")
     */
    public function show($id)  
    {

		$message = null;

    	if ($_POST) 
    	{
			$entityManager = $this->getDoctrine()->getManager();
			$product = $entityManager->getRepository(Products::class)->find($id);

			if (!$product) {
				throw $this->createNotFoundException(
				    'No product found for id '.$id
				);
			}
	        $product->setName($_POST['product']);       
	        $product->setDescription($_POST['description']);
			$entityManager->flush();

			$message = "Product has been updated";

    	}


		$repository = $this->getDoctrine()->getRepository(Products::class);	
    	$product = $repository->find($id);

        return $this->render('product/show.html.twig', [
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'message'		=> $message
        ]);

    }

    /**
     * @Route("/delete/{id}", name="deleteProduct")
     */
    public function delete($id) 
    {
		$entityManager = $this->getDoctrine()->getManager();
		$product = $entityManager->getRepository(Products::class)->find($id);

		$entityManager->remove($product);
		$entityManager->flush();

		$this->addFlash('success', 'Product Deleted!');
		return $this->redirectToRoute('index');


    }


}
