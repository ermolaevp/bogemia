<?php
/**
 * Created by JetBrains PhpStorm.
 * User: developer
 * Date: 06.04.13
 * Time: 0:39
 * To change this template use File | Settings | File Templates.
 */

namespace Entities;

/** @Entity @Table(name="pylones_products") */
class PylonesProduct {
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @Column(type="integer") */
    private $category_id;
    /** @Column(type="string", length=255) */
    private $name;
    /** @Column(type="text", nullable=true) */
    private $description;
    /** @Column(type="text", nullable=true) */
    private $full_description;
    /** @Column(type="string", length=255, nullable=true) */
    private $price;
    /** @Column(type="text", nullable=true) */
    private $models;
    /** @Column(type="integer", nullable=true) */
    private $pylones_id;
    /** @Column(type="text", nullable=true) */
    private $image_ids;
    /** @Column(type="string", length=255) */
    private $img_lnk;
    /** @Column(type="string", length=255) */
    private $lnk;

    public function getCatalogName(){
        return 'pylones';
    }

    public function getId(){
        return $this->id;
    }

    public function getPylonesId(){
        return $this->pylones_id;
    }

    public function getName($transform = false){
        if($transform){
            $name = strtolower($this->name);

            $name = preg_replace('/(\W)+/', '-', $name);

            //$name = str_replace(' - ', '-', $name);
            while(strpos($name, '--')){
                $name = str_replace('--', '-', $name);
            }
//            $name = str_replace(' ', '-', $name);
//            $name = str_replace('\'', '', $name);
//            $name = str_replace('/', '-', $name);
//            $name = str_replace('.', '-', $name);
            return $name;
        }
        return $this->name;
    }

    public function getImage($size = 'large'){
        $image_ids = unserialize($this->image_ids);
        $i_id = $image_ids[0];
        $name = $this->getName(true);
        $p_id = $this->getPylonesId();

        return "http://www.pylones.com/$p_id-$i_id-$size/$name.jpg";
    }

    public function getImgLnk(){
        return $this->img_lnk;
    }

    public function getLnk(){
        return $this->lnk;
    }
}