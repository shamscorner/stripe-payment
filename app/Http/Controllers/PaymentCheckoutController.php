<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Cartalyst\Stripe\Exception\CardErrorException;

class PaymentCheckoutController extends Controller
{
    /**
    * Author: shamscorner
    * DateTime: 24/November/2019 - 14:00:34
    *
    * checkout the payment
    *
    */
    public function checkout(Request $request)
    {
        // validate
        
        // create the payment charge
        try {
            $charge = Stripe::charges()->create([
                'amount' => 20,
                'currency' => 'USD',
                'source' => $request->stripeToken,
                'description' => 'Description goes here',
                'receipt_email' => $request->email,
                'metadata' => [
                    'data1' => 'metadata 1',
                    'data2' => 'metadata 2',
                    'data3' => 'metadata 3'
                ]
            ]);

            // save this info to your database

            // successfull message
            return back()->with('success_message', 'Thank you! Your payment has been accepted.');
        } catch (CardErrorException $e) {

            // save info to database for failed
            
            // save info to database for failed
            return back()->withErrors('Error! ' . $e->getMessage());
        }
    }
}
