<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

final class VoitureController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function accueil(VoitureRepository $voitureRepository): Response
    {
        return $this->render('voitures/accueil.html.twig', [
            'voitures' => $voitureRepository->findAll(),
        ]);
    }

    #[Route('/voiture/ajouter', name: 'app_voiture_ajouter')]
    public function ajouter(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $voiture = new Voiture();

        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile !== null) {
                $originalFilename = pathinfo(
                    $imageFile->getClientOriginalName(),
                    PATHINFO_FILENAME
                );

                $safeFilename = $slugger->slug($originalFilename);

                $newFilename = $safeFilename . '-' . uniqid() . '.' .
                    $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('voitures_images_directory'),
                        $newFilename
                    );
                } catch (FileException $exception) {
                    $this->addFlash(
                        'error',
                        'Une erreur est survenue pendant l’envoi de l’image.'
                    );

                    return $this->render('voitures/ajouter.html.twig', [
                        'form' => $form,
                    ]);
                }

                $voiture->setImage($newFilename);
            }

            $entityManager->persist($voiture);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'La voiture a été ajoutée avec succès.'
            );

            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('voitures/ajouter.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route(
        '/voiture/{id}',
        name: 'app_voiture_detail',
        requirements: ['id' => '\d+']
    )]
    public function detail(Voiture $voiture): Response
    {
        return $this->render('voitures/detail.html.twig', [
            'voiture' => $voiture,
        ]);
    }
    #[Route('/voiture/{id}/supprimer', name: 'app_voiture_supprimer', methods: ['POST'])]
    public function supprimer(
        Voiture $voiture,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid(
            'supprimer' . $voiture->getId(),
            $request->request->get('_token')
        )) {
            $entityManager->remove($voiture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_accueil');
    }
}
