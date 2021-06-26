<?php

namespace App\Http\Controllers\Admin;

use App\Classes\SMS_Api;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Message;
use App\Models\Payment;
use App\Models\PaymentPriceEdit;
use App\Models\SentMessage;
use App\Models\Subscription;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin.message.list', ['messages' => Message::orderBy('id', 'DESC')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.message.add');
    }

    public function send()
    {
        return view("admin.message.send_sms", ["customers" => Customer::all(), "messages" => Message::all(), 'categories' => Category::all()]);
    }

    /*
        1 => 'Müşteri',
        2 => 'Kategori',
        3 => 'Tüm Aboneler',
        4 => 'Ödemeyenler',
        5 => 'Gecikme Ücreti Yansıyacaklar'
    */

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:1,2,3,4,5',
            'message_id' => 'required|exists:messages,id'
        ]);

        $type = $validated["type"];

        if ($type == 1) {
            $validated = $request->validate([
                'customers' => 'required|array',
                'customers.*' => 'required|exists:customers,id'
            ]) + $validated;
        } else if ($type == 2) {
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id'
            ]) + $validated;
        }

        $message = Message::find($validated["message_id"]);

        $sms = new SMS_Api();

        if ($type == 1) {
            $customers = Customer::whereIn('id', $validated["customers"])->get();
            $numbers = [];
            foreach ($customers as $customer) {
                $numbers[] = $customer->telephone;
            }
        } else if ($type == 2) {
            $category = Category::find($validated["category_id"]);
            $subscriptions = Subscription::whereIn("service_id", $category->services->pluck('id'))->get();
            $numbers = [];
            foreach ($subscriptions as $subscription) {
                $numbers[] = $subscription->customer->telephone;
            }
            /*$sms->submit(
                "RUZGARNET",
                $message->message,
                $numbers
            );*/
        } else if ($type == 3) {
            $subscriptions = Subscription::all();
            $numbers = [];
            foreach ($subscriptions as $subscription) {
                $numbers[] = $subscription->customer->telephone;
            }
            /*$sms->submit(
                "RUZGARNET",
                $message->message,
                $numbers
            );*/
        } else if ($type == 4) {
            $numbers = [];
            $payments = Payment::where("status", "<>", 2)->where('date', date('Y-m-15'))->get();
            foreach ($payments as $payment) {
                $numbers[] = $payment->subscription->customer->telephone;
            }
            /*$sms->submit(
                "RUZGARNET",
                $message->message,
                $numbers
            );*/
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        if (Message::insert($validated)) {
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.insert.success')
                ],
                'redirect' => relative_route('admin.messages')
            ]);
        }

        return response()->json([
            'error' => true,
            'toastr' => [
                'type' => 'error',
                'title' => trans('response.title.error'),
                'message' => trans('response.insert.error')
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Message $message)
    {
        return view('admin.message.edit', ['message' => $message]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Message $message)
    {
        $rules = $this->rules();

        $rules['key']['unique'] = Rule::unique('messages', 'key')->ignore($message->id);

        $validated = $request->validate($rules);

        if ($message->update($validated)) {
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.edit.success')
                ],
                'redirect' => relative_route('admin.messages')
            ]);
        }

        return response()->json([
            'error' => true,
            'toastr' => [
                'type' => 'error',
                'title' => trans('response.title.error'),
                'message' => trans('response.edit.error')
            ]
        ]);
    }

    /**
     * Rules for validation
     *
     * @return array
     */
    private function rules()
    {
        return [
            'key' => [
                'required',
                'string',
                'max:255',
                'unique' => Rule::unique('messages', 'key')
            ],
            'title' => 'required|string|max:255',
            'message' => 'required|string'
        ];
    }
}
