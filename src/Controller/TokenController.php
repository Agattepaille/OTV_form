<?php

namespace App\Controller;


use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class TokenController extends AbstractController
{
    private $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    public function generateToken(): Response
    {
        // Générer le token JWT avec les données nécessaires
        $token = $this->jwtManager->create(['username' => 'example']);

        return new Response($token);
    }
}
