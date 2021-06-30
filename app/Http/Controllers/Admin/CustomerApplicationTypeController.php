<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerApplicationType;
use Illuminate\Http\Request;

class CustomerApplicationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.customer-application-type.list', [
            'customer_application_types' => CustomerApplicationType::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.customer-application-type.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        if (CustomerApplicationType::create($validated)) {
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.insert.success')
                ],
                'redirect' => relative_route('admin.customer.application.types')
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
     * @param  \App\Models\CustomerApplicationType  $customerApplicationType
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerApplicationType $customerApplicationType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerApplicationType  $customerApplicationType
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerApplicationType $customerApplicationType)
    {
        return view('admin.customer-application-type.edit', [
            'customer_application_type' => $customerApplicationType
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerApplicationType  $customerApplicationType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerApplicationType $customerApplicationType)
    {
        $validated = $request->validate($this->rules());

        if ($customerApplicationType->update($validated)) {
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.edit.success')
                ],
                'redirect' => relative_route('admin.customer.application.types')
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
     * @param  \App\Models\CustomerApplicationType  $customerApplicationType
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerApplicationType $customerApplicationType)
    {
        //
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255'
        ];
    }
}
