<?php

namespace App\Controller;

use Exception;
use Milon\Barcode\DNS1D;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BarcodeController extends AbstractController
{
    /**
     * @Route("/barcode", name="app_barcode", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        try {
            $number = $request->query->get('number');

            if (!$number) {
                return new Response('No number provided', Response::HTTP_BAD_REQUEST);
            }

            $d = new DNS1D();
            $d->setStorPath(__DIR__ . '/cache/');
            $barcode = $d->getBarcodePNG($number, 'C128', 2, 120, [1, 1, 1], true);

            $response = new Response(base64_decode($barcode));
            $response->headers->set('Content-Type', 'image/png');

            return $response;
        } catch (Exception $e) {
            return new Response('An error occurred: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
