<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Dompdf\Dompdf;

class InvoiceController extends AbstractController
{
    // Impression PDF pour utilisateur connecté
    // vérification de la commande pour un utilisateur donné
    #[Route('/compte/facture/impression/{id_order}', name: 'app_invoice_customer')]
    public function invoiceCustomer(OrderRepository $orderRepository, $id_order): Response
    {
        $order = $orderRepository->findOneById($id_order);

        if(!$order) {
            return $this->redirectToRoute('app_account');
        }

        if($order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_account');
        }

        $dompdf = new Dompdf();
        $html = $this->renderView('invoice/index.html.twig', [
            'order' => $order
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portait');
        $dompdf->render();
        $dompdf->stream('facture.pdf', [
            'Attachment' => false
        ]);
        exit();
       
    }

    // Impression PDF pour admin
    // vérification de la commande pour un utilisateur donné
    #[Route('/admin/facture/impression/{id_order}', name: 'app_invoice_admin')]
    public function invoiceAdmin(OrderRepository $orderRepository, $id_order): Response
    {
        $order = $orderRepository->findOneById($id_order);

        if(!$order) {
            return $this->redirectToRoute('admin');
        }

        $dompdf = new Dompdf();
        $html = $this->renderView('invoice/index.html.twig', [
            'order' => $order
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portait');
        $dompdf->render();
        $dompdf->stream('facture.pdf', [
            'Attachment' => false
        ]);
        exit();
       
    }
}
