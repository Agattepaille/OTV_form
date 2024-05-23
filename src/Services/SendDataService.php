<?php

namespace App\Services;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SendDataService
{
    public function __construct(
        private HttpClientInterface $client,
        private LoggerInterface $logger
    ) {
        $this->client = $client;
        $this->logger = $logger;

    }

    public function sendData(array $data, string $tempFilePath, string $apiUrl): void
    {
        // Convertir les données en tableau associatif
        // $jsonData = json_encode($data);

        // Ouvrir le fichier temporaire en mode lecture
        $fileResource = fopen($tempFilePath, 'r');

        // Construire le contenu de la requête
        $formData = [
            'data' => $data,
            'file' => $fileResource, // Utiliser directement l'objet UploadedFile
        ];

        try {
            // Envoyer la requête POST avec les données et le fichier
            $response = $this->client->request('POST', $apiUrl, [
                'body' => $formData,
            ]);

            // Gérer la réponse ici si nécessaire
            $statusCode = $response->getStatusCode();
            $this->logger->info('Form data sent.', $formData);

        } catch (\Exception $e) {
            // Gérer les erreurs
            $this->logger->error($e->getMessage());
        }
    }
}
