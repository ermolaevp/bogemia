<?php
/**
 * Created by JetBrains PhpStorm.
 * User: developer
 * Date: 06.04.13
 * Time: 0:39
 * To change this template use File | Settings | File Templates.
 */

namespace Entities;

use Doctrine\Common\Collections\ArrayCollection;

/** @Entity @Table(name="thun_products") */
class ThunProduct {
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @Column(type="string", length=255) */
    private $name;
    /** @Column(type="string", length=255, nullable=true, name="name_ru") */
    private $nameRu;
    /** @Column(type="float", nullable=true) */
    private $price;
    /** @Column(type="float", nullable=true, name="price_ru") */
    private $priceRu;
    /** @Column(type="string", length=255, unique=true) */
    private $lnk;
    /** @Column(type="text", nullable=true, name="short_info") */
    private $shortInfo;
    /** @Column(type="text", nullable=true, name="short_info_ru") */
    private $shortInfoRu;
    /** @Column(type="text", nullable=true) */
    private $details;
    /** @Column(type="text", nullable=true, name="details_ru") */
    private $detailsRu;
    /** @Column(type="text", nullable=true, name="img_links") */
    private $imgLinks;
    /** @Column(type="string", length=255, nullable=true) */
    private $article;
    /** @Column(type="string", length=32, nullable=true) */
    private $weight;

    /** @ManyToMany(targetEntity="ThunSubcategory", inversedBy="products") */
    private $subcategories;
    /** @OneToMany(targetEntity="ThunImage", mappedBy="product") */
    private $images;

    public function __construct(){
        $this->images = new ArrayCollection();
    }

    public function getCatalogName(){
        return 'thun';
    }

    /**
     * @return ThunSubcategory;
     */
    public function getSubcategory(){
        return $this->subcategories->first();
    }

    public function getId(){
        return $this->id;
    }

    public function getLnk(){
        return $this->lnk;
    }

    public function getImage(){
        $images = unserialize($this->images);
        return $images[count($images) - 1];
    }

    public function getImages(){
        return $this->images;
    }

    public function getImgLinks(){
        return unserialize($this->imgLinks);
    }

    public function getName(){
        return $this->name;
    }

    public function getNameRu(){
        return $this->nameRu;
    }

    public function getShortInfo($newLines = true){
        return $newLines ? nl2br($this->shortInfo) : $this->shortInfo;
    }

    public function getShortInfoRu($newLines = true){
        return $newLines ? nl2br($this->shortInfoRu) : $this->shortInfoRu;
    }

    public function getDetails($newLines = true){
        return $newLines ? nl2br($this->details) : $this->details;
    }

    public function getDetailsRu($newLines = true){
        return $newLines ? nl2br($this->detailsRu) : $this->detailsRu;
    }

    public function getPrice(){
        return $this->price;
    }

    public function getPriceRu(){
        return $this->priceRu;
    }

    public function getLink(){
        return $this->getLnk();
    }

    public function getArticle(){
        return $this->article;
    }

    public function getWeight(){
        return $this->weight;
    }

    public function getProductLink(){
        $subCategory = $this->getSubcategory();
        $category = $subCategory->getCategory();

        return '/' . implode('/', array(
            'thun',
            $category->getSeoLink(),
            $subCategory->getSeoLink(),
            $this->getId()));
    }

    public function getProductImage($size = 'medium'){
        return '/' . implode('/', array(
            'img',
            'catalog',
            'thun',
            $size,
            $this->getImages()->first()->getImageName()
        ));
    }

    public function setName($name){
        $this->name = $name;
    }

    public function setNameRu($name){
        $this->nameRu = $name;
    }

    public function setPrice($price){
        $price = trim($price);
        $price = preg_replace('/(\D+),?(\D+)/', '', $price);
        $price = str_replace(',','.',$price);
        $price = floatval($price);

        $this->price = $price;
    }

    public function setPriceRu($price){
        $this->priceRu = $price;
    }

    public function setLink($lnk){
        $this->lnk = $lnk;
    }

    public function setShortInfo($info){
        $this->shortInfo = trim($info);
    }

    public function setShortInfoRu($info){
        $this->shortInfoRu = trim($info);
    }

    public function setDetails($details){
        $this->details = trim($details);
    }

    public function setDetailsRu($details){
        $this->detailsRu = trim($details);
    }

    public function setImages(ThunImage $img){
        $this->images = $img;
    }

    public function setSubcategories($subcategories){
        $this->subcategories = $subcategories;
    }

    public function setWeight($weight){
        $this->weight = $weight;
    }

    public function setArticle($article){
        $this->article = $article;
    }
}