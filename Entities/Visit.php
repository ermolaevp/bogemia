<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Pavel
 * Date: 20.05.13
 * Time: 15:49
 * To change this template use File | Settings | File Templates.
 */

namespace Entities;


/** @Entity @Table(name="visits") */
class Visit {
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @Column(type="string", length=255, nullable=true) */
    private $userAgent;
    /** @Column(type="string", length=255, nullable=true) */
    private $referer;
    /** @Column(type="datetime", name="created_at") */
    private $createdAt;
    /** @Column(type="string", length=255, nullable=true) */
    private $ip;

    public function __construct(){
        $this->createdAt = new \DateTime();
        $this->userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $this->referer   = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $this->ip        = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    }

    public function getId(){
        return $this->id;
    }
}