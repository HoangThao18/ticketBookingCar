<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Requests\cancelBooking;
use App\Http\Requests\cancelBookingRequest;
use App\Http\Requests\checkoutRequest;
use App\Repositories\Bill\BillRepositoryInterface;
use App\Repositories\Seats\SeatsRepositoryInterface;
use App\Repositories\Ticket\TicketRepositoryInterface;
use App\Repositories\Trip\TripRepositoryInterface;
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
    public function __construct(
        TicketRepositoryInterface $ticketRepository,
        TripRepositoryInterface $tripRepository,
        SeatsRepositoryInterface $seatRepository,
        BillRepositoryInterface $billRepository
    ) {
        $this->ticketRepository = $ticketRepository;
        $this->tripRepository = $tripRepository;
        $this->seatRepository = $seatRepository;
        $this->billRepository = $billRepository;
    }
    public function vnpayPayment(checkoutRequest $request)
    {

        $trip = $this->tripRepository->findNotAssociateColumn($request->trip_id);
        $seats = $this->seatRepository->getByCar($trip->car_id);
        $codeBill = Str::random(10);
        $total = 0;
        $ticketsCreate = [];
        $arrayUniqueSeatId =  array_unique($request->seat_id);

        $bill = $this->billRepository->create([
            "user_id" => Auth::id(),
            'payment_method' => "thanh toán online",
            'status' => "pending",
            "code" => $codeBill
        ]);
        foreach ($arrayUniqueSeatId as $seatId) {
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
                    "user_id" => Auth::id(),
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
        $vnp_Returnurl = "http://localhost/ticketBookingCar/public/api/vnpay-return";
        $vnp_TmnCode = "86HQZ6IJ"; //Mã website tại VNPAY 
        $vnp_HashSecret = "VXFDSCHEORUPTSTIHSKFDTVMTSSYGHDC"; //Chuỗi bí mật

        $vnp_TxnRef = $codeBill; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = "thanh toán đơn hàng";
        $vnp_OrderType = "billpayment";
        $vnp_Amount = $total * 100;
        $vnp_Locale = "VN";
        $vnp_BankCode = "";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        $vnp_ExpireDate = Carbon::now()->addMinutes(1)->format('YmdHis');

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
        $tickets = $this->ticketRepository->getByBill($bill->id)->toArray();
        $ticketIds = array_column($tickets, 'id');
        if ($request->vnp_TransactionStatus == 00) {
            $this->ticketRepository->updateStatus($ticketIds, "booked");
            $this->billRepository->update($bill->id, ['status' => "đã thanh toán"]);
            return HttpResponse::respondWithSuccess([
                'bill' => [
                    'code' => $bill->code,
                    "amount" => $request->vnp_Amount,
                    "content" => "thanh toán hóa đơn",
                    "backCode" => $request->vnp_BankCode,
                ]
            ], "Giao dịch được thực hiện thành công");
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

    // Thanh toán Momo
    function getMomoQR(Request $request)
    {
        $trip = $this->tripRepository->findNotAssociateColumn($request->trip_id);
        $seats = $this->seatRepository->getByCar($trip->car_id);
        $codeBill = hash('sha256', microtime() . mt_rand());
        $codeBill = substr($codeBill, 0, 15);
        $total = 0;
        $ticketsCreate = [];
        // return response()->json(['message' => $request->all()]);
        $arrayUniqueSeatId =  array_unique($request->seat_id);

        $bill = $this->billRepository->create([
            "user_id" => Auth::id(),
            'payment_method' => "Thanh toán MoMo",
            'status' => "pending",
            "code" => $codeBill
        ]);
        foreach ($arrayUniqueSeatId as $seatId) {
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
                    "user_id" => Auth::id(),
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

        $qr = "2|99|0886543301|||0|0|$total|$codeBill|transfer_p2p";
        $momo = [
            "qr" => "<img src=" . "https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl==$qr;&choe=UTF-8" . "/>",
            "chu_tai_khoan" => "Bui Huu Hau",
            "so_dien_thoai" => "0886543301",
            "noi_dung" => $codeBill,
            "so_tien" => $total,
            "to_time" => time() + 300,
        ];

        return HttpResponse::respondWithSuccess($momo);
    }

    public function momoReturn(Request $request)
    {
        $codeBill = $request->code_bill;
        $amount = $request->amount;
        $data = config('listmomo.listMomo');
        $data = json_decode($data);
        $codeBill = $this->billRepository->findByCode($codeBill);
        $tickets = $this->ticketRepository->getByBill($codeBill->id)->toArray();
        $ticketIds = array_column($tickets, 'id');
        // $data = $data->message->data->notifications;
        foreach ($data as $key => $value) {
            if ($value->type == 77) {
                $extra = json_decode($value->extra);
                if ($extra->comment == $codeBill->code && $extra->amount >= $amount) {
                    $this->ticketRepository->updateStatus($ticketIds, "booked");
                    $this->billRepository->update($codeBill->id, ['status' => "đã thanh toán"]);
                    return HttpResponse::respondWithSuccess([
                        'bill' => [
                            'code' => $codeBill->code,
                            "amount" => $amount,
                            "content" => "Thanh toán hóa đơn qua Momo",
                            "backCode" => "",
                        ]
                    ], "Giao dịch được thực hiện thành công");
                }
            }
        }
        $this->ticketRepository->updateStatus($ticketIds, "đã hủy");
        $this->billRepository->update($codeBill->id, ['status' => "thanh toán thất bại"]);
        return HttpResponse::respondError("thanh toán thất bại");
    }

    // Thanh toán Bank
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

            foreach ($arrayUniqueSeatId as $seatId) {
                $isValid = $this->ticketRepository->searchByTripAndSeat($request->trip_id,$seatId);
                if ($isValid !== null && $isValid->status !== 'cancelled') {
                    return HttpResponse::respondNotFound("Seat id " . $seatId . " on trip id " . $request->trip_id . " is invalid");
                }
            }

            $bill = $this->billRepository->create([
                "user_id" => Auth::id(),
                'payment_method' => "Chuyển khoản ngân hàng",
                'status' => "pending",
                "code" => $codeBill
            ]);

            foreach ($arrayUniqueSeatId as $seatId) {
                $seat = $seats->find($seatId);
                $total += $seat->price;
                $ticketsCreate[] =
                    [
                        "trip_id" => $trip->id,
                        "seat_id" => $seatId,
                        "user_id" => Auth::id(),
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

            $total = 10000;

            $momo = [
                "qr" => "<img src=" . "'https://api.vietqr.io/image/963388-0377457747-Tcntxkf.jpg?accountName=BUI%20HUU%20HAU&amount=$total&addInfo=$codeBill'" . "/>",
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
        if($bill->status == 'đã thanh toán'){
            return HttpResponse::respondWithSuccess($bill,"Thanh toán thành công");
        } else{
            return HttpResponse::respondError("thanh toán thất bại");
        }
    }
}
