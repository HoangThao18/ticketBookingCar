<?php

namespace App\Http\Controllers\API\User;


use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Resources\Ticket\DetailTicketResource;
use App\Http\Resources\Ticket\TicketBookedResource;
use App\Repositories\Ticket\TicketRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    private $ticketRepository;

    public function __construct(TicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    public function searchByCode(String $code)
    {
        $ticket = $this->ticketRepository->searchByCode($code);
        if ($ticket) {
            return HttpResponse::respondWithSuccess(new DetailTicketResource($ticket));
        }
        return HttpResponse::respondNotFound('ticket not found');
    }

    public function getHistory()
    {
        $data = $this->ticketRepository->getHistoryBooked(Auth::id());
        return HttpResponse::respondWithSuccess(TicketBookedResource::collection($data));
    }
}
