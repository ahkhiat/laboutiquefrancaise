<?php

namespace App\Controller;

use App\Class\Cart;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Form\OrderType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    // 1ere étape du tunnel d'achat
    // Choix de l'adresse de livraison et du transporteur

    #[Route('/commande/livraison', name: 'app_order')]
    public function index(): Response
    {
        $addresses = $this->getUser()->getAddresses();

        if(count($addresses) == 0) {

            $this->addFlash(
                'warning',
                'Vous n\'avez aucune adresse enregistrée !'
            );
            $this->addFlash(
                'warning',
                'Veuillez renseigner une adresse'
            );
            return $this->redirectToRoute('app_account_address_form');
        }

        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $this->getUser()->getAddresses(),
            'action' => $this->generateUrl('app_order_summary')
        ]);

        return $this->render('order/index.html.twig', [
            'deliveryForm' => $form->createView(),
        ]);
    }

    // 2e étape du tunnel d'achat
    // Recap de la commande
    // insertion en base de données
    // preparation du paiment

    #[Route('/commande/recapitulatif', name: 'app_order_summary')]
    public function add(Cart $cart, Request $request, EntityManagerInterface $entityManager): Response
    {
        if($request->getMethod() != 'POST') {
            return $this->redirectToRoute('app_cart');
        }

        $products= $cart->getCart();

        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $this->getUser()->getAddresses(),
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // Stocker les infos en bdd
            
            $addressObj = $form->get('addresses')->getData();

            $address = $addressObj->getFirstName().' '.$addressObj->getLastName().'<br>';
            $address .= $addressObj->getAddress().'<br>';
            $address .= $addressObj->getPostal().' '.$addressObj->getCity().'<br>';
            $address .= $addressObj->getCountry().'<br>';
            $address .= $addressObj->getPhone();

            $order = new Order();

            $order->setUser($this->getUser());

            $order->setCreatedAt(new \DateTime());
            $order->setState(1);
            $order->setCarrierName($form->get('carriers')->getData()->getName());
            $order->setCarrierPrice($form->get('carriers')->getData()->getPrice());
            $order->setDelivery($address);

            foreach ($products as $product) {
                $orderDetail = new OrderDetail;
                $orderDetail->setProductName($product['object']->getName());
                $orderDetail->setProductIllustration($product['object']->getIllustration());
                $orderDetail->setProductPrice($product['object']->getPrice());
                $orderDetail->setProductTva($product['object']->getTva());
                $orderDetail->setProductQuantity($product['qty']);
                $order->addOrderDetail($orderDetail);
            }
            $entityManager->persist($order);
            $entityManager->flush();


        }

        return $this->render('order/summary.html.twig',[
            'cart' => $products,
            'choices' => $form->getData(),
            'totalwt' => $cart->getTotalwt(),
            'order' => $order
        ]);
    }

}
