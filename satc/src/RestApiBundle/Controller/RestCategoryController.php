<?php

namespace RestApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use RestApiBundle\Entity\Category;

class RestCategoryController extends Controller
{
    public function addCategoryAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $cat_title=$request->request->get('name');
        $cat_name='';
        if(!$cat_title){
            $cat_name=$this->generateCateGoryName($cat_title);
        }

        $category=new Category();
        $category->setName($cat_name);
        $category->setTitle($cat_title);
        $em->persist($category);
        $em->flush();
        return new JsonResponse(array("status"=>"Success"));

    }

    public function getCategoryAction($id){
        //$em=$this->getDoctrine()->getManager();
        $category=$this->getDoctrine()->getRepository(Category::class)->find($id);
        if($category){
            return new JsonResponse(array("status"=>"success",'category'=>array('id'=>$category->getId(),'name'=>$category->getTitle())));
        }else{
            return new JsonResponse(array("status"=>"no categary found"));
        }
    }

    public function getAllCategoryAction(){
        $em=$this->getDoctrine()->getManager();

        $response=array();
        $qb=$em->createQueryBuilder();
        $cat_result=$qb->select('u')
                    ->from('RestApiBundle:Category', 'u')
                    ->getQuery()
                    ->getResult();
        //print_r($cat_result);die();
        $categories=array();
        if($cat_result){
            foreach($cat_result as $cat){
                $categories[]=array("id"=>$cat->getId(),"name"=>$cat->getTitle());
            }
            $response['status']="success";
            $response['cateogries']=$categories;
        }else{
            $response["status"]="no category found";
        }
        return new JsonResponse($response);
    }

    public function editCategoryAction($id,Request $request){
        $em=$this->getDoctrine()->getManager();
        $category=$this->getDoctrine()->getRepository(Category::class)->find($id);
        $cat_title=$request->request->get('name');
        $category->setTitle($cat_title);
        $em->persist($category);
        $em->flush();
        return new JsonResponse(array("status"=>"Success"));

    }

    public function deleteCategoryAction($id){
          $em=$this->getDoctrine()->getManager();
          $category=$this->getDoctrine()->getRepository(Category::class)->find($id);
          $em->remove($category);
          $em->flush();
          return new JsonResponse(array("status"=>"deleted successfuly"));
    }

    private function generateCateGoryName($title){
        $title= strtolower($title);
        $title= str_replace(' ','-', $title);
        return $title;

    }
}
