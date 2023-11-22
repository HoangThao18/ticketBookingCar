<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Resources\Admin\ticket\AdminDetailTicketResource;
use App\Http\Resources\Admin\Ticket\AdminTicketResource;
use App\Repositories\Ticket\TicketRepositoryInterface;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    private $ticketRepository;

    public function __construct(TicketRepositoryInterface $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    public function index()
    {
        $tickets = $this->ticketRepository->getAll();
        return HttpResponse::respondWithSuccess(AdminTicketResource::collection($tickets));
    }

    public function show($id)
    {
        $ticket = $this->ticketRepository->find($id);
        return HttpResponse::respondWithSuccess(new AdminDetailTicketResource($ticket));
    }

    public function update(Request $request, string $seat)
    {
        $status =  $this->ticketRepository->update($seat, $request->all());
        if ($status) {
            return HttpResponse::respondWithSuccess([], "updated successfully");
        }
        return HttpResponse::respondError("Something wrong");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $status = $this->ticketRepository->delete($id);
        if ($status) {
            return HttpResponse::respondWithSuccess([], "deleted successfully");
        }
        return HttpResponse::respondError("Something wrong");
    }
}
