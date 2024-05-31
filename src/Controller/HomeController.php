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
                $apiUrl = $this->apiUrl;
                $dataService->sendData($formData, $pdfFile, $apiUrl);

                // Envoi de l'email de confirmation
                $logoPolice = $sendMail->imageToBase64($this->getParameter('kernel.project_dir') . '/public/assets/images/Logo_Police_Municipale__France_.webp');
                $from = new Address('noreply@marcq-en-baroeul');
                $toUser = $formData['email'];
                $subject = 'Confirmation de votre demande d\'Opération Tranquillité Vacances';
                $template = 'confirmation';
                $context = [
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
                    'email' => $formData['email'],
                    'houseType' => $formData['houseType'],
                    'hasAlarm' => $formData['hasAlarm'] ? 'Oui' : 'Non',
                    'hasAlarmExt' => $formData['hasAlarmExt'] ? 'Oui' : 'Non',
                    'hasCamera' => $formData['hasCamera'] ? 'Oui' : 'Non',
                    'hasAnimal' => $formData['hasAnimal'] ? 'Oui' : 'Non',
                    'blindsSchedule' => $formData['blindsSchedule'],
                    'lightingSchedule' => $formData['lightingSchedule'],
                    'car' => $formData['car'],
                    'additionalInfo' => $formData['additionalInfo'],
                    'authorizedPerson' => $formData['authorizedPerson'],
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
