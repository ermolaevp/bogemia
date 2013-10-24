<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Pavel
 * Date: 20.05.13
 * Time: 16:06
 * To change this template use File | Settings | File Templates.
 */

namespace Entities;

/** @Entity @Table(name="orders") */
class Order {
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @Column(type="datetime", name="created_at") */
    private $createdAt;
    /** @Column(type="string", length=255, nullable=true) */
    private $address;
    /** @Column(type="string", length=255, nullable=true) */
    private $phone;
    /** @Column(type="integer", length=4, options={"default"="0"}) */
    private $status;
    /** @OneToOne(targetEntity="Customer", inversedBy="orders") */
    private $customer;

    public function __construct(){
        $this->createdAt = new DateTime();
    }
}