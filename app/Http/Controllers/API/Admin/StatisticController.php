<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Models\Comment;
use App\Repositories\Car\CarRepositoryInterface;
use App\Repositories\Comments\CommentsRepositoryInterface;
use App\Repositories\Ticket\TicketRepository;
use App\Repositories\Ticket\TicketRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    private $ticketRepository;
    private $userRepository;
    private $carRepository;
    private $commentRepository;

    public function __construct(TicketRepositoryInterface $ticketRepository, CommentsRepositoryInterface $commentsRepository, CarRepositoryInterface $carRepository, UserRepositoryInterface $userRepository)
    {
        $this->ticketRepository = $ticketRepository;
        $this->userRepository = $userRepository;
        $this->carRepository = $carRepository;
        $this->commentRepository = $commentsRepository;
    }

    public function index()
    {
        $dailyTicketsBook = $this->ticketRepository->getDailyTicketBooked();
        $weeklyTicketsBook = $this->ticketRepository->getWeeklyTicketBooked();
        $monthlyTicketsBook = $this->ticketRepository->getMonthlyTicketBooked();

        $dailySales = $this->ticketRepository->getDailySales();
        $weeklySales = $this->ticketRepository->getWeeklySales();
        $monthlySales = $this->ticketRepository->getMonthlySales();

        $totalDriver = $this->userRepository->getTotalDriver();
        $totalCar = $this->carRepository->getTotalCar();
        $totalComment = $this->commentRepository->getTotalComments();

        $ticketsBookedIn12 = $this->ticketRepository->getTicketsBookedIn12();


        // Tạo một mảng chứa các tháng trong khoảng thời gian cần thống kê
        $monthsToCheck = [];
        $currentMonth = now();
        for ($i = 1; $i <= 6; $i++) {
            $monthsToCheck[$currentMonth->format('Y-m')] = 0;
            $currentMonth = $currentMonth->subMonth();
        }

        foreach ($ticketsBookedIn12 as $ticketCount) {
            $year = $ticketCount->year;
            $month = $ticketCount->month;
            $total = $ticketCount->total;
            $monthsToCheck["$year-$month"] = $total;
        }

        return HttpResponse::respondWithSuccess([
            'tickets' => [
                'dailyBooked' => $dailyTicketsBook,
                'weeklyBooked' => $weeklyTicketsBook,
                'monthlyBooked' => $monthlyTicketsBook
            ],
            "tickets_sale_in_6_month" => $monthsToCheck,
            'revenue' => [
                'daily' => $dailySales,
                'weekly' => $weeklySales,
                'monthly' => $monthlySales
            ],
            'comment' => [
                'total' => $totalComment
            ],
            'driver' => [
                'total' => $totalDriver
            ],
            "car" => [
                'total' => $totalCar
            ]
        ]);
    }
}
