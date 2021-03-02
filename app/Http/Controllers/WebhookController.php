<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class WebhookController extends CashierController
{
    /**
     * Handle a Stripe webhook call.
     *
     * @param  Request  $request
     * @return Response
     */
    public function handleWebhook(Request $request)
    {
        $payload = json_decode($request->getContent(), true);
        $method = 'handle'.Str::studly(str_replace('.', '_', $payload['type']));

        if (method_exists($this, $method)) {
            return $this->{$method}($payload);
        }

        return $this->missingMethod();
    }
    /**
     * @param array $payload
     */
    public function handleInvoiceCreated(array $payload)
    {
        DB::table('webhook')->insert([
            'data' => json_encode($payload)
        ]);

        if ($user = $this->getUserByStripeId($payload['data']['object']['customer']))
        {
            $data = $payload['data']['object'];
            DB::table('transactions')->insert([
                'user_id' =>$user->id,
                'payment_status'=>$data['paid'],
                'amount'=>$data['amount_paid']/100,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ]);
        }
    }

}
