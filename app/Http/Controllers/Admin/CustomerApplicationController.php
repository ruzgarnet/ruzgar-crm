<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Telegram;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerApplication;
use App\Models\CustomerApplicationType;
use App\Models\Staff;
use Illuminate\Http\Request;

class CustomerApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.customer-application.list', ['customer_applications' => CustomerApplication::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.customer-application.add', [
            'staffs' => Staff::all(),
            'statuses' => trans(
                'tables.customer_application.status'
            ),
            'customer_application_types' => CustomerApplicationType::all(),
            'customers' => Customer::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = $this->rules();
        $customer_id = $request->input('customer_id');
        if (!$request->input('customer_id')) {
            $rules['first_name'] = 'required';
            $rules['last_name'] = 'required';
            $rules['telephone'] = 'required';
            $customer_id = null;
        } else {
            $rules['customer_id'] = 'required';
        }

        $validated = $request->validate($rules);
        $request = [];
        if ($customer_id == null) {
            $request["information"] = [
                'first_name' => $validated["first_name"],
                'last_name' => $validated["last_name"],
                'telephone' => $validated["telephone"]
            ];
            $validated["customer_id"] = null;
        } else {
            $request["information"] = json_encode([]);
        }

        $request["staff_id"] = $validated["staff_id"];
        $request["customer_id"] = $validated["customer_id"];
        $request["status"] = $validated["status"];
        $request["customer_application_type_id"] = $validated["customer_application_type_id"];
        $request["description"] = $validated["description"];
        $request["staff_id"] = $validated["staff_id"];
        if ($customer_application = CustomerApplication::create($request)) {
            if ($validated["customer_application_type_id"] == 1) {
                if($validated["customer_id"] == null)
                {
                    Telegram::send(
                        "İptalİşlemler",
                        trans(
                            'telegram.application_cancel',
                            [
                                'full_name' => $customer_application->information["first_name"]." ".$customer_application->information["last_name"],
                                'telephone' => $customer_application->information["telephone"]
                            ]
                        )
                    );
                }
                else
                {
                    Telegram::send(
                        "İptalİşlemler",
                        trans(
                            'telegram.application_cancel',
                            [
                                'full_name' => $customer_application->customer->full_name,
                                'telephone' => $customer_application->customer->telephone
                            ]
                        )
                    );
                }
            } else if ($validated["customer_application_type_id"] == 2) {
                if($validated["customer_id"] == null)
                {
                    Telegram::send(
                        "KaliteKontrolEkibi",
                        trans(
                            'telegram.application_cancel',
                            [
                                'full_name' => $customer_application->information["first_name"]." ".$customer_application->information["last_name"],
                                'telephone' => $customer_application->information["telephone"]
                            ]
                        )
                    );
                }
                else
                {
                    Telegram::send(
                        "KaliteKontrolEkibi",
                        trans(
                            'telegram.application_cancel',
                            [
                                'full_name' => $customer_application->customer->full_name,
                                'telephone' => $customer_application->customer->telephone
                            ]
                        )
                    );
                }
            } else {
            }
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.insert.success')
                ],
                'redirect' => relative_route('admin.customer_applications')
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
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerApplication  $customerApplication
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerApplication $customerApplication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerApplication  $customerApplication
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerApplication $customerApplication)
    {
        return view('admin.customer-application.edit', [
            'customer_application' => $customerApplication,
            'staffs' => Staff::all(),
            'statuses' => trans(
                'tables.customer_application.status'
            ),
            'customer_application_types' => CustomerApplicationType::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerApplication  $customerApplication
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerApplication $customerApplication)
    {
        $rules = $this->rules();
        $rules["status"] = 'required';
        $validated = $request->validate($rules);

        if ($customerApplication->update($validated)) {
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.edit.success')
                ],
                'redirect' => relative_route('admin.customer_applications')
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerApplication  $customerApplication
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerApplication $customerApplication)
    {
        //
    }

    public function rules()
    {
        return [
            'staff_id' => 'required',
            'customer_application_type_id' => 'required',
            'status' => 'required',
            'description' => 'required|max:255',
            'customer_application_type_id' => 'required'
        ];
    }
}
