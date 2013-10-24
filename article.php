<?php
require_once 'bootstrap.php';

//$products = $app['em']->getRepository('Entities\ThunProduct')->findBy(array(), null, 2);
$products = $app['em']->getRepository('Entities\ThunProduct')->findAll();
foreach ($products as $product) if ($product instanceof \Entities\ThunProduct) {
    $page = file_get_contents($product->getLnk());
    $doc = phpQuery::newDocument($page);
    $detailsTable = $doc->find('table#product-attribute-specs-table tr');

    $details = array();

    foreach ($detailsTable as $row) {
        $details[] = pq($row)->find('td')->text();
    }

    $product->setArticle($details[0]);
    $product->setWeight($details[1]);

    var_dump($product->getLnk(), $details);
}

$app['em']->flush();