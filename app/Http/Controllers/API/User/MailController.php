<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;

class MailController extends Controller
{
    public function sendOrderConfirmation(Request $request)
    {
        // Xử lý logic đơn hàng ở đây
        $codeBill = $request->code_bill;

        // Gửi email xác nhận
        $userEmail = 'buihuuhau.hb1909@gmail.com'; // Thay bằng địa chỉ email của người dùng đặt hàng
        // $orderData = []; // Thay bằng dữ liệu đơn hàng
        $orderData = $codeBill; // Thay bằng dữ liệu đơn hàng

        Mail::to($userEmail)->send(new OrderConfirmation($orderData));

        return HttpResponse::respondWithSuccess(null, "Gửi mail xác nhận đơn hàng thành công");
    }
}
