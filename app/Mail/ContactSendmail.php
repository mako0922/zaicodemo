<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactSendmail extends Mailable
{
    use Queueable, SerializesModels;

    private $email;
    private $customer_name;
    private $initial_id;
    private $body;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $inputs )
    {
      $this->email = $inputs['email'];
      $this->customer_name = $inputs['customer_name'];
      $this->initial_id = $inputs['initial_id'];
      $this->body  = $inputs['body'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      return $this
          ->from('example@gmail.com')
          ->subject('在庫ドコ!申請受付完了メール')
          ->view('contact.mail')
          ->with([
              'email' => $this->email,
              'customer_name' => $this->customer_name,
              'initial_id' => $this->initial_id,
              'body'  => $this->body,
          ]);
    }
}
