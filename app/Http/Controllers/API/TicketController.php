<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Resources\DetailTicketResource;
use App\Http\Resources\TicketResource;
use App\Repositories\Ticket\TicketRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    private $ticketRepository;

    public function __construct(TicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    public function searchByCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => "required"
        ]);

        if ($validator->fails()) {
            return HttpResponse::respondError($validator->errors());
        }

        $ticket = $this->ticketRepository->searchByCode($request->code);

        if ($ticket) {
            return HttpResponse::respondWithSuccess(new DetailTicketResource($ticket));
        }

        return HttpResponse::respondNotFound('ticket not found');
    }
}
