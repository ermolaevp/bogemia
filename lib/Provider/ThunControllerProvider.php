<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Pavel
 * Date: 30.04.13
 * Time: 16:30
 * To change this template use File | Settings | File Templates.
 */

namespace lib\Provider;


use Doctrine\Common\Collections\Criteria;
use Entities\ThunCategory;
use Entities\ThunProduct;
use Entities\ThunSubcategory;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class ThunControllerProvider implements ControllerProviderInterface {

    public function connect(Application $app){

        $controllers = $app['controllers_factory'];

        # product controller
        $controllers->get('/{category}/{subcategory}/{product}', function(Application $app, $category, $subcategory, $product){
            $category = $app['em']->getRepository('Entities\ThunCategory')->findOneBySeoLnk($category);
            $subcategory = $app['em']->getRepository('Entities\ThunSubcategory')->findOneBySeoLink($subcategory);
            $product = $app['em']->getRepository('Entities\ThunProduct')->findOneById((int) $product);

            if(!$category instanceof ThunCategory || !$subcategory instanceof ThunSubcategory || !$product instanceof ThunProduct){
                return $app->abort(404, 'not found');
            }

            return $app['twig']->render('product.html.twig', compact('category', 'subcategory', 'product'));
        })->assert('product', '\d+');

        # subcategory controller
        $controllers->get('/{category}/{subcategory}', function(Application $app, Request $request, $category, $subcategory){
            $category = $app['em']->getRepository('Entities\ThunCategory')->findOneBySeoLnk($category);
            $subcategory = $app['em']->getRepository('Entities\ThunSubcategory')->findOneBySeoLink($subcategory);

            if(!$category instanceof ThunCategory || !$subcategory instanceof ThunSubcategory){
                return $app->abort('404', 'not found');
            }

            // Pagination
            $page = (int) $request->get('page', 1);

            $productsOnPage = 9;

            $pages = $app['pages']($page, $subcategory, $productsOnPage);

            $offset = ($page - 1) * $productsOnPage;

            $products = $subcategory->getProductsWithLimit($offset, $productsOnPage);

            return $app['twig']->render('products.html.twig', compact('category', 'subcategory', 'products', 'page', 'pages'));
        });

        # category controller
        $controllers->get('/{category}', function(Application $app, $category){
            $category = $app['em']->getRepository('Entities\ThunCategory')->findOneBySeoLnk($category);
            if(!$category instanceof ThunCategory){
                return $app->abort(404, 'not found');
            }

            if('farfor_dlia_doma' == $category->getSeoLink()){
                return $app['twig']->render('farfor_dlia_doma.html.twig', compact('category'));
            }

            $subcategory = $category->getSubcategories()->first();

            return $app->redirect("/thun/{$category->getSeoLink()}/{$subcategory->getSeoLink()}");
        });

        return $controllers;
    }
}