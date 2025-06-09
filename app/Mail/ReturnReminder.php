<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReturnReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $borrow;
    public $type; // 'device' hoặc 'room'
    public $isOverdue;

    public function __construct($borrow, $type, $isOverdue = false)
    {
        $this->borrow = $borrow;
        $this->type = $type;
        $this->isOverdue = $isOverdue;
    }

    public function build()
    {
        $subject = $this->isOverdue 
            ? "Cảnh báo: Quá hạn trả " . ($this->type == 'device' ? 'thiết bị' : 'phòng')
            : "Nhắc nhở: Sắp đến hạn trả " . ($this->type == 'device' ? 'thiết bị' : 'phòng');

        return $this->subject($subject)
                    ->view('emails.return-reminder');
    }
} 