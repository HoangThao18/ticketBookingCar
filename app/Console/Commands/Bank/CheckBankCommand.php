<?php

namespace App\Console\Commands\Bank;

use Illuminate\Console\Command;
use App\Http\Library\HttpResponse;
use App\Repositories\Bill\BillRepositoryInterface;
use App\Repositories\Ticket\TicketRepositoryInterface;

class CheckBank extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bank:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check bank';

    private $ticketRepository;
    private $billRepository;
    public function __construct(
        TicketRepositoryInterface $ticketRepository,
        BillRepositoryInterface $billRepository
    ) {
        parent::__construct();
        $this->ticketRepository = $ticketRepository;
        $this->billRepository = $billRepository;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->checkBank();
    }

    protected function checkBank()
    {

        $bills = $this->billRepository->findByStatus('waiting');
        $data = json_decode(config('listbank.listBank'), 1);
        foreach ($bills as  $value) {
            foreach ($data as $codebill) {
                // $trip = $this->tripRepository->findNotAssociateColumn($tickets->trip_id);
                // $seats = $this->seatRepository->getByCar($trip->car_id);
                // $total = 0;
                // $arrayUniqueSeatId =  array_unique($tickets->seat_id);
                // foreach ($arrayUniqueSeatId as $seatId) {
                //     $seat = $seats->find($seatId);
                //     $total += $seat->price;
                // }                
                if (strpos($codebill['txnDesc'], $value['code']) !== false) {
                    $bill = $this->billRepository->findByCode($value['code']);
                    $tickets = $this->ticketRepository->getByBill($bill->id)->toArray();
                    $ticketIds = array_column($tickets, 'id');
                    $total = 0;
                    foreach ($tickets as $ticket) {
                        $total += $ticket['price'];
                    }
                    if ($codebill['txnAmount'] == 10000) {
                        $this->ticketRepository->updateStatus($ticketIds, "booked");
                        $this->billRepository->update($bill->id, ['status' => "đã thanh toán"]);
                        $this->info('Update pending bill successfully');
                    }

                }
            }
        }
    }
}
