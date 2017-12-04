<?php

namespace RestApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use RestApiBundle\Entity\Category;
use RestApiBundle\Entity\Product;

class RestProductController extends Controller{
    
    
    public function getProductAction($id){
         
    }
    
    public function getAllProductsAction(){
        $em=$this->getDoctrine()->getManager();
        
        $response=array();
        $qb=$em->createQueryBuilder();
        $product_result=$qb->select('p')
                    ->from('RestApiBundle:Product', 'p')
                    ->getQuery()
                    ->getResult();
        //print_r($cat_result);die();
        $products=array();
        if($product_result){
            foreach($product_result as $product){
                $catCollection=$product->getCategories();
                $category=array();
                foreach($catCollection as $cat){
                     $category[]=array('id'=>$cat->getId(),'name'=>$cat->getTitle());
                }
                $products[]=array("id"=>$product->getId(),
                                "name"=>$product->getName(),
                                "mainCategory"=>1,
                                "categories"=>$category
                            );
            }
            $response['status']="success";
            $response['products']=$products;
        }else{
            $response["status"]="no product found found";
        }
        return new JsonResponse($response);
    }
    
    public function getProductByCategoryAction($id){
        $em=$this->getDoctrine()->getManager();
        $response=array();
        $category=$this->getDoctrine()->getRepository(Category::class)->find($id);
        $product_result=$category->getProducts();
        $products=array();
        if($product_result){
            foreach($product_result as $product){
                $catCollection=$product->getCategories();
                $category=array();
                foreach($catCollection as $cat){
                     $category[]=array('id'=>$cat->getId(),'name'=>$cat->getTitle());
                }
                $products[]=array("id"=>$product->getId(),
                                "name"=>$product->getName(),
                                "mainCategory"=>1,
                                "categories"=>$category
                            );
            }
            $response['status']="success";
            $response['products']=$products;
        }else{
            $response["status"]="no product found found";
        }
        return new JsonResponse($response);
    }
    
    public function AddProductAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $product=new Product();
        $productName=$request->request->get('name');
        $productCategory=$request->request->get('category');
        foreach($productCategory as $pc){
            $category=$this->getDoctrine()->getRepository(Category::class)->find($pc);
            $product->addCategories($category);
        }
        $product->setName($productName);
        $em->persist($product);
        $em->flush();
        return new JsonResponse(array("status"=>"Success"));
        
    }
    
    public function editProduct(){
        
    }
    
    public function deleteProduct(){
        
    }
}
