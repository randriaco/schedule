<?php

namespace App\Controller;

use App\Entity\DailyClosing;
use App\Form\DailyClosingType;
use App\Repository\DailyClosingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/daily-closing')]
class DailyClosingController extends AbstractController
{
    #[Route('/', name: 'app_daily_closing_index', methods: ['GET'])]
    public function index(DailyClosingRepository $dailyClosingRepository): Response
    {
        $dailyClosing = new DailyClosing();
        $newForm = $this->createForm(DailyClosingType::class, $dailyClosing, [
            'action' => $this->generateUrl('app_daily_closing_new'),
        ]);

        $dailyClosings = $dailyClosingRepository->findAll();
        $dailyClosingForms = [];
        foreach ($dailyClosings as $dailyClosing) {
            $dailyClosingForms[] = $this->createForm(DailyClosingType::class, $dailyClosing, [
                'action' => $this->generateUrl('app_daily_closing_edit', ['id' => $dailyClosing->getId()]),
            ])->createView();
        }
        
        return $this->render('daily_closing/index.html.twig', [
            'new_form' => $newForm->createView(),
            'daily_closing_forms' => $dailyClosingForms,
        ]);
    }

    #[Route('/new', name: 'app_daily_closing_new', methods: ['POST'])]
    public function new(Request $request, DailyClosingRepository $dailyClosingRepository): Response
    {
        $dailyClosing = new DailyClosing();
        $form = $this->createForm(DailyClosingType::class, $dailyClosing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dailyClosingRepository->add($dailyClosing, true);
        }
        return $this->redirectToRoute('app_daily_closing_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit', name: 'app_daily_closing_edit', methods: ['POST'])]
    public function edit(Request $request, DailyClosing $dailyClosing, DailyClosingRepository $dailyClosingRepository): Response
    {
        $form = $this->createForm(DailyClosingType::class, $dailyClosing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dailyClosingRepository->add($dailyClosing, true);
        }
        return $this->redirectToRoute('app_daily_closing_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_daily_closing_delete', methods: ['POST'])]
    public function delete(Request $request, DailyClosing $dailyClosing, DailyClosingRepository $dailyClosingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dailyClosing->getId(), $request->request->get('_token'))) {
            $dailyClosingRepository->remove($dailyClosing, true);
        }

        return $this->redirectToRoute('app_daily_closing_index', [], Response::HTTP_SEE_OTHER);
    }
}
