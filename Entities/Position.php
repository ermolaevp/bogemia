<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Pavel
 * Date: 29.05.13
 * Time: 17:02
 * To change this template use File | Settings | File Templates.
 */

namespace Entities;

/** @Entity @Table(name="positions") */
class Position {
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @ManyToOne(targetEntity="Cart", inversedBy="positions") */
    private $cart;
    /** @OneToOne(targetEntity="ThunProduct") */
    private $product;
    /** @Column(type="integer", options={"default"=1}) */
    private $quantity;

    public function getId(){
        return $this->id;
    }

    public function setCart(Cart $cart){
        $this->cart = $cart;
    }

    public function setProduct(ThunProduct $product, $qty = 1){
        $this->product = $product;
        $this->quantity = $qty;
    }

    public function getProduct(){
        return $this->product;
    }

    public function setQty($qty){
        $this->quantity = $qty;
    }

    public function getQty(){
        return $this->quantity;
    }

    public function increaseQty(){
        $this->quantity++;
    }
}