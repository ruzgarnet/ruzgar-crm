<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin.service.list', ['services' => Service::orderBy('id', 'DESC')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.service.add', [
            'categories' => Category::where('status', 1)->where('type', 2)->get()
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
        $request->merge([
            'slug' => (string)Str::of($request->input('slug') ?? $request->input('name'))->slug()
        ]);

        $validated = $request->validate($this->rules());

        if (Service::create($validated)) {
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.insert.success')
                ],
                'redirect' => relative_route('admin.services')
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
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Service $service)
    {
        return view('admin.service.edit', [
            'service' => $service,
            'categories' => Category::where('status', 1)->where('type', 2)->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Service $service)
    {
        $request->merge([
            'slug' => (string)Str::of($request->input('slug') ?? $request->input('name'))->slug()
        ]);

        $rules = $this->rules();

        $rules['model']['unique'] = Rule::unique('services', 'model')->ignore($service->id);
        $rules['slug']['unique'] = Rule::unique('services', 'slug')->ignore($service->id);

        $validated = $request->validate($rules);

        if ($service->update($validated)) {
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.edit.success')
                ],
                'redirect' => relative_route('admin.services')
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
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(function ($query) {
                    return $query->where('status', 1)->where('type', 2);
                })
            ],
            'name' => 'required|string|max:255',
            'model' => [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                'unique' => Rule::unique('services', 'model')
            ],
            'slug' => [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                'unique' => Rule::unique('services', 'slug')
            ],
            'price' => 'required|numeric|between:0,1000000',
            'content' => 'required',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'original_price' => 'required|numeric|between:0,1000000',
            'download' => 'required|numeric|between:0,1000000',
            'upload' => 'required|numeric|between:0,1000000',
        ];
    }
}
