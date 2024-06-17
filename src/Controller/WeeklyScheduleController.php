<?php

namespace App\Controller;

use App\Entity\WeeklySchedule;
use App\Form\WeeklyScheduleType;
use App\Repository\WeeklyScheduleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/weekly-schedule')]
class WeeklyScheduleController extends AbstractController
{
    
    // ------------------------affichage formulaire horaires hebdomadaires-------------------------------
    #[Route('/', name: 'app_weekly_schedule_index', methods: ['GET'])]
    public function index(WeeklyScheduleRepository $weeklyScheduleRepository): Response
    {
        $weeklySchedules = $weeklyScheduleRepository->findAll();
        $form = $this->createForm(WeeklyScheduleType::class, [], 
        [
            'data' => 
            [
                'weekly_schedule' => $weeklySchedules
            ],
            'action' => $this->generateUrl('app_weekly_schedule_edit'),
        ]);

        return $this->render('weekly_schedule/index.html.twig', 
        [
            'form' => $form->createView(),
            'weekly_schedules' => $weeklySchedules,
        ]);
    }

    // ---------------------------------modifier horaires hebdomadaires-------------------------------------
    #[Route('/edit', name: 'app_weekly_schedule_edit', methods: ['POST'])]
    public function edit(Request $request, WeeklyScheduleRepository $weeklyScheduleRepository, ManagerRegistry $doctrine): Response
    {
        $weeklySchedules = $weeklyScheduleRepository->findAll();
        $form = $this->createForm(WeeklyScheduleType::class, [], 
        [
            'data' => 
            [
                'weekly_schedule' => $weeklySchedules
            ]
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $data = $form->getData();
            foreach ($weeklySchedules as $weeklySchedule) 
            {
                $weeklySchedule->setMorningOpening($data['morningOpening'.$weeklySchedule->getId()])
                               ->setMorningClosing($data['morningClosing'.$weeklySchedule->getId()])
                               ->setEveningOpening($data['eveningOpening'.$weeklySchedule->getId()])
                               ->setEveningClosing($data['eveningClosing'.$weeklySchedule->getId()]);
            }
            $doctrine->getManager()->flush();
        }
        return $this->redirectToRoute('app_weekly_schedule_index', [], Response::HTTP_SEE_OTHER);
    }
}
