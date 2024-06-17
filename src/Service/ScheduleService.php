<?php

namespace App\Service;

use App\Entity\DailyClosing;
use App\Entity\WeeklySchedule;
use App\Repository\DailyClosingRepository;
use App\Repository\FrequencyRepository;
use App\Repository\MultiDayClosingRepository;
use App\Repository\WeeklyScheduleRepository;
use DateInterval;
use DatePeriod;
use DateTime;

/**
 * Class ScheduleService
 */
class ScheduleService
{

    /**
     * @var WeeklyScheduleRepository
     */
    private WeeklyScheduleRepository $weeklyScheduleRepository;

    /**
     * @var MultiDayClosingRepository
     */
    private MultiDayClosingRepository $multiDayClosingRepository;

    /**
     * @var DailyClosingRepository
     */
    private DailyClosingRepository $dailyClosingRepository;

    /**
     * @var FrequencyRepository
     */
    private FrequencyRepository $frequencyRepository;

    public function __construct(
        WeeklyScheduleRepository $weeklyScheduleRepository,
        MultiDayClosingRepository $multiDayClosingRepository,
        DailyClosingRepository $dailyClosingRepository,
        FrequencyRepository $frequencyRepository,
    ) {
        $this->weeklyScheduleRepository = $weeklyScheduleRepository;
        $this->multiDayClosingRepository = $multiDayClosingRepository;
        $this->dailyClosingRepository = $dailyClosingRepository;
        $this->frequencyRepository = $frequencyRepository;
    }

    /**
     * @return array
     */
    public function getSchedule(int $week = 0): array
    {
        $weeklySchedules = $this->weeklyScheduleRepository->findAll();

        $schedule = [];

        foreach ($weeklySchedules as $weeklySchedule) 
        {
            $date = new DateTime($weeklySchedule->getDayEnglish() . ' +' . $week . ' week');
            $time = $date->getTimestamp();

            $schedule[$time]['day'] = $weeklySchedule->getDay();
            $schedule[$time]['date'] = $date;

            $multipleClosingDay = $this->multiDayClosingRepository->findMultiDayClosingByDate($date);
            if ($multipleClosingDay) 
            {
                $schedule[$time]['is_off'] = true;
                $schedule[$time]['time_slots'] = [];
                continue;
            } 
            else 
            {
                $schedule[$time]['is_off'] = false;
            }

            //check if it's day off
            if (
                !$weeklySchedule->getMorningOpening()
                && !$weeklySchedule->getMorningClosing()
                && !$weeklySchedule->getEveningOpening()
                && !$weeklySchedule->getEveningClosing()
            ) 
            {
                $schedule[$time]['is_off'] = true;
                $schedule[$time]['time_slots'] = [];
                continue;
            }

            $dailyClosing = $this->dailyClosingRepository->findOneBy(['closingDate' => $date]);
            if ($dailyClosing instanceof DailyClosing) 
            {
                $timeSlots = $this->getTimeSlotsByDailyClosing($dailyClosing, $date);
                $schedule[$time]['time_slots'] = $timeSlots;
                if (empty($timeSlots)) 
                {
                    $schedule[$time]['is_off'] = true;
                }
                continue;
            } 
            else 
            {
                $timeSlots = $this->getTimeSlotsByWeeklySchedule($weeklySchedule, $date);
                $schedule[$time]['time_slots'] = $timeSlots;
                if (empty($timeSlots)) 
                {
                    $schedule[$time]['is_off'] = true;
                }
            }
        }
        ksort($schedule);

        return $schedule;
    }

    /**
     * @param DailyClosing $dailyClosing
     * @param DateTime $date
     * @return array $timeSlots
     */
    public function getTimeSlotsByDailyClosing(DailyClosing $dailyClosing, DateTime $date): array
    {
        $today = false;
        $now = new DateTime();

        if ($date->format('Y-m-d') == $now->format('Y-m-d')) 
        {
            $today = true;
        }

        $intervals = [];
        $timeSlots = [];

        if (
            !$dailyClosing->getMorningOpening()
            && !$dailyClosing->getMorningClosing()
            && !$dailyClosing->getEveningOpening()
            && !$dailyClosing->getEveningClosing()
        ) 
        {
            return [];
        }

        if (
            !$dailyClosing->getMorningClosing()
            && !$dailyClosing->getEveningOpening()

        ) 
        {
            $intervals = 
            [
                [
                    'begin' => $dailyClosing->getMorningOpening(),
                    'end' => $dailyClosing->getEveningClosing(),
                ]
            ];
        }

        if (
            $dailyClosing->getMorningOpening()
            && $dailyClosing->getMorningClosing()
            && !$dailyClosing->getEveningOpening()
            && !$dailyClosing->getEveningClosing()
        ) 
        {
            $intervals = 
            [
                [
                    'begin' => $dailyClosing->getMorningOpening(),
                    'end' => $dailyClosing->getMorningClosing(),
                ]
            ];
        }

        if (
            !$dailyClosing->getMorningOpening()
            && !$dailyClosing->getMorningClosing()
            && $dailyClosing->getEveningOpening()
            && $dailyClosing->getEveningClosing()

        ) 
        {
            $intervals = 
            [
                [
                    'begin' => $dailyClosing->getEveningOpening(),
                    'end' => $dailyClosing->getEveningClosing(),
                ]
            ];
        }

        if (
            $dailyClosing->getMorningOpening()
            && $dailyClosing->getMorningClosing()
            && $dailyClosing->getEveningOpening()
            && $dailyClosing->getEveningClosing()

        ) 
        {
            $intervals = 
            [
                [
                    'begin' => $dailyClosing->getMorningOpening(),
                    'end' => $dailyClosing->getMorningClosing(),
                ],
                [
                    'begin' => $dailyClosing->getEveningOpening(),
                    'end' => $dailyClosing->getEveningClosing(),
                ]
            ];
        }

        foreach ($intervals as $interval) 
        {
            if ($interval['begin'] && $interval['end']) 
            {
                $timeSlots = array_merge($this->getTimeSlotsBetweenTwoIntervals($interval['begin'], $interval['end'], $today), $timeSlots);
            }
        }

        return $timeSlots;
    }

    /**
     * @param WeeklySchedule $weeklySchedule
     * @param DateTime $date
     * @return array $timeSlots
     */
    public function getTimeSlotsByWeeklySchedule(WeeklySchedule $weeklySchedule, DateTime $date): array
    {
        $today = false;
        $now = new DateTime();

        if ($date->format('Y-m-d') == $now->format('Y-m-d')) 
        {
            $today = true;
        }
        $intervals = [];
        $timeSlots = [];

        if (
            !$weeklySchedule->getMorningClosing()
            && !$weeklySchedule->getEveningOpening()

        ) 
        {
            $intervals = 
            [
                [
                    'begin' => $weeklySchedule->getMorningOpening(),
                    'end' => $weeklySchedule->getEveningClosing(),
                ]
            ];
        }

        if (
            $weeklySchedule->getMorningOpening()
            && $weeklySchedule->getMorningClosing()
            && !$weeklySchedule->getEveningOpening()
            && !$weeklySchedule->getEveningClosing()
        ) 
        {
            $intervals = 
            [
                [
                    'begin' => $weeklySchedule->getMorningOpening(),
                    'end' => $weeklySchedule->getMorningClosing(),
                ]
            ];
        }

        if (
            !$weeklySchedule->getMorningOpening()
            && !$weeklySchedule->getMorningClosing()
            && $weeklySchedule->getEveningOpening()
            && $weeklySchedule->getEveningClosing()
        ) 
        {
            $intervals = 
            [
                [
                    'begin' => $weeklySchedule->getEveningOpening(),
                    'end' => $weeklySchedule->getEveningClosing(),
                ]
            ];
        }

        if (
            $weeklySchedule->getMorningOpening()
            && $weeklySchedule->getMorningClosing()
            && $weeklySchedule->getEveningOpening()
            && $weeklySchedule->getEveningClosing()

        ) 
        {
            $intervals = 
            [
                [
                    'begin' => $weeklySchedule->getMorningOpening(),
                    'end' => $weeklySchedule->getMorningClosing(),
                ],
                [
                    'begin' => $weeklySchedule->getEveningOpening(),
                    'end' => $weeklySchedule->getEveningClosing(),
                ]
            ];
        }

        foreach ($intervals as $interval) {
            if ($interval['begin'] && $interval['end']) 
            {
                $timeSlots = array_merge($this->getTimeSlotsBetweenTwoIntervals($interval['begin'], $interval['end'], $today), $timeSlots);
            }
        }
        sort($timeSlots);

        return $timeSlots;
    }

    /**
     * @param string $begin
     * @param string $end
     * @return array $timeSlots
     */
    public function getTimeSlotsBetweenTwoIntervals(string $begin, string $end, bool $today): array
    {
        $frequency = $this->frequencyRepository->findOneBy(['active' => true]);
        $timeSlots = [];

        $start = new DateTime($begin);
        $finish = new DateTime($end);

        $interval = DateInterval::createFromDateString($frequency->getValue() . ' minutes');
        $daterange = new DatePeriod($start, $interval, $finish);

        foreach ($daterange as $date1) 
        {
            if ($today) 
            {
                if ($date1 < new DateTime('now')) 
                {
                    continue;
                }
            }

            $timeSlots[$date1->getTimeStamp()] = $date1->format('H:i');
        }
        return $timeSlots;
    }
}
