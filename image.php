<?php
/**
 * Date: 24.04.13
 */

require_once 'bootstrap.php';

$bohemiaProducts = $em->getRepository('Entities\BohemiaProduct')->findBy(array(), null, 1000);
$pylonesProducts = $em->getRepository('Entities\PylonesProduct')->findBy(array(), null, 1000);
$thunProducts = $em->getRepository('Entities\ThunProduct')->findBy(array(), null, 1000);

function imgDownloader($products){
    $picturePath = __DIR__ . "/public/img/catalog/%s/%d";
    foreach($products as $product){
        //var_dump($product->getImage()); continue;
        $pPath = sprintf($picturePath, $product->getCatalogName(), $product->getId());
        var_dump($product->getLnk());
        var_dump($product->getImage());
        if(!file_exists($pPath)){
            file_put_contents($pPath, file_get_contents($product->getImage()));
        }
        if(file_exists($pPath)){
            $finfo = finfo_open(FILEINFO_MIME_TYPE); // возвращает mime-тип
            $mimeType = finfo_file($finfo, $pPath); //var_dump($mimeType);
            finfo_close($finfo);
            switch ($mimeType) {
                case 'image/jpeg':
                    $newName = $pPath . '.jpg';
                    break;
                case 'image/png':
                    $newName = $pPath . '.png';
                    break;
                case 'image/gif':
                    $newName = $pPath . '.gif';
                    break;
                default:
                    $newName = false;
                    break;
            }
            if($newName && !file_exists($newName)){
                rename($pPath, $newName);
            } else {
                unlink($pPath);
            }
            var_dump($newName);
            echo "\n";
        }
    }
}

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

function createThumbnails($products){
    $picturePath = __DIR__ . "/public/img/catalog/%s/%d.jpg";
    $picturePathTarget = __DIR__ . "/public/img/catalog/%s/medium/%d.jpg";

    foreach($products as $product){
        $pPath = sprintf($picturePath, $product->getCatalogName(), $product->getId());
        $tPath = sprintf($picturePathTarget, $product->getCatalogName(), $product->getId());

        if(file_exists($tPath) || !file_exists($pPath)) continue;

        var_dump($tPath);

        $imagine = new \Imagine\Gd\Imagine();
        $size    = new \Imagine\Image\Box(198, 132);
        $mode    = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;

        $imagine->open($pPath)
            ->thumbnail($size, $mode)
            ->save($tPath)
        ;
    }
}

//imgDownloader($thunProducts);

//createThumbnails($pylonesProducts);
//createThumbnails($bohemiaProducts);
createThumbnails($thunProducts);

