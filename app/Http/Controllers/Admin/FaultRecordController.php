<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Generator;
use App\Classes\Telegram;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\FaultRecord;
use App\Models\FaultType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FaultRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin.fault-record.list', [
            'faultRecords' => FaultRecord::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.fault-record.add', [
            'customers' => Customer::where('customers.type', '!=', 0)->join('subscriptions', 'subscriptions.customer_id', 'customers.id')->distinct()->get('customers.*'),
            'faultTypes' => FaultType::where('status', 1)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules() + ['files.*' => 'required|file|image']);
        $files = [];
        $validated["serial_number"] = Generator::serialNumber();

        if ($request->input('files')) {
            foreach ($request->input('files') as $key => $file) {
                $files[] = str_replace("public/", "", $request->file('files.' . $key)->store('public/files'));
            }
        }

        $customer = Customer::find($validated["customer_id"]);

        Telegram::send(
            "RüzgarTeknik",
            trans(
                'telegram.add_fault_record',
                [
                    'id_no' => $customer->identification_number,
                    'full_name' => $customer->full_name,
                    'telephone' => $customer->telephone,
                    'customer_staff' => $customer->staff->full_name
                ]
            )
        );

        if (!empty($files)) {
            Telegram::send_photo(
                "RüzgarTeknik",
                "storage/" . $files[0]
            );
        }

        Telegram::send(
            "RüzgarTeknik",
            trans(
                'telegram.add_fault_record_description',
                [
                    'description' => $validated["description"]
                ]
            )
        );

        $validated["files"] = $files;
        $validated["solution_detail"] = "";
        if (FaultRecord::create($validated)) {
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.insert.success')
                ],
                'redirect' => relative_route('admin.fault.records')
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
     * @param  \App\Models\FaultRecord  $faultRecord
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(FaultRecord $faultRecord)
    {
        return view('admin.fault-record.edit', [
            'customers' => Customer::where('type', '!=', 0)->get(),
            'faultTypes' => FaultType::where('status', 1)->get(),
            'faultRecord' => $faultRecord,
            'statuses' => trans(
                'tables.fault.record.status'
            )
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FaultRecord  $faultRecord
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, FaultRecord $faultRecord)
    {
        $rules = $this->rules();
        $rules["status"] = 'required';
        $rules['solution_detail'] = 'required|string';
        $validated = $request->validate($rules);
        $validated["staff_id"] = $request->user()->staff_id;
        $old_status = $faultRecord->status;
        if ($faultRecord->update($validated)) {
            if ($old_status != $faultRecord->status) {
                Telegram::send(
                    "RüzgarTeknik",
                    trans(
                        'telegram.edit_fault_record',
                        [
                            'id_no' => $faultRecord->customer->identification_number,
                            'full_name' => $faultRecord->customer->full_name,
                            'serial_number' => $faultRecord->serial_number,
                            'status' => trans('tables.fault.record.status.' . $validated["status"]),
                            'description' => $validated["solution_detail"],
                            'username' => $request->user()->username
                        ]
                    )
                );
            }

            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.edit.success')
                ],
                'redirect' => relative_route('admin.fault.records')
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
     * Return rules for validation
     *
     * @return array
     */
    private function rules()
    {
        return [
            'customer_id' => [
                'required',
                Rule::exists('customers', 'id')->where(function ($query) {
                    return $query->where('type', '!=', 0);
                })
            ],
            'fault_type_id' => [
                'required',
                Rule::exists('fault_types', 'id')->where(function ($query) {
                    return $query->where('status', 1);
                })
            ],
            'description' => 'required|string'
        ];
    }
}
