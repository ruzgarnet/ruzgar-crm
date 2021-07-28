<?php

namespace App\Http\Controllers;

use App\Classes\Generator;
use App\Classes\Messages;
use App\Classes\Moka;
use App\Classes\SMS_Api;
use App\Classes\Telegram;
use App\Http\Controllers\Admin\PaymentController;
use App\Models\Category;
use App\Models\Customer;
use App\Models\CustomerApplication;
use App\Models\FaultRecord;
use App\Models\Message;
use App\Models\MokaLog;
use App\Models\MokaPayment;
use App\Models\MokaSale;
use App\Models\Payment;
use App\Models\SentMessage;
use App\Models\Staff;
use App\Models\Subscription;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class APIController extends Controller
{
    public function get_fault_with_serial_number(Request $request)
    {
        if (
            $request->input('serial_number') != null
        ) {
            $serial_number = $request->input('serial_number');

            $data = FaultRecord::selectRaw(
                'customers.identification_number,
                fault_types.title AS type_title,
                CONCAT(customers.first_name, \' \', customers.last_name) AS full_name,
                fault_records.description,
                fault_records.status'
            )
                ->join('customers', 'customers.id', 'fault_records.customer_id')
                ->join('fault_types', 'fault_types.id', 'fault_records.fault_type_id')
                ->where("fault_records.serial_number", $serial_number)->get();
            if ($data->count() > 0) {
                return response()->json([
                    'error' => false,
                    'is_null' => false,
                    'data' => $data[0]
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => "Arıza kaydı bulunamadı."
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'message' => "Lütfen geçerli değerler gönderiniz."
            ]);
        }
    }

    public function add_application(Request $request)
    {
        if (
            $request->input('application_type_id') != null
        ) {
            $application_type_id = $request->input('application_type_id');

            if ($application_type_id == 3) {
                if (
                    $request->input('address') != null &&
                    $request->input('phone') != null &&
                    $request->input('full_name') != null
                ) {
                    $address = $request->input('address');
                    $phone = $request->input('phone');
                    $tariff_id = $request->input('tariff_id');
                    $full_name = $request->input('full_name');

                    $first_name = "";
                    $last_name = "";

                    $full_name = explode(" ", $full_name);
                    $last_name = $full_name[count($full_name) - 1];
                    foreach ($full_name as $index => $item) {
                        if ($index >= (count($full_name) - 1))
                            break;
                        $first_name .= " " . $item;
                    }

                    $information = [
                        'address' => $address,
                        'telephone' => $phone,
                        'first_name' => $first_name,
                        'last_name' => $last_name
                    ];

                    if (CustomerApplication::create([
                        'customer_application_type_id' => $application_type_id,
                        'description' => "Rüzgar Destek",
                        'information' => $information
                    ])) {
                        Telegram::send(
                            "BizSiziArayalım",
                            $first_name . " " . $last_name . " adlı bir kullanıcı RüzgarFIBER hakkında bilgi almak istiyor. Telefon Numarası : " . $phone
                        );

            			SentMessage::create(
            			   [
            				'phone' => $phone,
            				'message' => Message::find(39)->message
            			   ]
            			);

                        return response()->json([
                            'error' => false,
                            'message' => "Başvuru kaydınız başarılı bir şekilde alınmıştır."
                        ]);
                    } else {
                        return response()->json([
                            'error' => true,
                            'message' => "Beklenmedik bir hata oluştu. Lütfen daha sonra tekrar deneyiniz."
                        ]);
                    }
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => "Lütfen geçerli değerler gönderiniz."
                    ]);
                }
            } else if ($application_type_id == 1) {
                if (
                    $request->input('identification_number') != null &&
                    $request->input('image') != null
                ) {
                    $identification_number = $request->input('identification_number');
                    $image = $request->input('image');

                    $customer = Customer::where("identification_number", $identification_number)->get();
                    if ($customer->count() > 0) {
                        $files = [];
                        $image = str_replace(" ", "+", $image);
                        $data = base64_decode($image);

                        $file = 'files/' . str_shuffle(substr("abcdefghijklmnoprstuvyzwxqABCDEFGHIJKLMNOPRSTUVYZWXQ0123456789", 0, 32)) . ".jpeg";
                        file_put_contents($file, $data);

                        $files[] = $file;

                        if (CustomerApplication::create([
                            'staff_id' => $customer[0]->staff->id,
                            'customer_application_type_id' => $application_type_id,
                            'customer_id' => $customer[0]->id,
                            'description' => "Rüzgar Destek",
                            'information' => json_encode([]),
                            'files' => json_encode($files)
                        ])) {
                            Telegram::send(
                                "KaliteKontrolEkibi",
                                trans(
                                    'telegram.application_cancel',
                                    [
                                        'full_name' => $customer[0]->full_name,
                                        'telephone' => $customer[0]->telephone
                                    ]
                                )
                            );
                            Telegram::send_photo(
                                "KaliteKontrolEkibi",
                                $files[0]
                            );

                            return response()->json([
                                'error' => false,
                                'message' => "İptal başvurunuz başarılı bir şekilde oluşturulmuştur."
                            ]);
                        } else {
                            return response()->json([
                                'error' => true,
                                'message' => "Beklenmedik bir hata oluştu. Lütfen daha sonra tekrar deneyiniz."
                            ]);
                        }
                    } else {
                        return response()->json([
                            'error' => true,
                            'message' => "Bu kimlik numarasına ait bir kayıt bulunamadı."
                        ]);
                    }
                }
            } else if ($application_type_id == 2) {
                if (
                    $request->input('identification_number') != null &&
                    $request->input('description') != null
                ) {
                    $identification_number = $request->input('identification_number');
                    $description = $request->input('description');
                    $application_type_id = $request->input('application_type_id');

                    $customer = Customer::where("identification_number", $identification_number)->get();
                    if ($customer->count() > 0) {
                        if (CustomerApplication::create([
                            'staff_id' => $customer[0]->staff->id,
                            'customer_application_type_id' => $application_type_id,
                            'customer_id' => $customer[0]->id,
                            'description' => $description,
                            'information' => json_encode([])
                        ])) {
                            Telegram::send(
                                "KaliteKontrolEkibi",
                                "[TARİFE YÜKSELTME] \nAdı Soyadı : " . $customer[0]->full_name . "\nTelefon Numarası : " . $customer[0]->telephone . "\nMüşteri Temsilcisi : " . $customer[0]->staff->full_name
                            );

                            return response()->json([
                                'error' => false,
                                'message' => "Tarife yükseltme başvurunuz başarılı bir şekilde oluşturuldu."
                            ]);
                        } else {
                            return response()->json([
                                'error' => true,
                                'message' => "Beklenmedik bir hata oluştu. Lütfen daha sonra tekrar deneyiniz."
                            ]);
                        }
                    } else {
                        return response()->json([
                            'error' => true,
                            'message' => "Bu kimlik numarasına ait bir kayıt bulunamadı."
                        ]);
                    }
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => "Lütfen geçerli değerler gönderiniz.1"
                    ]);
                }
            } else {
                return response()->json([
                    'error' => true,
                    'message' => "Lütfen geçerli değerler gönderiniz."
                ]);
            }
        }
    }

    public function get_reference_code(Request $request)
    {
        if (
            $request->input('identification_number') != null
        ) {
            $validated = [
                'identification_number' => $request->input('identification_number')
            ];

            $customer = Customer::select('reference_code')->where('identification_number', $validated["identification_number"])->get();

            if ($customer->count() > 0) {
                return response()->json([
                    'error' => false,
                    'is_null' => false,
                    'reference_code' => $customer[0]->reference_code
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'is_null' => true,
                    'message' => "Bu kimlik numarasına ait bir kayıt bulunamadı."
                ]);
            }
        }
    }
    public function add_fault(Request $request)
    {
        if (
            $request->input('identification_number') != null &&
            $request->input('token') != null &&
            $request->input('image') != null &&
            $request->input('latitude') != null &&
            $request->input('longitude') != null &&
            $request->input('fault_title_id') != null
        ) {

            $values = [
                'identification_number' => $request->input('identification_number'),
                'token' => $request->input('token'),
                'image' => $request->input('image'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'fault_title_id' => $request->input('fault_title_id')
            ];

            $customer = Customer::where('identification_number', $values["identification_number"])->get();

            if ($customer->count() > 0) {
                $files = [];

                $validated["serial_number"] = Generator::serialNumber();
                $validated["files"] = $files;
                $validated["solution_detail"] = "";

                $validated = $validated + [
                    'customer_id' => $customer[0]->id,
                    'fault_type_id' => $values["fault_title_id"],
                    'status' => 1,
                    'description' => ''
                ];

                $image = str_replace(" ", "+", $values['image']);
                $data = base64_decode($image);

                $file = 'files/' . str_shuffle(substr("abcdefghijklmnoprstuvyzwxqABCDEFGHIJKLMNOPRSTUVYZWXQ0123456789", 0, 32)) . ".jpeg";
                file_put_contents($file, $data);

                $files[] = $file;

                $validated["files"] = $files;

                $message = Message::where("id", 20)->get();

                if ($fault_record = FaultRecord::create($validated)) {
                    SentMessage::insert(
                        [
                            'customer_id' => $customer[0]->id,
                            'message' => (new Messages)->generate(
                                $message[0]->message,
                                [
                                    'seri_numarasi' => $fault_record->serial_number
                                ]
                            ),
                            'staff_id' => $customer[0]->staff->id
                        ]
                    );

                    Telegram::send(
                        "RüzgarTeknik",
                        trans(
                            'telegram.add_fault_record',
                            [
                                'id_no' => $customer[0]->identification_number,
                                'full_name' => $customer[0]->full_name,
                                'telephone' => $customer[0]->telephone,
                                'customer_staff' => $customer[0]->staff->full_name
                            ]
                        )
                    );

                    if (!empty($files)) {
                        Telegram::send_photo(
                            "RüzgarTeknik",
                            $files[0]
                        );
                    }

                    return response()->json([
                        'error' => false,
                        'message' => "",
                        'serial_number' => Generator::serialNumber()
                    ]);
                }

                return response()->json([
                    'error' => true,
                    'message' => ""
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'Arıza kaydı oluşturabilmek için abone olmanız gerekmektedir.'
                ]);
            }
        }
    }

    public function get_faults(Request $request)
    {
        if ($request->input("status_id") == 0) {
            $data = FaultRecord::selectRaw('fault_records.id, fault_records.status, fault_records.description, CONCAT(customers.first_name, \' \', customers.last_name) AS full_name, CONCAT(staff.first_name, \' \', staff.last_name) AS staff_full_name, fault_records.created_at AS created_date')->join('customers', 'customers.id', 'fault_records.customer_id')->join('customer_staff', 'customers.id', 'customer_staff.customer_id')->join('staff', 'staff.id', 'customer_staff.staff_id')->get();
        } else {
            $data = FaultRecord::selectRaw('fault_records.id, fault_records.status, fault_records.description, CONCAT(customers.first_name, \' \', customers.last_name) AS full_name, CONCAT(staff.first_name, \' \', staff.last_name) AS staff_full_name, fault_records.created_at AS created_date')->join('customers', 'customers.id', 'fault_records.customer_id')->join('customer_staff', 'customers.id', 'customer_staff.customer_id')->join('staff', 'staff.id', 'customer_staff.staff_id')->where('status', $request->input("status_id"))->get();
        }

        return response()->json([
            'error' => false,
            'data' => $data
        ]);
    }

    public function search_fault(Request $request)
    {
        if (
            $request->input('status_id') != null &&
            $request->input('search_string') != null
        ) {
            if ($request->input("status_id") == 0) {
                $data = FaultRecord::selectRaw('fault_records.id, fault_records.status, fault_records.description, CONCAT(customers.first_name, \' \', customers.last_name) AS full_name, CONCAT(staff.first_name, \' \', staff.last_name) AS staff_full_name, fault_records.created_at AS created_date')->join('customers', 'customers.id', 'fault_records.customer_id')->join('customer_staff', 'customers.id', 'customer_staff.customer_id')->join('staff', 'staff.id', 'customer_staff.staff_id')->whereRaw('CONCAT(customers.first_name, \' \', customers.last_name) LIKE \'%' . $request->input('search_string') . '%\'')->get();
            } else {
                $data = FaultRecord::selectRaw('fault_records.id, fault_records.status, fault_records.description, CONCAT(customers.first_name, \' \', customers.last_name) AS full_name, CONCAT(staff.first_name, \' \', staff.last_name) AS staff_full_name, fault_records.created_at AS created_date')->join('customers', 'customers.id', 'fault_records.customer_id')->join('customer_staff', 'customers.id', 'customer_staff.customer_id')->join('staff', 'staff.id', 'customer_staff.staff_id')->whereRaw('CONCAT(customers.first_name, \' \', customers.last_name) LIKE \'%' . $request->input('search_string') . '%\'')->where('status', $request->input("status_id"))->get();
            }

            if ($data->count() > 0) {
                return response()->json([
                    'error' => false,
                    'data' => $data
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => "Arıza bulunamadı."
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'message' => "Lütfen geçerli değerler gönderiniz."
            ]);
        }
    }

    public function get_fault(Request $request)
    {
        $data = FaultRecord::selectRaw('customers.identification_number, fault_types.title AS type_title, CONCAT(customers.first_name, \' \', customers.last_name) AS full_name, fault_records.description AS detail, fault_records.status, fault_records.solution_detail')->join('fault_types', 'fault_types.id', 'fault_records.fault_type_id')->join('customers', 'customers.id', 'fault_records.customer_id')->where('fault_records.id', $request->input('fault_id'))->get();
        return response()->json([
            'error' => false,
            'data' => $data[0]
        ]);
    }

    public function edit_fault(Request $request)
    {
        if (
            $request->input('fault_id') != null &&
            $request->input('status_id') != null &&
            $request->input('username') != null
        ) {
            $fault_id = $request->input('fault_id');
            $status_id = $request->input('status_id');
            $detail = $request->input('detail');
            if (!$detail) {
                return response()->json([
                    'error' => true,
                    'message' => "NOT"
                ]);
            }
            $username = $request->input('username');

            $fault_record = FaultRecord::find($fault_id);

            $fault_record->status = $status_id;
            $fault_record->solution_detail = $detail;

            if ($fault_record->save()) {
                Telegram::send(
                    "RüzgarTeknik",
                    trans('telegram.edit_fault', ['full_name' => $fault_record->customer->full_name, 'serial_number' => $fault_record->serial_number, 'status' => trans('tables.fault.record.status.' . $status_id), 'detail' => $fault_record->solution_detail, 'username' => $username])
                );

                $message = Message::find(38);
                SentMessage::insert(
                    [
                        'customer_id' => $fault_record->customer->id,
                        'message' => (new Messages)->generate(
                            $message->message,
                            [
                                'durum' => trans('tables.fault.record.status.' . $status_id)
                            ]
                        )
                    ]
                );

                return response()->json([
                    'error' => false,
                    'message' => "Başarılı bir şekilde güncellendi."
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => "Beklenmedik bir hata oluştu. Lütfen daha sonra tekrar deneyiniz."
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'message' => "Lütfen geçerli değerler gönderiniz."
            ]);
        }
    }

    public function login(Request $request)
    {
        if (
            $request->input('username') != null &&
            $request->input('password') != null
        ) {
            $validated = [
                'username' => $request->input('username'),
                'password' => $request->input('password')
            ];

            $user = User::where(['username' => $validated["username"]])->get();
            if ($user->count() > 0) {
                $result = Hash::check($validated["password"], $user[0]->password);
                if ($result) {
                    return response()->json([
                        'error' => false,
                        'message' => "Başarılı bir şekilde giriş yaptınız."
                    ]);
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => "Kullanıcı adı veya şifre yanlış."
                    ]);
                }
            } else {
                return response()->json([
                    'error' => true,
                    'message' => "Kullanıcı adı veya şifre yanlış."
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'message' => "Lütfen geçerli değerler gönderiniz."
            ]);
        }
    }

    public function send_sms(Request $request)
    {
        if (
            $request->input('content') != null &&
            $request->input('category_id') != null
        ) {
            $content = $request->input('content');
            $category_id = $request->input('category_id');

            $messages = new Messages();

            $numbers = [];

            if ($category_id == 0) {
                $subscriptions = Subscription::where("status", 1)->get();
            } else {
                $category = Category::find($category_id);
                $subscriptions = Subscription::whereIn("service_id", $category->services->pluck('id'))->get();
            }

            if ($subscriptions->count() > 0) {
                foreach ($subscriptions as $subscription) {
                    $numbers[] = $subscription->customer->telephone;
                }

                $messages = $messages->multiMessage(
                    $content,
                    $subscriptions
                );
            } else {
                $messages = [];
            }

            $sms = new SMS_Api();
            $result = $sms->submitMulti(
                "RUZGARNET",
                $messages
            );

            if ($result) {
                return response()->json([
                    'error' => false,
                    'message' => "SMS'ler başarılı bir şekilde gönderildi."
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => "SMS'ler gönderilirken bir hata oluştu. Lütfen daha sonra tekrar deneyiniz."
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'message' => "Lütfen geçerli değerler gönderiniz."
            ]);
        }
    }

    public function pay(Request $request)
    {
        $rules = [];
        $rules["credit_card_holder_name"] = [
            'required',
            'numeric'
        ];
        $rules["credit_card_number"] = [
            'required',
            'string',
            'max:255'
        ];
        $rules["month"] = [
            'required',
            'string'
        ];
        $rules["year"] = [
            'required',
            'string'
        ];
        $rules["security_code"] = [
            'required',
            'numeric',
            'between:100,999'
        ];
        $rules["payment_id"] = ['required'];

        if (
            $request->input('credit_card_holder_name') != null &&
            $request->input('credit_card_number') != null &&
            $request->input('month') != null &&
            $request->input('year') != null &&
            $request->input('security_code') != null &&
            $request->input('payment_id') != null &&
            $request->input('is_automatic') != null
        ) {
            $validated = [
                'credit_card_holder_name' => $request->input('credit_card_holder_name'),
                'credit_card_number' => $request->input('credit_card_number'),
                'month' => $request->input('month'),
                'year' => $request->input('year'),
                'security_code' => $request->input('security_code'),
                'payment_id' => $request->input('payment_id'),
                'is_automatic' => $request->input('is_automatic')
            ];

            $payment = Payment::find($validated["payment_id"]);

            $moka = new Moka();

            if ($payment->mokaPayment) {
                $payment_detail = $moka->get_payment_detail_by_other_trx($payment->mokaPayment->trx_code);

                if (
                    $payment_detail->Data->PaymentDetail->PaymentStatus == 2 &&
                    $payment_detail->Data->PaymentDetail->TrxStatus == 1
                ) {
                    $payment->receive([
                        'type' => 4
                    ]);

                    return response()->json([
                        'error' => true,
                        'message' => "Lütfen ödemenizi tekrar sorgulayınız."
                    ]);
                }
            }

            MokaPayment::where('payment_id', $payment->id)->delete();

            $card = [
                'full_name' => $validated['credit_card_holder_name'],
                'number' => $validated['credit_card_number'],
                'expire_month' => $validated['month'],
                'expire_year' => $validated['year'],
                'security_code' => $validated['security_code'],
                'amount' => $payment->price
            ];

            $hash = [
                'subscription_no' => $payment->subscription->subscription_no,
                'payment_created_at' => $payment->created_at
            ];

            $moka = new Moka();
            $response = $moka->pay(
                $card,
                "https://crm.ruzgarnet.site/payment/result/" . $payment->id,
                $hash
            );

            if ($response->Data != null) {
                MokaPayment::create([
                    'payment_id' => $payment->id,
                    'trx_code' => $moka->trx_code
                ]);

                MokaLog::create([
                    'payment_id' => $payment->id,
                    'ip' => $request->ip(),
                    'response' => ['init' => $response],
                    'trx_code' => $moka->trx_code
                ]);

                if ($request->input('is_automatic') && !$payment->subscription->isAuto()) {
                    $auto_data = [
                        'card' => [
                            'full_name' => $validated['credit_card_holder_name'],
                            'number' => $validated['credit_card_number'],
                            'expire_date' =>  $validated['month'] . '/' . $validated['year'],
                            'amount' => $payment->price
                        ]
                    ];

                    (new PaymentController)->define_auto_payment($payment, $auto_data, false);
                }

                return response()->json([
                    'error' => false,
                    'frame' => $response->Data->Url
                ]);
            }

            MokaLog::create([
                'payment_id' => $payment->id,
                'ip' => $request->ip(),
                'response' => $response,
                'trx_code' => $moka->trx_code
            ]);

            return response()->json([
                'error' => true,
                'message' => "Ödeme oluşturulurken hata oluştu. Lütfen kart bilgilerinizi kontrol ediniz."
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => "Lütfen kart bilgilerinizi kontrol edin."
            ]);
        }
    }

    public function get_payment_list(Request $request)
    {
        $validated = $request->validate([
            'identification_number' => 'string|numeric'
        ]);

        $identification_number = $request->input('identification_number');

        if (!Customer::where("identification_number", $identification_number)->count() > 0) {
            $payment_list["error"] = true;
            $payment_list["message"] = "Bu T.C. kimlik numarasına ait abonelik kaydı bulunamadı.";
        } else {
            $customer = Customer::where('identification_number', $identification_number)->first();
            $moka = new Moka();
            foreach ($customer->subscriptions as $subscription) {
                if ($payment = $subscription->currentPayment()) {
                    if (!$payment->isPaid() && $payment->mokaPayment) {

                        $payment_detail = $moka->get_payment_detail_by_other_trx(
                            $payment->mokaPayment->trx_code
                        );

                        if (
                            $payment_detail->Data->PaymentDetail->PaymentStatus == 2 &&
                            $payment_detail->Data->PaymentDetail->TrxStatus == 1
                        ) {
                            $payment->receive([
                                'type' => 4
                            ]);

                            Telegram::send(
                                'RüzgarNETÖdeme',
                                trans('telegram.payment_received', [
                                    'id_no' => $payment->subscription->customer->identification_number,
                                    'full_name' => $payment->subscription->customer->full_name,
                                    'price' => $payment->price,
                                    'category' => $payment->subscription->service->category->name
                                ])
                            );
                        }
                    }
                }
            }
            
            $payments = Subscription::select('payments.id', 'payments.subscription_id', 'payments.price', 'payments.date', 'customers.first_name', 'customers.last_name', 'customers.telephone')
                    ->join('payments', 'subscriptions.id', 'payments.subscription_id')
                    ->join('customers', 'customers.id', 'subscriptions.customer_id')
                    ->where([
                        'payments.date' => date('Y-m-15'),
                        'customers.identification_number' => $identification_number,
                        'subscriptions.status' => 1,
                    ])->whereNull("paid_at")->get();

            $payment_list = [];

            if (count($payments) > 0) {
                $payment_list["error"] = false;
            } else {
                $payment_list["error"] = true;
                $payment_list["message"] = "Ödenmemiş faturanız bulunmamaktadır.";
            }

            foreach ($payments as $payment) {
                $payment_list["invoices_list"][] = [
                    'full_name' => $payment->first_name . " " . $payment->last_name,
                    'invoice_id' => $payment->id,
                    'customer_id' => $payment->subscription_id,
                    'amount' => $payment->price,
                    'invoice_date' => $payment->date,
                    'phone' => $payment->telephone
                ];
            }
        }

        return response()->json($payment_list);
    }
}
