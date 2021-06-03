<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContractType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ContractTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin.contract-type.list', ['contractTypes' => ContractType::orderBy('id', 'DESC')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.contract-type.add');
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

        $validated['key'] = (string)Str::of($validated['key'])->slug();
        $validated['view'] = (string)Str::of($validated['view'])->slug();

        if (ContractType::create($validated)) {
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.insert.success')
                ],
                'redirect' => relative_route('admin.contract.types')
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
     * @param  \App\Models\ContractType  $contractType
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(ContractType $contractType)
    {
        return view('admin.contract-type.edit', ['contractType' => $contractType]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContractType  $contractType
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, ContractType $contractType)
    {
        $rules = $this->rules();

        $rules['key']['unique'] = Rule::unique('contract_types', 'key')->ignore($contractType->id);

        $validated = $request->validate($rules);

        if ($contractType->update($validated)) {
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.edit.success')
                ],
                'redirect' => relative_route('admin.contract.types')
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
            'key' => ['nullable', 'alpha_dash', 'unique' => Rule::unique('contract_types')],
            'title' => 'required|max:255',
            'view' => 'required|alpha_dash|max:255',
            'options' => 'nullable|json'
        ];
    }
}
