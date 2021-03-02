<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscriptionCancleMail extends Mailable
{
	use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$type)
    {
    	$this->user=$user;
    	$this->type=$type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

    	if($this->type=='user'){
    		return $this->subject('Subscription Cancel Mail')->view('email.user-subscription-cancle')->with([
    			'user'=>$this->user
    		]);

    	}else{

    		return $this->subject('Subscription Cancel Mail')->view('email.admin-subscription-cancle')->with([
    			'user'=>$this->user
    		]);
    	}
    }
}
