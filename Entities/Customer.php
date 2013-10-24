<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Pavel
 * Date: 20.05.13
 * Time: 15:23
 * To change this template use File | Settings | File Templates.
 */

namespace Entities;

use Doctrine\Common\Collections\ArrayCollection;

/** @Entity @Table(name="customers")*/
class Customer {
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @Column(type="string", length=255, unique=true) */
    private $identity;
    /** @Column(type="string", length=255, nullable=true, unique=true) */
    private $email;
    /** @Column(type="string", length=255, nullable=true) */
    private $password;
    /** @Column(type="string", length=255, nullable=true) */
    private $phone;
    /** @Column(type="string", length=255, nullable=true) */
    private $address;
    /** @Column(type="datetime", name="created_at") */
    private $createdAt;

    /** @OneToMamy(targetEntity="Order", mappedBy="customer") */
    private $orders;
    /** @OneToOne(targetEntity="Cart", mappedBy="customer") */
    private $cart;

    public function __construct(){
        $this->identity = uniqid();
        $this->createdAt = new \DateTime;
        $this->orders = new ArrayCollection();
    }

    public function setIdentity($identity){
        $this->identity = $identity;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setPassword($password){
        $this->password = md5($password);
    }

    public function setPhone($phone){
        $this->phone = $phone;
    }

    public function getPhone(){
        return $this->phone;
    }

    public function setAddress($address){
        $this->address = $address;
    }

    public function getAddress(){
        return $this->address;
    }

    public function setProduct(ThunProduct $product){
        $this->products[] = $product;
    }

    public function getIdentity(){
        return $this->identity;
    }

    public function getProducts(){
        return $this->products;
    }

    public function removeProduct(ThunProduct $product){
        $this->products->removeElement($product);
    }

    public function setCart(Cart $cart){
        $this->cart = $cart;
    }

    public function getCart(){
        return $this->cart;
    }
}