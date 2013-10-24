<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Pavel
 * Date: 29.05.13
 * Time: 11:53
 * To change this template use File | Settings | File Templates.
 */

namespace Entities;

use Doctrine\Common\Collections\ArrayCollection;

/** @Entity @Table(name="carts") */
class Cart {
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @OneToOne(targetEntity="Customer", inversedBy="cart") */
    private $customer;
    /** @OneToMany(targetEntity="Position", mappedBy="cart") */
    private $positions;

    public function __construct(){
        $this->positions = new ArrayCollection();
    }

    public function getId(){
        return $this->id;
    }

    public function setCustomer(Customer $customer){
        $this->customer = $customer;
    }

    public function addProduct(ThunProduct $product){
        $this->products[] = $product;
    }

    public function delProduct(ThunProduct $product){
        $this->products->removeElement($product);
    }

    public function setQty($qty = 1){
        $this->quantity = $qty;
    }

    public function getQty(){
        return $this->quantity;
    }

    public function getPositions(){
        return $this->positions;
    }

    public function getCustomer(){
        return $this->customer;
    }

    public function addPosition(Position $position){
        $this->positions[] = $position;
    }
}