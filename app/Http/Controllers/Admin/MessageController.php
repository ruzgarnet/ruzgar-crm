<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Messages;
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
        return view('admin.message.list', [
            'messages' => Message::orderBy('id', 'DESC')->get()
        ]);
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

    /**
     * Show the form for sending messages.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function send()
    {
        return view("admin.message.send_sms", [
            "customers" => Customer::all(),
            "messages" => Message::all(),
            'categories' => Category::all()
        ]);
    }

    /**
     * Send messages.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|void|null
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

        $messages = [];
        $message = Message::find($validated["message_id"])->message;
        $message_formatter = new Messages();

        $sms = new SMS_Api();

        if ($type == 1) {
            $customers = Customer::whereIn('id', $validated["customers"])->get();
            $numbers = [];
            foreach ($customers as $customer) {
                $messages[] = [
                    $customer->telephone,
                    $message_formatter->generate(
                        $message,
                        [
                            'ad_soyad' => $customer->full_name,
                            'ay' => date('m'),
                            'yil' => date('Y'),
                            'referans_kodu' => $customer->reference_code
                        ]
                    )
                ];
            }
        } else if ($type == 2) {
            $category = Category::find($validated["category_id"]);
            $subscriptions = Subscription::whereIn("service_id", $category->services->pluck('id'))->get();
            $numbers = [];
            foreach ($subscriptions as $subscription) {
                $messages[] = [
                    $subscription->customer->telephone,
                    $message_formatter->generate(
                        $message,
                        [
                            'ad_soyad' => $subscription->customer->full_name,
                            'ay' => date('m'),
                            'yil' => date('Y'),
                            'referans_kodu' => $subscription->customer->reference_code
                        ]
                    )
                ];
            }
        } else if ($type == 3) {
            $subscriptions = Subscription::all();
            $numbers = [];
            foreach ($subscriptions as $subscription) {
                $messages[] = [
                    $subscription->customer->telephone,
                    $message_formatter->generate(
                        $message,
                        [
                            'ad_soyad' => $subscription->customer->full_name,
                            'ay' => date('m'),
                            'yil' => date('Y'),
                            'referans_kodu' => $subscription->customer->reference_code
                        ]
                    )
                ];
            }
        } else if ($type == 4) {
            $numbers = [];
            $payments = Payment::where("status", "<>", 2)->where('date', date('Y-m-15'))->get();
            foreach ($payments as $payment) {
                $messages[] = [
                    $payment->subscription->customer->telephone,
                    $message_formatter->generate(
                        $message,
                        [
                            'ad_soyad' => $payment->subscription->customer->full_name,
                            'ay' => date('m'),
                            'yil' => date('Y'),
                            'referans_kodu' => $payment->subscription->customer->reference_code
                        ]
                    )
                ];
            }
        }

        $sms->submitMulti(
            "RUZGARNET",
            $messages
        );

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

    /**
     * Send spesific message
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send_sms_spesific(Request $request)
    {
        $validated = $request->validate(
            [
                'telephone' => 'required',
                'message_id' => 'required'
            ]
        );

        $message_formatter = new Message();

        $message = Message::find($validated["message_id"]);
        $sms = new SMS_Api();


        if($sms->submit('RUZGARNET',$message->message,[$validated["telephone"]])){
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.insert.success')
                ],
                'reload' => true
            ]);
        }
        else{
            return response()->json([
                'error' => true,
                'toastr' => [
                    'type' => 'error',
                    'title' => trans('response.title.error'),
                    'message' => trans('response.insert.error')
                ]
            ]);
        }

        if($message)
        {
            SentMessage::create(
                [
                    'phone' => $validated["telephone"],
                    'message' => $message->message
                ]
            );
        }
    }

    public function send_sms_payment(Payment $payment)
    {
        $message_formatter = new Message();

        $name = $payment->subscription->customer->full_name;
        $phone = $payment->subscription->customer->telephone;
        $price = $payment->price;
        $date = $payment->date_print;

        if(SentMessage::create(
            [
                'phone' => $phone,
                'message' => $name.' isimli değerli müşterimiz '.$price.'TL tutarındaki faturanızın son ödeme tarihi 15 '.$date.' dır. +45 TL hizmet bedeli ödememek ve yasal yaptırımlarla karşılaşmamak için lütfen ödemenizi gerçekleştiriniz.Ödeme yapmanız için Hesap bilgilerimiz; HALKBANK HESAP ADI: AKARNET TELEKOM Sanayi Ticaret Limited Şirketi İBAN NO: TR85 0001 2009 6890 0010 2609 33 ACIKLAMA: ABONE İSİM SOYİSİM VE TC YAZILMALIDIR.HESAP ADI DOĞRU VE EKSİKSİZ YAZILMALIDIR.'
            ]
        )){
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.insert.success')
                ],
                'reload' => true
            ]);
        }
        else{
            return response()->json([
                'error' => true,
                'toastr' => [
                    'type' => 'error',
                    'title' => trans('response.title.error'),
                    'message' => trans('response.insert.error')
                ]
            ]);
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
