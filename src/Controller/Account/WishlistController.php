<?php

namespace App\Controller\Account;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WishlistController extends AbstractController
{
    #[Route('/compte/liste-envie', name: 'app_account_wishlist')]
    public function index(): Response
    {
        return $this->render('account/wishlist/index.html.twig', [
        ]);
    }

    #[Route('/compte/liste-envie/add/{id}', name: 'app_account_wishlist_add')]
    public function add(ProductRepository $productRepository, $id, EntityManagerInterface $entityManager): Response
    {
        // recuperer l'objet du produit souhaité
        $product = $productRepository->findOneById($id);
        // Si produit existant et si user connecté, ajouter le produit à la wishlist

        if($product) {
            $this->getUser()->addWishlist($product);
            $entityManager->flush();
        }
        // return $this->redirectToRoute('app_product');


    }
}
