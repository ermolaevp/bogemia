<?php
/**
 * Created by JetBrains PhpStorm.
 * User: developer
 * Date: 28.04.13
 * Time: 19:18
 * To change this template use File | Settings | File Templates.
 */

namespace Entities;


use Doctrine\Common\Collections\ArrayCollection;

/** @Entity @Table(name="thun_categories") */
class ThunCategory {
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @Column(type="string", length=255) */
    private $name;
    /** @Column(type="string", length=255, unique=true) */
    private $lnk;
    /** @Column(type="string", length=255, name="display_name") */
    private $displayName;
    /** @Column(type="string", length=255, unique=true, name="seo_lnk") */
    private $seoLnk;
    /** @Column(type="text", nullable=true) */
    private $description;
    /** @OneToMany(targetEntity="ThunSubcategory", mappedBy="category") */
    private $subcategories;

    public function __construct(){
        $this->subcategories = new ArrayCollection();
    }

    public function getId(){
        return $this->id;
    }

    public function getLink(){
        return $this->lnk;
    }

    public function getName(){
        return $this->name;
    }

    public function getDescription(){
        return $this->description;
    }

    public function getDisplayName(){
        return $this->displayName;
    }

    public function getSeoLink(){
        return $this->seoLnk;
    }

    public function getSubcategories(){
        return $this->subcategories->filter(function(ThunSubcategory $subcategory){
            return $subcategory->getProducts()->count() > 0;
        });
    }

    public function getCategoryLink(){
        return '/thun/' . $this->getSeoLink();
    }

    public function setName($name){
        $this->name = $name;
    }

    public function setLink($lnk){
        $this->lnk = $lnk;
    }

    public function setDescription($descr){
        $this->description = $descr;
    }
}