<?php

namespace App\Controller;

use App\Entity\Frequency;
use App\Form\FrequencyType;
use App\Repository\FrequencyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/frequency')]
class FrequencyController extends AbstractController
{
    // -----------------------formulaire frequence : valeur actuelle----------------------------------
    #[Route('/', name: 'app_frequency_index', methods: ['GET'])]
    public function index(FrequencyRepository $frequencyRepository): Response
    {
        $oldFrequency = $frequencyRepository->findOneBy(['active' => true]);
        $newFrequency = new Frequency();
        $newFrequency->setValue($oldFrequency->getValue());

        $form = $this->createForm(FrequencyType::class, $newFrequency, [
            'action' => $this->generateUrl('app_frequency_new'),
        ]);

        return $this->render('frequency/index.html.twig', [
            'formFreq' => $form->createView(),
        ]);
    }

    // ---------------------------formulaire frequence : nouvelle valeur---------------------------------
    #[Route('/new', name: 'app_frequency_new', methods: ['POST'])]
    public function new(Request $request, FrequencyRepository $frequencyRepository): Response
    {
        $oldFrequency = $frequencyRepository->findOneBy(['active' => true]);
        $oldFrequency->setActive(false);

        $frequency = new Frequency();
        $frequency->setActive(true);

        $form = $this->createForm(FrequencyType::class, $frequency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $frequencyRepository->add($frequency, true);
        }
        return $this->redirectToRoute('app_frequency_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_frequency_show', methods: ['GET'])]
    public function show(Frequency $frequency): Response
    {
        return $this->render('frequency/show.html.twig', [
            'frequency' => $frequency,
        ]);
    }
}
