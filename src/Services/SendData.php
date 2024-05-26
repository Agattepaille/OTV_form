<?php

namespace App\Services;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;


class SendData
{
    private $apiKey;
    private $client;
    private $logger;

    public function __construct(ContainerBagInterface $params, HttpClientInterface $client, LoggerInterface $logger)
    {
        $this->apiKey = $params->get('API_KEY');
        $this->client = $client;
        $this->logger = $logger;
    }

    public function sendData(array $data, UploadedFile $file, string $apiUrl): void
    {

        // Convertir les dates au format ISO 8601
        $start_Date = $data['start_Date'];
        $end_Date = $data['end_Date'];

        // Formatter les dates au format ISO 8601
        $data['start_Date'] = $start_Date->format('Y-m-d\TH:i:sP');
        $data['end_Date'] = $end_Date->format('Y-m-d\TH:i:sP');

        try {
            // Envoyer la requête POST avec les données et le fichier
            $response = $this->client->request('POST', $apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ],
                'body' => [
                    'data' => $data,
                    'file' => fopen($file->getPathname(), 'r'),
                ],
            ]);

            // Gérer la réponse ici si nécessaire
            $statusCode = $response->getStatusCode();
            $this->logger->info('Form data sent.', [
                'status_code' => $statusCode,
                'data' => $data,
                'file' => $file->getClientOriginalName(),
            ]);
        } catch (\Exception $e) {
            // Gérer les erreurs
            $this->logger->error($e->getMessage());
        }
    }
}
