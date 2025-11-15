<?php

namespace App\Controller;

use App\Entity\Modele;
use App\Entity\Voiture;
use App\Form\VoitureForm;
use App\Repository\ModeleRepository;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VoitureController extends AbstractController
{
    #[Route('/voitures', name: 'app_voitures')]
    public function list(VoitureRepository $vr): Response
    {
        $voitures = $vr->findAll();

        return $this->render('voiture/listVoiture.html.twig', [
            'listeVoiture' => $voitures,
        ]);
    }

    #[Route('/addVoiture', name: 'add_voiture')]
    public function addVoiture(Request $request, EntityManagerInterface $em): Response
    {
        $voiture = new Voiture();
        $form = $this->createForm(VoitureForm::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($voiture);
            $em->flush();

            return $this->redirectToRoute('app_voitures'); // Corrigé
        }

        return $this->render('voiture/addVoiture.html.twig', [
            'formV' => $form->createView(),
        ]);
    }

    #[Route('/voiture/{id}/delete', name: 'voitureDelete')]
    public function delete(EntityManagerInterface $em, VoitureRepository $vr, $id): Response
    {
        $voiture = $vr->find($id);

        if ($voiture) {
            $em->remove($voiture);
            $em->flush();
        }

        return $this->redirectToRoute('app_voitures');
    }
    #[Route('/voiture/{id}/update', name: 'voitureUpdate')]
    public function updateVoiture(Request $request, EntityManagerInterface $em, VoitureRepository $vr, $id): Response
    {
        $voiture = $vr->find($id);
        $editform = $this->createForm( VoitureForm::class, $voiture);
        $editform->handleRequest($request);
        if ($editform->isSubmitted() and $editform->isValid()) {
            $em->persist($voiture);
            $em->flush();
            return $this->redirectToRoute( 'app_voitures');
        }
        return $this->render('voiture/updateVoiture.html.twig', [
            'editFormVoiture' => $editform->createView(),
        ]);
    }
#[Route('/modeles')]
#[Route ('/voitures_par-modele', name: 'voitures_par_modele')]
public function voitureParModele(Request $request, EntityManagerInterface $em, VoitureRepository $vr): Response
{
    $modeleId = $request->query->get('modele');
    $voiture = [];
    if ($modeleId) {
        $voitures = $vr->findByModele((int)$modeleId);
    }

    $modeles = $em->getRepository(Modele::class)->findAll();
    return $this->render('voiture/voitureParModele.html.twig', [
        'voitures' => $voiture,
        'modeles' => $modeles,
        'selectedModele' => $modeleId,
    ]);
}
}
