<?php

namespace App\Http\Controllers;

use Stripe\Plan;
use Stripe\Stripe;

class SubscriptionsController extends Controller
{

    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function getPlans() {
        return response()->json(['data' => Plan::all()->data]);
    }

}
