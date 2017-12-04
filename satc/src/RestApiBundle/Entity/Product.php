<?php

namespace RestApiBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * Product
 */
class Product
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
//    private $category;
//
//    /**
//     * Get Category
//     *
//     * @return RestApiBundle\Entity\Category
//     */
//    public function getCategory()
//    {
//        return $this->category;
//    }
//
//    /**
//     * Set name
//     *
//     * @param RestApiBundle\Entity\Category
//     *
//     * @return Product
//     */
//    public function setCategory(RestApiBundle\Entity\Category $category)
//    {
//        $this->category = $category;
//        return $this;
//    }
    
    protected $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * @return Collection|Categories[]
     */
    public function getCategories()
    {
        return $this->categories;
        
    }
    
    public function addCategories(RestApiBundle\Entity\Category $category ){
        $this->categories[]=categories;
        return $this;
    }
}

