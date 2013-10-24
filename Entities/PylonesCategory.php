<?php
/**
 * Created by JetBrains PhpStorm.
 * User: developer
 * Date: 05.04.13
 * Time: 23:49
 * To change this template use File | Settings | File Templates.
 */

namespace Entities;

/** @Entity @Table(name="pylones_categories") */
class PylonesCategory {
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @Column(type="string", length=255) */
    private $name;
    /** @Column(type="string", length=255, unique=true) */
    private $lnk;

    public function getId(){
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }
}