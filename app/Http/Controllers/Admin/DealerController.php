<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Classes\Mutator;
use App\Models\City;
use App\Models\Dealer;
use App\Rules\AvailableDistrict;
use App\Rules\Telephone;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DealerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.dealer.list', ['dealers' => Dealer::orderBy('id', 'DESC')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.dealer.add', ['cities' => City::all()]);
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

        $validated = $request->validate($this->rules());

        if (Dealer::create($validated)) {
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.insert.success')
                ],
                'redirect' => relative_route('admin.dealers')
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
     * @param  \App\Models\Dealer  $dealer
     * @return \Illuminate\View\View
     */
    public function edit(Dealer $dealer)
    {
        return view('admin.dealer.edit', ['dealer' => $dealer, 'cities' => City::all()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dealer  $dealer
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Dealer $dealer)
    {
        $rules = $this->rules();
        $rules['tax_number'] = Rule::unique('dealers', 'tax_number')->ignore($dealer->id);
        $validated = $request->validate($rules);

        if ($dealer->update($validated)) {
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.edit.success')
                ],
                'redirect' => relative_route('admin.dealers')
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
            'name' => 'required|string|max:255',
            'tax_number' => 'required|string|max:255|unique:dealers,tax_number',
            'city_id' => 'required|exists:cities,id',
            'district_id' => [
                'required',
                new AvailableDistrict('city_id')
            ],
            'address' => 'required|string|max:255',
            'telephone' => ['required', new Telephone],
            'started_at' => 'required|date'
        ];
    }
}
