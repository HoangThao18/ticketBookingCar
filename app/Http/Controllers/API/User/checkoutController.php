<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Requests\cancelBooking;
use App\Http\Requests\cancelBookingRequest;
use App\Http\Requests\checkoutRequest;
use App\Http\Resources\Ticket\DetailTicketResource;
use App\Notifications\BillPaid;
use App\Repositories\Bill\BillRepositoryInterface;
use App\Repositories\Seats\SeatsRepositoryInterface;
use App\Repositories\Ticket\TicketRepositoryInterface;
use App\Repositories\Trip\TripRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class checkoutController extends Controller
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

    public function create_bill($user, $codeBill)
    {
        $bill =  $this->billRepository->create([
            "user_id" => $user->id,
            'payment_method' => "thanh toán online",
            'status' => "pending",
            "code" => $codeBill
        ]);

        return $bill;
    }

    public function vnpayPayment(checkoutRequest $request)
    {

        $trip = $this->tripRepository->findNotAssociateColumn($request->trip_id);
        $seats = $this->seatRepository->getByCar($trip->car_id);
        $codeBill = Str::random(10);
        $total = 0;
        $ticketsCreate = [];
        $arrayUniqueSeatId =  array_unique($request->seat_id);
        $user = $this->userRepository->getByEmail($request->email);

        if ($user) {
            $bill = $this->create_bill($user, $codeBill);
        } else {
            $user = $this->userRepository->getByPhone($request->phone_number);
            if ($user) {
                $bill = $this->create_bill($user, $codeBill);
            } else {

                $user = $this->userRepository->create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                ]);
                $bill = $this->create_bill($user, $codeBill);
            }
        }

        foreach ($arrayUniqueSeatId as  $seatId) {
            $isValid = $seats->contains('id', $seatId);

            if (!$isValid) {
                return HttpResponse::respondNotFound("seat is invalid");
            }

            $seat = $seats->find($seatId);
            $total += $seat->price;
            $ticketsCreate[] =
                [
                    "trip_id" => $trip->id,
                    "seat_id" => $seatId,
                    "user_id" => $user->id,
                    "code" =>  Str::random(10),
                    "price" => $seat->price,
                    "bill_id" => $bill->id,
                    'pickup_location' => $request->pickup_location,
                    'dropoff_location' => $request->dropoff_location,
                    "status" => "pending",
                ];
        }
        foreach ($ticketsCreate as  $ticket) {
            $this->ticketRepository->create($ticket);
        }
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://127.0.0.1:8000/api/vnpay-return";
        $vnp_TmnCode = "86HQZ6IJ"; //Mã website tại VNPAY 
        $vnp_HashSecret = "VXFDSCHEORUPTSTIHSKFDTVMTSSYGHDC"; //Chuỗi bí mật

        $vnp_TxnRef = $codeBill; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = "thanh toán đơn hàng";
        $vnp_OrderType = "billpayment";
        $vnp_Amount = $total * 100;
        $vnp_Locale = "VN";
        $vnp_BankCode = "";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        $vnp_ExpireDate = Carbon::now()->addMinutes(10)->format('YmdHis');

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $vnp_ExpireDate,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return HttpResponse::respondWithSuccess($vnp_Url);
    }

    public function vnpayReturn(Request $request)
    {
        $bill = $this->billRepository->findByCode($request->vnp_TxnRef);
        $tickets = $this->ticketRepository->getByBill($bill->id);
        $ticketIds = array_column($tickets->toArray(), 'id');
        if ($request->vnp_TransactionStatus == 00) {
            $this->ticketRepository->updateStatus($ticketIds, "booked");
            $this->billRepository->update($bill->id, ['status' => "đã thanh toán"]);
            $bill->user->notify(new BillPaid($request->vnp_Amount / 100, $bill->code));
            $responseData = [
                'bill' => [
                    'code' => $bill->code,
                    "total" => $request->vnp_Amount / 100,
                    "message" => "thanh toán hóa đơn",
                    "tickets" => DetailTicketResource::collection($tickets)
                ]
            ];

            return HttpResponse::respondWithSuccess($responseData, "Giao dịch được thực hiện thành công");
        } else {
            $this->ticketRepository->updateStatus($ticketIds, "đã hủy");
            $this->billRepository->update($bill->id, ['status' => "thanh toán thất bại"]);
            return HttpResponse::respondError("thanh toán thất bại");
        }
    }

    public function cancelBooking(cancelBookingRequest $request)
    {
        try {
            $bill = $this->billRepository->find($request->bill_id);
            $tickets = $this->ticketRepository->getByBill($bill->id)->toArray();
            $ticketIds = array_column($tickets, 'id');
            $this->billRepository->update($bill->id, ['status' => "đã hủy"]);
            $this->ticketRepository->updateStatus($ticketIds, "đã hủy");
            return HttpResponse::respondWithSuccess([], "Hủy vé thành công");
        } catch (\Exception $e) {
            return HttpResponse::respondError($e->getMessage());
        }
    }
}
