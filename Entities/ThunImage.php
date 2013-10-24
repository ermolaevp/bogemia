<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Pavel
 * Date: 30.04.13
 * Time: 12:22
 * To change this template use File | Settings | File Templates.
 */

namespace Entities;

/** @Entity @Table(name="thun_images") */
class ThunImage {
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @Column(type="string", length=255) */
    private $link;
    /** @Column(type="string", length=4) */
    private $ext;
    /** @ManyToOne(targetEntity="ThunProduct") */
    private $product;

    public function setLink($link){
        $this->link = $link;
    }

    public function setExt($ext){
        $this->ext = $ext;
    }

    public function setProduct(ThunProduct $product){
        $this->product = $product;
    }

    public function getId(){
        return $this->id;
    }

    public function getProduct(){
        return $this->product;
    }

    public function getExtension(){
        return $this->ext;
    }

    public function getImageName(){
        return $this->getProduct()->getId() . '_' . $this->getId() . '.' . $this->getExtension();
    }

    public function getLink(){
        return $this->link;
    }
}