<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Pavel
 * Date: 30.04.13
 * Time: 16:00
 * To change this template use File | Settings | File Templates.
 */
namespace lib\Twig\Extension;

use Entities\ThunCategory;

class BohemiaTwigExtension extends \Twig_Extension {

    private $app;

    public function __construct($app){
        $this->app = $app;
    }

    public function getName(){
        return get_class($this);
    }

    public function getGlobals(){
        return array(
            'customer' => $this->app['customer'],
            'categories' => $this->app['em']->getRepository('Entities\ThunCategory')->findAll(),
            'category' => null
        );
    }

    public function getFunctions(){
        return array(
            new \Twig_SimpleFunction('genMainMenu', array($this, 'genMainMenu')),
        );
    }

    public function genMainMenu(ThunCategory $currentCategory = null){
        $html = '';
        $thunCategories = $this->app['em']->getRepository('Entities\ThunCategory')->findAll();
        foreach ($thunCategories as $category) if ($category instanceof ThunCategory){
            $displayName = $category->getDisplayName();
            $seoLnk = $category->getSeoLink();
            $class = isset($currentCategory) && $currentCategory->getSeoLink() == $seoLnk ? ' class="active"' : '';
            $html .= "<li$class><a href=\"/thun/$seoLnk\">$displayName</a></li>";
        }
        return $html;
    }
}