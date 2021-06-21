<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Generator;
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
            'customers' => Customer::where('type', '!=', 0)->get(),
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
        $validated = $request->validate($this->rules());

        $validated["serial_number"] = Generator::serialNumber();

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
        $validated = $request->validate($rules);

        if ($faultRecord->update($validated)) {
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
