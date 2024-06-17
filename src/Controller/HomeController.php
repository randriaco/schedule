<?php

namespace App\Controller;

use DateTime;
use App\Entity\Booking;
use App\Service\ScheduleService;
use App\Repository\RestoRepository;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ScheduleService $scheduleService, Request $request): Response
    {
        $pageNumber = 0;
        if ($request->query->get('page_number')) {
            $pageNumber = $request->query->get('page_number');
        }
        $schedule = $scheduleService->getSchedule($pageNumber);
        
        return $this->render('home/index.html.twig', [
            'days' => $schedule,
            'page_number' => $pageNumber,
        ]);
    }

    #[Route('/booking', name: 'app_booking')]
    public function booking(Request $request, BookingRepository $bookingRepository): Response
    {
        $date = $request->query->get('time_slot');
        $booking = new Booking();
        $booking->setTimeBooked(new DateTime($date));

        $bookingRepository->add($booking, true);
        return $this->redirectToRoute('app_booking_success', ['id' => $booking->getId()], Response::HTTP_SEE_OTHER);

        return $this->render('home/index.html.twig', [
            'booking' => $booking,
        ]);
    }

    #[Route('/booking-success/{id}', name: 'app_booking_success')]
    public function bookingSuccess(Booking $booking): Response
    {
        return $this->render('home/booking-success.html.twig', [
            'booking' => $booking,
        ]);
    }

    #[Route('/restaurants', name: 'restaurants_list')]
    public function listRestaurants(RestoRepository $restoRepository): Response
    {
        $restaurants = $restoRepository->findBy([], ['nom' => 'ASC']);

        return $this->render('home/list.html.twig', [
            'restaurants' => $restaurants,
        ]);
    }
}
