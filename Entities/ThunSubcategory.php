<?php
/**
 * Created by JetBrains PhpStorm.
 * User: developer
 * Date: 05.04.13
 * Time: 23:49
 * To change this template use File | Settings | File Templates.
 */

namespace Entities;

use Doctrine\Common\Collections\ArrayCollection;

/** @Entity @Table(name="thun_subcategories") */
class ThunSubcategory {
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @Column(type="string", length=255) */
    private $name;
    /** @Column(type="string", length=255, unique=true) */
    private $lnk;
    /** @Column(type="string", length=255, nullable=true) */
    private $img_lnk;
    /** @Column(type="integer") */
    private $category_id;
    /** @Column(type="text",nullable=true) */
    private $description;
    /** @Column(type="string", length=255, name="display_name") */
    private $displayName;
    /** @Column(type="string", length=255, unique=true, name="seo_link") */
    private $seoLink;
    /** @ManyToMany(targetEntity="ThunProduct", mappedBy="subcategories") */
    private $products;
    /** @ManyToOne(targetEntity="ThunCategory") */
    private $category;

    public function __construct() {
        $this->products = new ArrayCollection();
    }

    public function getProductsWithLimit($offset, $length){
        return $this->products->slice($offset, $length);
    }

    public function getProducts(){
        return $this->products;
    }

    public function getId(){
        return $this->id;
    }

    public function getName(){
        return ucfirst(mb_strtolower($this->name, 'UTF-8'));
    }

    public function getImage(){
        return $this->img_lnk;
    }

    public function getLink(){
        return $this->lnk;
    }

    public function getDescription(){
        return $this->description;
    }

    /**
     * @return ThunCategory;
     */
    public function getCategory(){
        return $this->category;
    }

    public function getDisplayName(){
        return $this->displayName;
    }

    public function getSeoLink(){
        return $this->seoLink;
    }

    public function getSubcategoryLink(){
        return '/thun/' . $this->getCategory()->getSeoLink() . '/' . $this->getSeoLink();
    }

    public function setName($name){
        $this->name = $name;
    }

    public function setLink($link){
        $this->lnk = $link;
    }

    public function setImgLnk($img_lnk){
        $this->img_lnk = $img_lnk;
    }

    public function setCategoryId($category_id){
        $this->category_id = $category_id;
    }

    public function setCategory($category){
        $this->category = $category;
    }

    public function setDescription($descr){
        $this->description = $descr;
    }

    public function setDisplayName($displayName){
        $this->displayName = $displayName;
    }

    public function setSeoLink($seoLink){
        $this->seoLink = $seoLink;
    }

    public function countProducts(){
        return $this->getProducts()->count();
    }

    public function addProduct(ThunProduct $product){
        $this->products[] = $product;
    }
}