<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Pavel
 * Date: 30.05.13
 * Time: 12:21
 * To change this template use File | Settings | File Templates.
 */

namespace lib\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Form\FormFactory;

class CartControllerProvider implements ControllerProviderInterface {

    /**
     * @var ControllerCollection;
     */
    private $controllers;

    /**
     * @var EntityManager;
     */
    private $entityManager;

    /**
     * @var FormFactory;
     */
    private $formFactory;

    public function connect(Application $app){

        $this->controllers = $app['controllers_factory'];

        $this->entityManager = $app['em'];

        $em = $this->entityManager;

        $this->formFactory = $app['form.factory'];

        $formFactory = $this->formFactory;

        # Set qty
        $this->controllers->get('/setqty/{position_id}/{qty}', function(Application $app, $position_id, $qty) use ($em){
            $position = $em->getRepository('Entities\Position')->findOneById($position_id);
            $position->setQty($qty);
            $em->flush();
            return $app->json('ok');
        });

        # Add product to cart
        $this->controllers->get('/add/{product_id}', function(Application $app, $product_id) use ($em){

            $product = $em->getRepository('Entities\ThunProduct')->findOneById($product_id);

            $customer = $app['customer'];
            if(!$customer instanceof \Entities\Customer){
                throw new \Exception('customer not found');
            }

            $cart = $customer->getCart();
            if(!isset($cart)){
                $cart = new \Entities\Cart();
                $em->persist($cart);
                $cart->setCustomer($customer);
            }

            # looking for product always added to cart
            $criteria = Criteria::create()->where(Criteria::expr()->eq('product', $product_id));
            $position = $cart->getPositions()->matching($criteria)->first();
            if(!$position instanceof \Entities\Position){
                $position = new \Entities\Position();
                $position->setProduct($product);
                $em->persist($position);
                $position->setCart($cart);
            } else {
                $position->increaseQty();
            }

            $em->flush();

            return $app->json($product->getName());
        });

        # Delete cart position
        $this->controllers->get('/delete/{position_id}', function(Application $app, $position_id){

            $position = $app['em']->getRepository('Entities\Position')->findOneById((int) $position_id);
            $app['em']->remove($position);

            $app['em']->flush();

            return $app->json('ok');
        });

        # Confirm
        $this->controllers->get('/confirm', function(Application $app) use ($formFactory){

            $customer = $app['customer'];

            $data = array(
                'email' => $customer->getEmail(),
                'phone' => $customer->getPhone(),
                'address' => $customer->getAddress(),
            );

            $form = $formFactory->createBuilder('form', $data)
                ->add('name', 'text', array('label' => 'Фамилия Имя'))
                ->add('phone', 'text', array('label' => 'Телефон'))
                ->add('address', 'text', array('label' => 'Адрес доставки'));

            if(!$data['email']){
                $form->add('email', 'text', array('label' => 'E-mail'))
                    ->add('password', 'text', array('label' => 'Пароль'))
                    ->add('password_confirm', 'text', array('label' => 'Повторите пароль'));
            }

            $form = $form->getForm()->createView();

            return $app['twig']->render('confirm.html.twig', compact('form'));
        });

        # Cart page
        $this->controllers->get('/', function(Application $app){
            return $app['twig']->render('cart.html.twig');
        });

        return $this->controllers;
    }
}