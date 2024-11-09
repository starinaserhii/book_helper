<?php

namespace App\Controller;

use App\Entity\Language;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class LanguageController extends AbstractController
{
    #[Route('/api/language/create', name: 'app_lang')]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $name = $request->request->get('name');
        $repository = $entityManager->getRepository(Language::class);
        $author = $repository->findOneBy(['name' => $name]);

        if (empty($author)) {
            $entityManager->persist( new Language($name));
            $entityManager->flush();
        }

        return new JsonResponse(['success' => true]);
    }
}
