<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendMail(Request $request)
    {
        $htmlContent = $request->input('html_content');

        // Gửi mail
        Mail::raw($htmlContent, function ($message) use ($request) {
            $message->to($request->input('to_email'))
                    ->subject($request->input('subject'));
        });

        return HttpResponse::respondWithSuccess(null,"Thanh toán thành công");
    }
}
