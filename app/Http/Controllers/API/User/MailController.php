<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\Ticket\DetailTicketResource;
use App\Repositories\Bill\BillRepositoryInterface;
use App\Repositories\Seats\SeatsRepositoryInterface;
use App\Repositories\Ticket\TicketRepositoryInterface;
use App\Repositories\Trip\TripRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Mail\OrderConfirmation;
use Nette\Utils\DateTime;

class MailController extends Controller
{
    private $ticketRepository;
    private $tripRepository;
    private $seatRepository;
    private $billRepository;
    private $userRepository;
    public function __construct(
        TicketRepositoryInterface $ticketRepository,
        TripRepositoryInterface $tripRepository,
        SeatsRepositoryInterface $seatRepository,
        BillRepositoryInterface $billRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->ticketRepository = $ticketRepository;
        $this->tripRepository = $tripRepository;
        $this->seatRepository = $seatRepository;
        $this->billRepository = $billRepository;
        $this->userRepository = $userRepository;
    }
    public function sendOrderConfirmation(Request $request)
    {
        // Xử lý logic đơn hàng ở đây
        $codeBill = $request->code_bill;
        $bill = $this->billRepository->findByCode($codeBill);
        $tickets = $this->ticketRepository->getByBill($bill->id);
        $tickets = DetailTicketResource::collection($tickets);
        $trip = $this->tripRepository->find($tickets[0]->trip_id);

        $code_tickets = [];
        $seat_tickets = [];
        $total_price = 0;
        foreach ($tickets as $ticket) {
            array_push($code_tickets, $ticket->code);
            array_push($seat_tickets, $ticket->seat->position);
            $total_price += $ticket->seat->price;
        }
        // Gửi email xác nhận
        $userEmail = $tickets[0]->user->email; // Thay bằng địa chỉ email của người dùng đặt hàng
        $dateTime = new DateTime($trip->departure_time);
        // Lấy ngày
        $departure_day = $dateTime->format('Y-m-d');

        // Lấy giờ
        $departure_time = $dateTime->format('H:i:s');
        $orderData = [
            "mail" => $userEmail,
            "code_bill" => $bill->code,
            "code_tickets" => $code_tickets,
            "name" => $tickets[0]->user->name,
            "phone" => $tickets[0]->user->phone_number,
            "trip" => $trip,
            "quantity" => $tickets->count(),
            "seats" => $seat_tickets,
            "departure_day" => $departure_day,
            "departure_time" => $departure_time,
            "pickup_location" => $tickets[0]->pickup_location,
            "payment_method" => $bill->payment_method,
            "total_price" => $total_price,
        ]; // Thay bằng dữ liệu đơn hàng

        if($bill->status == "đã thanh toán"){
            Mail::to($userEmail)->send(new OrderConfirmation($orderData));
    
            return HttpResponse::respondWithSuccess(($orderData), "Gửi mail xác nhận đơn hàng thành công");
        }
        return HttpResponse::respondError("Đơn hàng chưa thanh toán, Gửi mail thất bại");
    }
}
