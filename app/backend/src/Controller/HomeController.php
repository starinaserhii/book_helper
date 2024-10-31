<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\Assets;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function __construct(private readonly Assets $assets)
    {}

    #[Route('/')]
    public function home(): Response
    {
        return $this->render('home.html.twig', [
            'assets_urls' => $this->assets->getAppAssetsUrls()
        ]);
    }
}