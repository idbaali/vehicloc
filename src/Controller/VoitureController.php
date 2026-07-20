<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class VoitureController extends AbstractController
{
    #[Route('/', name: 'app_accueil', methods: ['GET'])]
    public function accueil(
        VoitureRepository $voitureRepository
    ): Response {
        return $this->render('voitures/accueil.html.twig', [
            'voitures' => $voitureRepository->findAll(),
        ]);
    }

    #[Route(
        '/voiture/ajouter',
        name: 'app_voiture_ajouter',
        methods: ['GET', 'POST']
    )]
    public function ajouter(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $voiture = new Voiture();

        $form = $this->createForm(
            VoitureType::class,
            $voiture
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
        requirements: ['id' => '\d+'],
        methods: ['GET']
    )]
    public function detail(Voiture $voiture): Response
    {
        return $this->render('voitures/detail.html.twig', [
            'voiture' => $voiture,
        ]);
    }

    #[Route(
        '/voiture/{id}/supprimer',
        name: 'app_voiture_supprimer',
        requirements: ['id' => '\d+'],
        methods: ['GET']
    )]
    public function supprimer(
        Voiture $voiture,
        EntityManagerInterface $entityManager
    ): Response {
        $entityManager->remove($voiture);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'La voiture a été supprimée avec succès.'
        );

        return $this->redirectToRoute('app_accueil');
    }
}
