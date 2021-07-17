<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\PaymentCancellation;
use App\Models\PaymentCreate;
use App\Models\PaymentDelete;
use App\Models\PaymentPriceEdit;
use App\Models\SubscriptionCancellation;
use App\Models\SubscriptionChange;
use App\Models\SubscriptionFreeze;
use App\Models\SubscriptionPriceEdit;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Customer $customer)
    {
        $subs = $customer->subscriptions()->pluck('id')->toArray();

        $payments = [];

        foreach ($customer->subscriptions as $subscription) {
            $payments = array_merge($payments, $subscription->payments()->pluck('id')->toArray());
        }

        return view('admin.activities.list',[
            'customer' => $customer,

            'subscriptionCancels' => SubscriptionCancellation::whereIn('subscription_id', $subs)->get(),
            'subscriptionChanges' => SubscriptionChange::whereIn("subscription_id", $subs)->get(),
            'subscriptionFreezes' => SubscriptionFreeze::whereIn("subscription_id", $subs)->get(),
            'subscriptionPrizeEdits' => SubscriptionPriceEdit::whereIn("subscription_id", $subs)->get(),

            'paymentCancels' => PaymentCancellation::whereIn('payment_id', $payments)->get(),
            'paymentPriceEdits' => PaymentPriceEdit::whereIn('payment_id', $payments)->get(),
            'paymentCreates' => PaymentCreate::whereIn("subscription_id", $subs)->get(),
            'paymentDeletes' => PaymentDelete::whereIn("subscription_id", $subs)->get()
        ]);
    }
}
