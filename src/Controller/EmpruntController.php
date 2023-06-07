<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Form\EmpruntType;
use App\Repository\EmpruntRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/emprunt')]
class EmpruntController extends AbstractController
{

    #[Route('/en_cours', name: 'app_emprunts_en_cours_index', methods: ['GET'])]
    public function enCours(EmpruntRepository $empruntRepository): Response
    {
        $empruntsEnCours = $empruntRepository->findEmpruntsEnCours();

        return $this->render('emprunt/en_cours.html.twig', [
            'emprunts' => $empruntsEnCours,
        ]);
    }

    #[Route('/', name: 'app_emprunt_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EmpruntRepository $empruntRepository): Response
    {
        // Créer le formulaire pour la plage de dates
        $form = $this->createFormBuilder()
            ->add('start', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
            ])
            ->add('end', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
            ])
            ->getForm();

        $form->handleRequest($request);

        $emprunts = $empruntRepository->findAll(); // Récupérer tous les emprunts (commun aux deux cas)

        // Si le formulaire est soumis et valide, récupérer les dates et effectuer la recherche
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $start = $data['start'];
            $end = $data['end'];

            $count = $empruntRepository->countEmpruntsBetweenDates($start, $end);

            // Rendu avec les emprunts filtrés par plage de dates
            return $this->render('emprunt/index.html.twig', [
                'emprunts' => $emprunts,
                'form' => $form->createView(),
                'count' => $count,
            ]);
        }

        // Rendu par défaut avec tous les emprunts
        return $this->render('emprunt/index.html.twig', [
            'emprunts' => $emprunts,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/new', name: 'app_emprunt_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EmpruntRepository $empruntRepository): Response
    {
        $emprunt = new Emprunt();
        $form = $this->createForm(EmpruntType::class, $emprunt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $empruntRepository->save($emprunt, true);

            return $this->redirectToRoute('app_emprunt_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('emprunt/new.html.twig', [
            'emprunt' => $emprunt,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_emprunt_show', methods: ['GET'])]
    public function show(Emprunt $emprunt): Response
    {
        return $this->render('emprunt/show.html.twig', [
            'emprunt' => $emprunt,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_emprunt_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Emprunt $emprunt, EmpruntRepository $empruntRepository): Response
    {
        $form = $this->createForm(EmpruntType::class, $emprunt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $empruntRepository->save($emprunt, true);

            return $this->redirectToRoute('app_emprunt_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('emprunt/edit.html.twig', [
            'emprunt' => $emprunt,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_emprunt_delete', methods: ['POST'])]
    public function delete(Request $request, Emprunt $emprunt, EmpruntRepository $empruntRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emprunt->getId(), $request->request->get('_token'))) {
            $empruntRepository->remove($emprunt, true);
        }

        return $this->redirectToRoute('app_emprunt_index', [], Response::HTTP_SEE_OTHER);
    }
}
