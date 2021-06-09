<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reference;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin.reference.list', ['references' => Reference::orderBy('id', 'DESC')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function create(Subscription $subscription)
    {
        $data = [
            'subscription' => $subscription,
            'subscriptions' => Subscription::getActive()
        ];
        return view('admin.reference.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'subscription_id' => [
                'required',
                Rule::exists('subscriptions', 'id')->where(function ($query) use ($subscription) {
                    $query->where('id', '!=', $subscription->id)
                        ->where('status', 1);
                })
            ]
        ]);

        $referenced = Subscription::find($validated['subscription_id']);

        $data = [
            'staff_id' => $request->user()->staff_id,
            'reference_id' => $subscription->id,
            'referenced_id' => $referenced->id,
        ];

        if (Reference::add_reference($data)) {
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.reference.success')
                ],
                'redirect' => relative_route('admin.customer.show', $subscription->customer)
            ]);
        }

        return response()->json([
            'error' => true,
            'toastr' => [
                'type' => 'error',
                'title' => trans('response.title.error'),
                'message' => trans('response.reference.error')
            ]
        ]);
    }
}
