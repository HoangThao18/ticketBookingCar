<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\User\SendMail as UserSendMail;
use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Library\SendMailUser;
use App\Http\Requests\cancelBookingRequest;
use App\Http\Requests\checkoutRequest;
use App\Http\Resources\Ticket\DetailTicketResource;
use App\Mail\OrderConfirmation;
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
use GuzzleHttp\Exception\RequestException;

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

    public function create_bill($user, $codeBill, $payment_method = "Thanh toán qua thẻ")
    {
        $bill =  $this->billRepository->create([
            "user_id" => $user->id,
            'payment_method' => $payment_method,
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
                    'role' => "guest"
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
        $vnp_Returnurl = "https://deece.vn/dathanhtoan";
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

            $result = SendMailUser::sendOrderConfirmation($bill, $tickets, $tickets[0]->trip);
            return $result;
        } else {
            $this->ticketRepository->updateStatus($ticketIds, "đã hủy");
            $this->billRepository->update($bill->id, ['status' => "thanh toán thất bại"]);
            return HttpResponse::respondError("thanh toán thất bại");
        }
    }

    public function cancelBooking(cancelBookingRequest $request)
    {
        try {
            $ticket = $this->ticketRepository->find($request->ticket_id);
            $currentTime = date("Y-m-d H:i:s");
            if (!$this->tripRepository->checkCanCancelTicket($ticket->trip_id, $currentTime)) {
                return HttpResponse::respondError("Chuyến xe đã khời hành. Không thể hủy vé");
            }
            $this->ticketRepository->updateStatus($ticket->id, "đã hủy");
            return HttpResponse::respondWithSuccess([], "Hủy vé thành công");
        } catch (\Exception $e) {
            return HttpResponse::respondError($e->getMessage());
        }
    }

    // bank checkout
    function getBankQR(Request $request)
    {
        try {
            $trip = $this->tripRepository->findNotAssociateColumn($request->trip_id);
            $seats = $this->seatRepository->getByCar($trip->car_id);
            $codeBill = hash('sha256', microtime() . mt_rand());
            $codeBill = substr($codeBill, 0, 15);
            $total = 0;
            $ticketsCreate = [];
            // return response()->json(['message' => $request->all()]);
            $arrayUniqueSeatId =  array_unique($request->seat_id);
            $user = $this->userRepository->getByEmail($request->email);


            if ($user) {
                foreach ($arrayUniqueSeatId as $seatId) {
                    $isValid = $this->ticketRepository->searchByTripAndSeat($request->trip_id, $seatId, 'waiting');
                    if ($isValid !== null && $isValid->status !== 'cancelled') {
                        return HttpResponse::respondNotFound("Seat id " . $seatId . " on trip id " . $request->trip_id . " is invalid");
                    }
                }
                $bill = $this->create_bill($user, $codeBill, "Chuyển khoản ngân hàng");
            } else {
                $user = $this->userRepository->getByPhone($request->phone_number);
                if ($user) {
                    foreach ($arrayUniqueSeatId as $seatId) {
                        $isValid = $this->ticketRepository->searchByTripAndSeat($request->trip_id, $seatId, 'waiting');
                        if ($isValid !== null && $isValid->status !== 'cancelled') {
                            return HttpResponse::respondNotFound("Seat id " . $seatId . " on trip id " . $request->trip_id . " is invalid");
                        }
                    }
                    $bill = $this->create_bill($user, $codeBill, "Chuyển khoản ngân hàng");
                } else {
                    foreach ($arrayUniqueSeatId as $seatId) {
                        $isValid = $this->ticketRepository->searchByTripAndSeat($request->trip_id, $seatId, 'waiting');
                        if ($isValid !== null && $isValid->status !== 'cancelled') {
                            return HttpResponse::respondNotFound("Seat id " . $seatId . " on trip id " . $request->trip_id . " is invalid");
                        }
                    }
                    $user = $this->userRepository->create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'phone_number' => $request->phone_number,
                        'role' => "guest"
                    ]);
                    $bill = $this->create_bill($user, $codeBill, "Chuyển khoản ngân hàng");
                }
            }

            foreach ($arrayUniqueSeatId as $seatId) {
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
            foreach ($ticketsCreate as $ticket) {
                $this->ticketRepository->create($ticket);
            }

            // $total = 10000;

            $momo = [
                "qr" => "<img src=" . "'https://api.vietqr.io/image/963388-0377457747-J1SFeJT.jpg?accountName=BUI%20HUU%20HAU&amount=$total&addInfo=$codeBill'" . "/>",
                "chu_tai_khoan" => "Bui Huu Hau",
                "so_tai_khoan" => "0377457747",
                "ngan_hang" => "Timo",
                "noi_dung" => $codeBill,
                "so_tien" => $total,
                "to_time" => time() + 300,
            ];

            return HttpResponse::respondWithSuccess($momo);
        } catch (RequestException $e) {
            // Xử lý exception
            return "Có lỗi xảy ra: " . $e->getMessage();
        }
    }
    public function bankReturn(Request $request)
    {
        $codeBill = $request->code_bill;
        $bill = $this->billRepository->findByCode($codeBill);
        $tickets = $this->ticketRepository->getByBill($bill->id);
        if ($bill->status == 'đã thanh toán') {
            $responseData = [
                'bill' => [
                    'code' => $bill->code,
                    "total" => $request->vnp_Amount / 100,
                    "message" => "Thanh toán hóa đơn qua Bank",
                    "tickets" => DetailTicketResource::collection($tickets)
                ]
            ];
            SendMailUser::sendOrderConfirmation($bill, $tickets, $tickets[0]->trip);
            return HttpResponse::respondWithSuccess($responseData, "Thanh toán và gửi Mail thành công");
        } else {
            return HttpResponse::respondError("thanh toán thất bại");
        }
    }
}
