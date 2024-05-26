<?php

namespace App\Controller;

use App\Form\otvType;
use App\Services\SendData;
use App\Services\SendMail;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function index(SendMail $sendMail, LoggerInterface $logger, Request $request, SendData $dataService): Response
    {
        $form = $this->createForm(otvType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $formData = $form->getData();
            $logger->info('Form data submitted.', $formData);
            if (!$form->isValid()) {
                $this->addFlash('error', 'Veuillez vérifier les données saisies.');
                return $this->redirectToRoute('app_home');
            }

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

                // Envoi de l'email de confirmation
                $logoPolice = $sendMail->imageToBase64($this->getParameter('kernel.project_dir') . '/public/assets/images/Logo_Police_Municipale__France_.webp');
                $from = new Address('noreply@marcq-en-baroeul');
                $toUser = $formData['email'];
                $subject = 'Confirmation de votre demande d\'Opération Tranquillité Vacances';
                $template = 'confirmation';
                $context = [
                    'lastname' => $formData['lastname'],
                    'firstname' => $formData['firstname'],
                    'startDate' => $formData['start_Date']->format('d-m-Y'),
                    'endDate' => $formData['end_Date']->format('d-m-Y'),
                    'street' => $formData['street'],
                    'streetNumber' => $formData['streetNumber'],
                    'additionalStreetNumber' => $formData['additionalStreetNumber'],
                    'additionalAddressInfo' => $formData['additionalAddressInfo'],
                    'district' => $formData['district'],
                    'logoPolice' => $logoPolice,
                ];

                $sendMail->send(
                    $from,
                    $toUser,
                    $subject,
                    $template,
                    $context
                );

                $this->addFlash('success', 'Votre demande a bien été envoyée.');
            } catch (\Exception $e) {
                $logger->error($e->getMessage());
                $this->addFlash('error', 'Échec de l\'envoi des données2.');
                return $this->redirectToRoute('app_home');
            }


            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
