<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    #[Route('/')]
    public function home(): Response
    {
        $number = random_int(0, 100);

        return $this->render('home.html.twig', [
//            'number' => $number,
        ]);
    }
}