<?php

namespace App\Console\Commands\Bank;

use Illuminate\Console\Command;
use App\Http\Library\HttpResponse;
use App\Repositories\Bill\BillRepositoryInterface;
use App\Repositories\Ticket\TicketRepositoryInterface;
use App\Repositories\Seats\SeatsRepositoryInterface;
use App\Repositories\Trip\TripRepositoryInterface;

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
    private $tripRepository;
    private $seatRepository;
    private $billRepository;
    public function __construct(
        TicketRepositoryInterface $ticketRepository,
        TripRepositoryInterface $tripRepository,
        SeatsRepositoryInterface $seatRepository,
        BillRepositoryInterface $billRepository
    ) {
        parent::__construct();
        $this->ticketRepository = $ticketRepository;
        $this->tripRepository = $tripRepository;
        $this->seatRepository = $seatRepository;
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
                if (strpos($codebill['txnDesc'], $value['code']) !== false) {
                    $bill = $this->billRepository->findByCode($value['code']);
                    $tickets = $this->ticketRepository->getByBill($bill->id)->toArray();
                    $ticketIds = array_column($tickets, 'id');
                    $total = 0;
                    foreach ($tickets as $ticket) {
                        $total += $ticket['price'];
                    }
                    if ($codebill['txnAmount'] == $total) {
                        $this->ticketRepository->updateStatus($ticketIds, "booked");
                        $this->billRepository->update($bill->id, ['status' => "đã thanh toán"]);
                        $this->info('Update pending bill successfully');
                    }

                }
            }
        }
    }
}
