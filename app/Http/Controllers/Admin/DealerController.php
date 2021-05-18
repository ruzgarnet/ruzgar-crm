<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Dealer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DealerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Dealer $dealer
     * @return \Illuminate\View\View
     */
    public function index(Dealer $dealer)
    {
        return view('admin.dealer.list', ['dealers' => $dealer->all()]);
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
     * @param  \App\Models\Dealer $dealer
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Dealer $dealer)
    {
        $validated = $request->validate($this->rules());

        if ($dealer->create($validated)) {
            return response()->json([
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.insert.success')
                ],
                'redirect' => relative_route('admin.dealers')
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
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.edit.success')
                ],
                'redirect' => relative_route('admin.dealers')
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dealer  $dealer
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Dealer $dealer)
    {
        $id = $dealer->id;
        if ($dealer->delete()) {
            return response()->json([
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.delete.success')
                ],
                'deleted' => $id
            ]);
        }

        return response()->json([
            'toastr' => [
                'type' => 'error',
                'title' => trans('response.title.error'),
                'message' => trans('response.delete.error')
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
            'district_id' => 'required|in:' . $this->district_ids(),
            'address' => 'required|string|max:255'
        ];
    }

    /**
     * Find district ids for city
     *
     * @return string|null
     */
    private function district_ids()
    {
        return request()->input('city_id') ?
            (City::find(request()->input('city_id'))->districts->pluck('id')->implode(',') ?? null)
            : null;
    }
}
