<?php

namespace RestApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use RestApiBundle\Entity\Category;
use RestApiBundle\Entity\Product;

class RestProductController extends Controller{
    
    
    public function getProductAction($id){
        $Product=$this->getDoctrine()->getRepository(Product::class)->find($id);
        $product;
        if($Product){
            $catCollection=$Product->getCategories();
            $category=array();
            foreach($catCollection as $cat){
                     $category[]=array('id'=>$cat->getId(),'name'=>$cat->getTitle());
            }
            $product=array("id"=>$Product->getId(),
                            "name"=>$Product->getName(),
                            "mainCategory"=>1,
                            "categories"=>$category
                        );
            return new JsonResponse(array("status"=>"success",'product'=>$product));
        }else{
            return new JsonResponse(array("status"=>"no prodcut found"));
        }
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
        if(count($productCategory)>0){
            foreach($productCategory as $pc){
                $category=$this->getDoctrine()->getRepository(Category::class)->find($pc);
                $product->addCategories($category);
            }
        }
        $product->setName($productName);
        $em->persist($product);
        $em->flush();
        return new JsonResponse(array("status"=>"Success"));
        
    }
    
    public function editProductAction(){
        $em=$this->getDoctrine()->getManager();
        $Product=$this->getDoctrine()->getRepository(Product::class)->find($id);
        $productName=$request->request->get('name');
        $productCategory=$request->request->get('category');
        if(count($productCategory)>0){
            foreach($productCategory as $pc){
                $category=$this->getDoctrine()->getRepository(Category::class)->find($pc);
                $Product->addCategories($category);
            }
        }
        $Product->setName($productName);
        $em->persist($Product);
        $em->flush();
        return new JsonResponse(array("status"=>"Success"));
    }
    
    public function deleteProductAction($id){
        $em=$this->getDoctrine()->getManager();
        $prodcut=$this->getDoctrine()->getRepository(Product::class)->find($id);
        $em->remove($prodcut);
        $em->flush();
        return new JsonResponse(array("status"=>"deleted successfuly"));
    }
}
