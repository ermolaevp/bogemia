<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Pavel
 * Date: 09.04.13
 * Time: 11:16
 * To change this template use File | Settings | File Templates.
 */

namespace Entities;

/** @Entity @Table(name="catalog") */
class Catalog {
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @var @Column(type="string", length=255) */
    private $name;
    /** @var @Column(type="string", length=255) */
    private $prefix;
    /** @var @Column(type="string", length=255) */
    private $display_name;

    public function getDisplayName(){
        return $this->display_name;
    }
}