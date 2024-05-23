<?php

namespace App\Controller;

use App\Form\otvType;
use App\Services\SendDataService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class HomeController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function index(LoggerInterface $logger, Request $request, SendDataService $dataService, HttpClientInterface $client): Response
    {
        $form = $this->createForm(otvType::class);
        $form->handleRequest($request);
        
        /*  if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $logger->info('Form data submitted.', $formData);

           // Récupérer le fichier PDF
            $pdfFile = $form->get('file')->getData();
            $logger->info('PDF file uploaded.' . $pdfFile->getClientOriginalName());
            if (!$pdfFile) {
                $this->addFlash('error', 'Aucun fichier PDF fourni.');
                return $this->redirectToRoute('app_home');
            }

            try {
                $apiUrl = 'https://127.0.0.1:8001/otv/new';
                $dataService->sendData($formData, $pdfFile, $apiUrl);
            } catch (\Exception $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', 'Échec de l\'envoi des données.');
                return $this->redirectToRoute('app_home');
            }

            // Enregistrez vos données et le fichier PDF dans la base de données ou où vous en avez besoin
            $this->addFlash('success', 'Les données ont été envoyées avec succès.');
            return $this->redirectToRoute('app_home');
        } else {
            $logger->info('Form data not submitted.');
        } */

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
