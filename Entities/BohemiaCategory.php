<?php
/**
 * Created by JetBrains PhpStorm.
 * User: developer
 * Date: 05.04.13
 * Time: 23:49
 * To change this template use File | Settings | File Templates.
 */

namespace Entities;

/** @Entity @Table(name="bohemia_categories") */
class BohemiaCategory {
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @Column(type="string", length=255) */
    private $name;
    /** @Column(type="string", length=255, unique=true) */
    private $lnk;

    public function getName() {
        return $this->name;
    }

    public function getId(){
        return $this->id;
    }
}