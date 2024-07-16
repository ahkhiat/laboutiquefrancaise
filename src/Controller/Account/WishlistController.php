<?php

namespace App\Controller\Account;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WishlistController extends AbstractController
{
    #[Route('/compte/liste-envie', name: 'app_account_wishlist')]
    public function index(): Response
    {
        return $this->render('account/wishlist/index.html.twig', [
        ]);
    }

    #[Route('/compte/liste-envie/add/{id}', name: 'app_account_wishlist_add')]
    public function add(ProductRepository $productRepository, $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        // recuperer l'objet du produit souhaité
        $product = $productRepository->findOneById($id);
        // Si produit existant et si user connecté, ajouter le produit à la wishlist

        if($product) {
            $this->getUser()->addWishlist($product);
            $entityManager->flush();
        }

        $this->addFlash(
            'success',
            'Produit correctement ajouté à votre liste d\'envies'
        );
        return $this->redirect($request->headers->get('referer'));

    }

    #[Route('/compte/liste-envie/remove/{id}', name: 'app_account_wishlist_remove')]
    public function remove(ProductRepository $productRepository, $id,  Request $request,EntityManagerInterface $entityManager): Response
    {
        // recuperer l'objet du produit souhaité
        $product = $productRepository->findOneById($id);
        // Si produit existant et si user connecté, supprimer le produit à la wishlist

        if($product) {
            $this->addFlash(
                'warning',
                'Produit correctement supprimé de votre liste d\'envies'
            );

            $this->getUser()->removeWishlist($product);
            $entityManager->flush();
        } else {
            $this->addFlash(
                'danger',
                'Produit introuvable'
            );
        }

        return $this->redirect($request->headers->get('referer'));


    }



}
