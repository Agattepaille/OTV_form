<?php

namespace App\Controller;

use App\Form\otvType;
use App\Services\SendData;
use App\Services\SendMail;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private string $apiUrl;

    public function __construct(string $apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function index(SendMail $sendMail, LoggerInterface $logger, Request $request, SendData $dataService): Response
    {
        $form = $this->createForm(otvType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->addFlash('error', 'Veuillez vérifier les données saisies.');
                return $this->redirectToRoute('app_home');
            }

            $formData = $form->getData();
            $logger->info('Form data submitted.', $formData);

            $pdfFile = $form->get('file')->getData();
            if (!$pdfFile) {
                $this->addFlash('error', 'Aucun fichier PDF fourni.');
                return $this->redirectToRoute('app_home');
            }

            $logger->info('PDF file uploaded: ' . $pdfFile->getClientOriginalName());

            if (!$this->sendData($dataService, $formData, $pdfFile, $logger)) {
                return $this->redirectToRoute('app_home');
            }

            if (!$this->sendConfirmationEmail($sendMail, $formData, $logger)) {
                return $this->redirectToRoute('app_home');
            }

            $this->addFlash('success', 'Votre demande a bien été envoyée.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function sendData(SendData $dataService, array $formData, $pdfFile, LoggerInterface $logger): bool
    {
        try {
            $dataService->sendData($formData, $pdfFile, $this->apiUrl);
            return true;
        } catch (\Exception $e) {
            $logger->error('Data submission failed: ' . $e->getMessage());
            $this->addFlash('error', 'Échec de l\'envoi du formulaire.');
            return false;
        }
    }

    private function sendConfirmationEmail(SendMail $sendMail, array $formData, LoggerInterface $logger): bool
    {
        try {
            $logoPolice = $sendMail->imageToBase64($this->getParameter('kernel.project_dir') . '/public/assets/images/Logo_Police_Municipale__France_.webp');
            $from = new Address('noreply@marcq-en-baroeul');
            $toUser = $formData['email'];
            $subject = 'Confirmation de votre demande d\'Opération Tranquillité Vacances';
            $template = 'confirmation';
            $context = $this->createEmailContext($formData, $logoPolice);

            $sendMail->send($from, $toUser, $subject, $template, $context);
            return true;
        } catch (\Exception $e) {
            $logger->error('Email sending failed: ' . $e->getMessage());
            $this->addFlash('error', 'Échec de l\'envoi de l\'email de confirmation.');
            return false;
        }
    }

    private function createEmailContext(array $formData, string $logoPolice): array
    {
        return [
            'civility' => $formData['civility'],
            'otherCivility' => $formData['otherCivility'],
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
            'mobilePhone' => $formData['mobilePhone'],
            'landlinePhone' => $formData['landlinePhone'],
            'courriel' => $formData['email'],
            'houseType' => $formData['houseType'],
            'hasAlarm' => $formData['hasAlarm'] ? 'Oui' : 'Non',
            'hasAlarmExt' => $formData['hasAlarmExt'] ? 'Oui' : 'Non',
            'hasCamera' => $formData['hasCamera'] ? 'Oui' : 'Non',
            'hasAnimal' => $formData['hasAnimal'] ? 'Oui' : 'Non',
            'blindsSchedule' => $formData['blindsSchedule'],
            'lightsSchedule' => $formData['lightsSchedule'],
            'car' => $formData['car'],
            'additionalInfo' => $formData['additionalInfo'],
            'authorizedPersons' => $formData['authorizedPersons'],
            'emergency_civility_1' => $formData['emergency_civility_1'],
            'emergency_lastname_1' => $formData['emergency_lastname_1'],
            'emergency_firstname_1' => $formData['emergency_firstname_1'],
            'emergency_mobilePhone_1' => $formData['emergency_mobilePhone_1'],
            'emergency_landlinePhone_1' => $formData['emergency_landlinePhone_1'],
            'emergency_email_1' => $formData['emergency_email_1'],
            'emergency_civility_2' => $formData['emergency_civility_2'],
            'emergency_lastname_2' => $formData['emergency_lastname_2'],
            'emergency_firstname_2' => $formData['emergency_firstname_2'],
            'emergency_mobilePhone_2' => $formData['emergency_mobilePhone_2'],
            'emergency_landlinePhone_2' => $formData['emergency_landlinePhone_2'],
            'emergency_email_2' => $formData['emergency_email_2'],
            'emergency_civility_3' => $formData['emergency_civility_3'],
            'emergency_lastname_3' => $formData['emergency_lastname_3'],
            'emergency_firstname_3' => $formData['emergency_firstname_3'],
            'emergency_mobilePhone_3' => $formData['emergency_mobilePhone_3'],
            'emergency_landlinePhone_3' => $formData['emergency_landlinePhone_3'],
            'emergency_email_3' => $formData['emergency_email_3'],
        ];
    }
}
