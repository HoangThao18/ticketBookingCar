<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BillPaid extends Notification
{
    use Queueable;

    protected $amountPaid;
    protected $orderNumber;
    /**
     * Create a new notification instance.
     */
    public function __construct($amountPaid, $orderNumber)
    {
        $this->amountPaid = $amountPaid;
        $this->orderNumber = $orderNumber;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Thanh toán hóa đơn thành công')
            ->line(' Thanh toán hóa đơn của bạn đã được xác nhận thành công.')
            ->line('Mã đơn hàng: ' . $this->orderNumber)
            ->line('Số tiền thanh toán: ' . $this->amountPaid . " VND")
            ->line('Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
