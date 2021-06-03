<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Classes\Mutator;
use App\Models\Dealer;
use App\Models\Staff;
use App\Rules\TCNo;
use App\Rules\Telephone;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin.staff.list', ['staffs' => Staff::orderBy('id', 'DESC')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.staff.add', ['dealers' => Dealer::whereNull('ended_at')->get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if ($request->input('telephone')) {
            $request->merge([
                'telephone' => Mutator::phone($request->input('telephone'))
            ]);
        }

        if ($request->input('secondary_telephone')) {
            $request->merge([
                'secondary_telephone' => Mutator::phone($request->input('secondary_telephone'))
            ]);
        }

        $validated = $request->validate($this->rules());

        if (Staff::create($validated)) {
            return response()->json([
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.insert.success')
                ],
                'redirect' => relative_route('admin.staffs')
            ]);
        }

        return response()->json([
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
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Staff $staff)
    {
        return view('admin.staff.edit', ['staff' => $staff, 'dealers' => Dealer::whereNull('ended_at')->get()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Staff $staff)
    {
        if ($request->input('telephone')) {
            $request->merge([
                'telephone' => Mutator::phone($request->input('telephone'))
            ]);
        }

        if ($request->input('secondary_telephone')) {
            $request->merge([
                'secondary_telephone' => Mutator::phone($request->input('secondary_telephone'))
            ]);
        }

        $rules = $this->rules();

        // Ignored uniques
        $rules['email']['unique']                 = Rule::unique('staff', 'email')->ignore($staff->id);
        $rules['telephone']['unique']             = Rule::unique('staff', 'telephone')->ignore($staff->id);
        $rules['secondary_telephone']['unique']   = Rule::unique('staff', 'secondary_telephone')->ignore($staff->id);
        $rules['identification_number']['unique'] = Rule::unique('staff', 'identification_number')->ignore($staff->id);

        $rules['released_at']  = 'nullable|date';

        $validated = $request->validate($rules);

        if ($staff->update($validated)) {
            return response()->json([
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.edit.success')
                ],
                'redirect' => relative_route('admin.staffs')
            ]);
        }

        return response()->json([
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
            'dealer_id' => 'required|exists:dealers,id',
            'gender' => 'required',
            'first_name' => 'required|max:63',
            'last_name' => 'required|max:63',
            'birthday' => 'required|date',
            'address' => 'required|max:255',
            'identification_number' => [
                'required',
                new TCNo,
                'unique' => Rule::unique('staff', 'identification_number')
            ],
            'telephone' => [
                'required',
                new Telephone,
                'unique' => Rule::unique('staff', 'telephone')
            ],
            'email' => [
                'required',
                'email',
                'unique' => Rule::unique('staff', 'email')
            ],
            'secondary_telephone' => [
                'required',
                new Telephone,
                'unique' => Rule::unique('staff', 'secondary_telephone')
            ],
            'started_at' => 'required|date'
        ];
    }
}
