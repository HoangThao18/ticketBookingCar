<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\Bill\BillRepositoryInterface;
use App\Repositories\Ticket\TicketRepositoryInterface;

class CancelBillSpending extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancel-bill-spending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel bill spending';

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

        $bills = $this->billRepository->findByStatus('waiting', 7);
        foreach ($bills as  $value) {
            $bill = $this->billRepository->findByCode($value['code']);
            $tickets = $this->ticketRepository->getByBill($bill->id)->toArray();
            $ticketIds = array_column($tickets, 'id');

            $this->ticketRepository->updateStatus($ticketIds, "cancelled");
            $this->billRepository->update($bill->id, ['status' => "cancelled"]);
            $this->info('Cancelled pending bill successfully');
        }
    }
}
