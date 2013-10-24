<?php
/**
 * Created by JetBrains PhpStorm.
 * User: developer
 * Date: 12.05.13
 * Time: 19:22
 * To change this template use File | Settings | File Templates.
 */

namespace lib\Provider;


use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Entities\ThunCategory;
use Entities\ThunSubcategory;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminControllerProvider implements ControllerProviderInterface {

    /**
     * @var ControllerCollection;
     */
    private $controllers;


    /**
     * @var EntityManager
     */
    private $em;

    public function connect(Application $app){

        $this->controllers = $app['controllers_factory'];

        $this->em = $app['em'];

        $em = $this->em;

        $auth = function() use ($app){
            $username = $app['request']->server->get('PHP_AUTH_USER', false);
            $password = $app['request']->server->get('PHP_AUTH_PW');

            if ('admin' === $username && 'isawlittlebird' === $password) {
                $app['session']->set('user', array('username' => $username));
                return;
            }

            $response = new Response();
            $response->headers->set('WWW-Authenticate', sprintf('Basic realm="%s"', 'Login'));
            $response->setStatusCode(401, 'Please sign in.');
            return $response;
        };

        # Show thun subcategories
        $this->controllers->get('/thun/categories/{category}/{subcategory}/', function(Application $app, $category, $subcategory){
            $category = $app['em']->getRepository('Entities\ThunCategory')->findOneBySeoLnk($category);
            $subcategory = $app['em']->getRepository('Entities\ThunSubcategory')->findOneBySeoLink($subcategory);
            if(!$category instanceof ThunCategory || !$subcategory instanceof ThunSubcategory){
                return $app->abort(404, 'not found');
            }
            $products = $subcategory->getProducts();
            return $app['twig']->render('admin/products.html.twig', compact('category', 'subcategory', 'products'));
        })->before($auth);

        # Show thun categories
        $this->controllers->get('/thun/categories/{category}/', function(Application $app, $category){
            $category = $app['em']->getRepository('Entities\ThunCategory')->findOneBySeoLnk($category);
            if(!$category instanceof ThunCategory){
                return $app->abort(404, 'not found');
            }
            $subcategories = $category->getSubcategories();
            return $app['twig']->render('admin/subcategories.html.twig', compact('category', 'subcategories'));
        })->before($auth);

        $this->controllers->get('/thun/categories/', function(Application $app){
            $categories = $app['em']->getRepository('Entities\ThunCategory')->findAll();
            return $app['twig']->render('admin/categories.html.twig', compact('categories'));
        })->before($auth);

        # Edit product
        $this->controllers->match('/edit/product/{id}/', function(Application $app, Request $request, $id){

            $product = $app['em']->getRepository('Entities\ThunProduct')->findOneById($id);

            $formFactory = $app['form.factory'];

            $data = array(
                'name' => $product->getName(),
                'short_info' => $product->getShortInfo(false),
                'details' => $product->getDetails(false),
                'price' => $product->getPrice(),
                'name_ru' => $product->getNameRu(),
                'short_info_ru' => $product->getShortInfoRu(false),
                'details_ru' => $product->getDetailsRu(false),
                'price_ru' => $product->getPriceRu(),
            );

            $form = $formFactory->createBuilder('form', $data)
                ->add('name', 'text', array('max_length' => '255', 'attr' => array('class' => 'input-xxlarge'), 'read_only' => true))
                ->add('name_ru', 'text', array('max_length' => '255', 'attr' => array('class' => 'input-xxlarge')))
                ->add('short_info', 'textarea', array('attr' => array('rows' => 8, 'class' => 'input-xxlarge'), 'read_only' => true))
                ->add('short_info_ru', 'textarea', array('attr' => array('rows' => 8, 'class' => 'input-xxlarge')))
                ->add('details', 'textarea', array('attr' => array('rows' => 8, 'class' => 'input-xxlarge'), 'read_only' => true))
                ->add('details_ru', 'textarea', array('attr' => array('rows' => 8, 'class' => 'input-xxlarge')))
                ->add('price', 'text', array('read_only' => true))
                ->add('price_ru', 'text')
            ->getForm();

            if("POST" == $request->getMethod()){
                $form->bind($request);
                if($form->isValid()){
                    $data = $form->getData();

                    $product->setNameRu($data['name_ru']);
                    $product->setShortInfoRu($data['short_info_ru']);
                    $product->setDetailsRu($data['details_ru']);
                    $product->setPriceRu($data['price_ru']);

                    $app['em']->flush();

                    $subcategory = $product->getSubcategory();
                    $category    = $subcategory->getCategory();

                    return $app->redirect("/admin/thun/categories/{$category->getSeoLink()}/{$subcategory->getSeoLink()}/#product-{$id}");
                }
            }

            return $app['twig']->render('/admin/edit.html.twig', array('form' => $form->createView(), 'name' => $product->getName()));
        })->before($auth);

        # Edit subcategory
        $this->controllers->match('/edit/subcategory/{id}/', function(Application $app, Request $request, $id){

            $subcategory = $app['em']->getRepository('Entities\ThunSubcategory')->findOneById($id);

            $formFactory = $app['form.factory'];

            $data = array(
                'display_name' => $subcategory->getDisplayName(),
                'seo_link' => $subcategory->getSeoLink()
            );

            $form = $formFactory->createBuilder('form', $data)
                ->add('display_name')
                ->add('seo_link')
            ->getForm();

            if('POST' == $request->getMethod()){
                $form->bind($request);
                if($form->isValid()){
                    $data = $form->getData();

                    $data['seo_link'] = strtolower($data['seo_link']);
                    $data['seo_link'] = str_replace(' ', '_', $data['seo_link']);

                    $subcategory->setDisplayName($data['display_name']);
                    $subcategory->setSeoLink($data['seo_link']);
                    $app['em']->flush();

                    $categorySeoLink = $subcategory->getCategory()->getSeoLink();

                    return $app->redirect("/admin/thun/categories/$categorySeoLink/");
                }
            }

            return $app['twig']->render('/admin/edit.html.twig', array('form' => $form->createView(), 'name' => $subcategory->getName()));
        })->before($auth);

        # Main page
        $this->controllers->get('/', function(Application $app){
            return $app->redirect('/admin/thun/categories/');
        })->before($auth);

        # Search and replace
        $this->controllers->get('/replace', function(Application $app, Request $request) use ($em) {
            $search = $request->get('search', null);
            $replace = $request->get('replace', null);
            $options = $request->get('options', array());

            if(!(is_null($search) || is_null($replace))){
                $params = array(
                    $search,
                    $replace,
                    "%$search%"
                );

                //var_dump($options);

                if('true' == $options['shortInfo']){
                    $shortInfo = $em->getConnection()->executeUpdate("UPDATE thun_products set short_info_ru = replace(short_info_ru, ?, ?) WHERE short_info_ru LIKE ?", $params);
                } else $shortInfo = 0;

                if('true' == $options['details']){
                    $details = $em->getConnection()->executeUpdate("UPDATE thun_products set details_ru = replace(details_ru, ?, ?) WHERE details_ru LIKE ?", $params);
                } else $details = 0;

                if('true' == $options['name']){
                    $name = $em->getConnection()->executeUpdate("UPDATE thun_products set name_ru = replace(name_ru, ?, ?) WHERE name_ru LIKE ?", $params);
                } else $name = 0;

                return $app->json(compact('shortInfo', 'details', 'name'));
            }

            $error = array('error' => 'nothing to search or replace');

            return $app->json(compact('error'));
        })->before($auth);

        return $this->controllers;
    }

}