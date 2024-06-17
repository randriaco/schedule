<?php

namespace App\Controller;

use App\Entity\MultiDayClosing;
use App\Form\MultiDayClosingType;
use App\Repository\MultiDayClosingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/multi-day-closing')]
class MultiDayClosingController extends AbstractController
{
    #[Route('/', name: 'app_multi_day_closing_index', methods: ['GET'])]
    public function index(MultiDayClosingRepository $multiDayClosingRepository): Response
    {
        $multiDayClosing = new MultiDayClosing();
        $newForm = $this->createForm(MultiDayClosingType::class, $multiDayClosing, [
            'action' => $this->generateUrl('app_multi_day_closing_new'),
        ]);

        $multiDayClosings = $multiDayClosingRepository->findAll();
        $multiDayClosingForms = [];
        foreach ($multiDayClosings as $multiDayClosing) {
            $multiDayClosingForms[] = $this->createForm(multiDayClosingType::class, $multiDayClosing, [
                'action' => $this->generateUrl('app_multi_day_closing_edit', ['id' => $multiDayClosing->getId()]),
            ])->createView();
        }

        return $this->render('multi_day_closing/index.html.twig', [
            'new_form' => $newForm->createView(),
            'multi_day_closing_forms' => $multiDayClosingForms,
        ]);
    }

    #[Route('/new', name: 'app_multi_day_closing_new', methods: ['POST'])]
    public function new(Request $request, MultiDayClosingRepository $multiDayClosingRepository): Response
    {
        $multiDayClosing = new MultiDayClosing();
        $form = $this->createForm(MultiDayClosingType::class, $multiDayClosing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $multiDayClosingRepository->add($multiDayClosing, true);

        }
        return $this->redirectToRoute('app_multi_day_closing_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit', name: 'app_multi_day_closing_edit', methods: ['POST'])]
    public function edit(Request $request, MultiDayClosing $multiDayClosing, MultiDayClosingRepository $multiDayClosingRepository): Response
    {
        $form = $this->createForm(MultiDayClosingType::class, $multiDayClosing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $multiDayClosingRepository->add($multiDayClosing, true);

        }
        return $this->redirectToRoute('app_multi_day_closing_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_multi_day_closing_delete', methods: ['POST'])]
    public function delete(Request $request, MultiDayClosing $multiDayClosing, MultiDayClosingRepository $multiDayClosingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$multiDayClosing->getId(), $request->request->get('_token'))) {
            $multiDayClosingRepository->remove($multiDayClosing, true);
        }

        return $this->redirectToRoute('app_multi_day_closing_index', [], Response::HTTP_SEE_OTHER);
    }
}
