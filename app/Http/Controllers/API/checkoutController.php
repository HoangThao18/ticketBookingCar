<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Requests\cancelBooking;
use App\Http\Requests\cancelBookingRequest;
use App\Http\Requests\checkoutRequest;
use App\Repositories\Bill\BillRepositoryInterface;
use App\Repositories\BillDetail\BillDetailRepositoryInterface;
use App\Repositories\Ticket\TicketRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class checkoutController extends Controller
{
    private $ticketRepository;
    private $billRepository;
    private $billDetailRepository;
    public function __construct(TicketRepositoryInterface $ticketRepository, BillRepositoryInterface $billRepository, BillDetailRepositoryInterface $billDetailRepository)
    {
        $this->ticketRepository = $ticketRepository;
        $this->billRepository = $billRepository;
        $this->billDetailRepository = $billDetailRepository;
    }
    public function vnpayPayment(checkoutRequest $request)
    {

        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://localhost/ticketBookingCar/public/api/vnpay-return";
        $vnp_TmnCode = "86HQZ6IJ"; //Mã website tại VNPAY 
        $vnp_HashSecret = "VXFDSCHEORUPTSTIHSKFDTVMTSSYGHDC"; //Chuỗi bí mật

        $vnp_TxnRef = Str::random(10); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = "thanh toán đơn hàng";
        $vnp_OrderType = "billpayment";
        $vnp_Amount = $request->amount * 100;
        $vnp_Locale = "VN";
        $vnp_BankCode = $request->bankCode;
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        $vnp_ExpireDate = Carbon::now()->addMinutes(5)->format('YmdHis');

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

        $this->ticketRepository->updateStatus($request->tickets, "đang thanh toán");
        $dataCreatBill = [
            'user_id' => Auth::id(),
            'payment_method' => "thanh toán online",
            'status' => "đang thanh toán",
            "code" => $vnp_TxnRef
        ];
        $bill = $this->billRepository->create($dataCreatBill);

        $dataCreatBillDetail = [];

        foreach ($request->tickets as $id) {
            $dataCreatBillDetail[] = ['ticket_id' => $id];
        }
        $bill->BillsDetail()->createMany($dataCreatBillDetail);
        return HttpResponse::respondWithSuccess($vnp_Url);
    }

    public function vnpayReturn(Request $request)
    {
        $bill = $this->billRepository->findByCode($request->vnp_TxnRef);
        $ticketIds = $this->billDetailRepository->getTicketIdsByBill($bill->id);
        if ($request->vnp_TransactionStatus == 00) {
            $this->ticketRepository->updateStatus($ticketIds, "đã đặt");
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
            $this->ticketRepository->updateStatus($ticketIds, "còn trống");
            $this->billRepository->delete($bill->id);
            return HttpResponse::respondError("thanh toán thất bại");
        }
    }

    public function cancelBooking(cancelBookingRequest $request)
    {
        try {
            $bill = $this->billRepository->find($request->bill_id);
            $ticketIds = $this->billDetailRepository->getTicketIdsByBill($bill->id);
            $this->billRepository->update($bill->id, ['status' => "đã hủy"]);
            $this->ticketRepository->updateStatus($ticketIds, "còn trống");
            return HttpResponse::respondWithSuccess([], "Hủy vé thành công");
        } catch (\Exception $e) {
            return HttpResponse::respondError($e->getMessage());
        }
    }
}
