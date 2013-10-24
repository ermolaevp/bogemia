<?php
require_once __DIR__ . '/../bootstrap.php';

use Silex\Application;
use Doctrine\Common\Collections\Criteria;

/*
$bohemiaCategories = $em->getRepository('Entities\BohemiaCategory')->findAll();
$pylonesCategories = $em->getRepository('Entities\PylonesCategory')->findAll();
$thunCategories = $em->getRepository('Entities\ThunCategory')->findAll();

$bohemiaProducts = $em->getRepository('Entities\BohemiaProduct')->findBy(array(), null, 10);
$pylonesProducts = $em->getRepository('Entities\PylonesProduct')->findBy(array(), null, 10);
$thunProducts = $em->getRepository('Entities\ThunProduct')->findBy(array(), null, 10);
*/

$app['pages'] = $app->protect(function($page, $subcategory, $productsOnPage){

    $pages['count'] = ceil($subcategory->getProducts()->count() / $productsOnPage);

    $from = $page - 3;
    $till = $page + 3;

    $pages['from'] = $from > 0 ? $from : 1;
    $pages['till'] = $till > $pages['count'] ? $pages['count'] : $till;

    return $pages;
});

$app['customer'] = $app->share(function(Application $app){
    $customer = $app['session']->get('customer');

    if($customer){
        $customer = $app['em']->getRepository('Entities\Customer')->findOneByIdentity($customer['identity']);
    }

    if(!$customer instanceof \Entities\Customer){
        $customer = new \Entities\Customer();
        $app['em']->persist($customer);

        $cart = new \Entities\Cart();
        $app['em']->persist($cart);

        $cart->setCustomer($customer);

        $app['em']->flush();

        $app['session']->set('customer', array('identity' => $customer->getIdentity()));
    }
    return $customer;
});

$app->get('/about/', function(Application $app){
    return $app['twig']->render('about.html.twig');
});

$app->get('/contact/', function(Application $app){
    return $app['twig']->render('contact.html.twig');
});

$app->get('/pricing/', function(Application $app){
    return $app['twig']->render('pricing.html.twig');
});

$app->mount('/admin/', new \lib\Provider\AdminControllerProvider());

$app->mount('/thun/', new \lib\Provider\ThunControllerProvider());

$app->mount('/cart/', new \lib\Provider\CartControllerProvider());

$app->get('/widget/cart', function(Application $app){
    return $app['twig']->render('widgets/cart.html.twig');
});



$app->match('/catalog/{catalog}/{category}', function(Application $app, $catalog, $category) use ($em) {

    $catalog = strtolower($catalog);
    if($em->getRepository('Entities\Catalog')->findOneBy(array('prefix' => $catalog))){
        $catalogCategory = ucfirst($catalog) . 'Category';
        $categories = $em->getRepository("Entities\\$catalogCategory")->findAll();

        //$catalogProducts = ucfirst($catalog) . 'Product';
        //$products = $em->getRepository("Entities\\$catalogProducts")->findBy(array('category_id' => $category));

        return $app['twig']->render('catalog.html.twig', compact('categories', 'catalog'));
    }
})
    ->assert('category', '\d+')
    ->value('catalog', 'bohemia')
    ->value('category', 1);

$app->get('/product/{catalog}/{product_id}', function(Application $app, $catalog, $product_id) use ($em) {
    $catalogProduct = ucfirst($catalog) . 'Product';
    $catalogCategory = ucfirst($catalog) . 'Category';
    $catalog = $em->getRepository('Entities\Catalog')->findOneByPrefix($catalog);
    $categories = $em->getRepository("Entities\\$catalogCategory")->findAll();
    $product = $em->getRepository("Entities\\$catalogProduct")->findOneById($product_id);
    return $app['twig']->render('product.html.twig', compact('product', 'categories', 'catalog'));
});
/*
$app->get('/services/', function(Application $app) {
    return $app->redirect('/services/bohemia/');
});
*/

$app->get('/', function(Application $app) {
    # random products for slider
    $products = $app['em']->getRepository('Entities\ThunProduct')->findAll();

    $randomProducts = array(); $i = 0;
    while(count($randomProducts) <= 15 && $i <= 100){
        $i++; $randomNumber = mt_rand(0, count($products) - 1);
        $product = $products[$randomNumber];

        //var_dump($product->getImages());

        if($product->getImages() && $product->getImages()->count() > 0){
            $randomProducts[$product->getId()] = $product;
        }
    }

    $categories = $app['em']->getRepository('Entities\ThunCategory')->findAll();

    return $app['twig']->render('index.html.twig', compact('randomProducts', 'categories'));
});

$app->run();

/**
 * $app->get('/thumb/{file}', function($file) use ($app) {
$image = $app['imagine']->open('images/'.$file);

$transformation = new Imagine\Filter\Transformation();
$transformation->thumbnail(new Imagine\Image\Box(200, 200));
$image = $transformation->apply($image);

$format = pathinfo($file, PATHINFO_EXTENSION);

$response = new Symfony\Component\HttpFoundation\Response();
$response->headers->set('Content-type', 'image/'.$format);
$response->setContent($image->get($format));

return $response;
});
 */