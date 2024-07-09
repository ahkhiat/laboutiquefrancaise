<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Dompdf\Dompdf;

class InvoiceController extends AbstractController
{
    #[Route('/facture', name: 'app_invoice')]
    public function index(): Response
    {
        // instantiate and use the dompdf class
        $dompdf = new Dompdf();

        $html = $this->renderView('invoice/index.html.twig');
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream('facture.pdf', [
            'Attachment' => false
        ]);

        exit();
        // return $this->render('invoice/index.html.twig', [
            
        // ]);
    }
}
