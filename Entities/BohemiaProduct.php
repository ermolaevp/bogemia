<?php
/**
 * Created by JetBrains PhpStorm.
 * User: developer
 * Date: 06.04.13
 * Time: 0:39
 * To change this template use File | Settings | File Templates.
 */

namespace Entities;

/** @Entity @Table(name="bohemia_products") */
class BohemiaProduct {
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @Column(type="integer") */
    private $category_id;
    /** @Column(type="string", length=255) */
    private $name;
    /** @Column(type="string", length=255) */
    private $img_lnk;

    public function getCatalogName(){
        return 'bohemia';
    }

    public function getImage(){
        return 'http://www.crystal-bohemia.com' . $this->img_lnk;
    }

    public function getName(){
        return $this->name;
    }

    public function getId(){
        return $this->id;
    }

    public function getCategoryId(){
        return $this->category_id;
    }
}