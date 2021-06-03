<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin.product.list', ['products' => Product::orderBy('id', 'DESC')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.product.add', [
            'categories' => Category::where('status', 1)->where('type', 1)->get()
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

        if (Product::create($validated)) {
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.insert.success')
                ],
                'redirect' => relative_route('admin.products')
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
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Product $product)
    {
        return view('admin.product.edit', [
            'product' => $product,
            'categories' => Category::where('status', 1)->where('type', 1)->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Product $product)
    {
        $request->merge([
            'slug' => (string)Str::of($request->input('slug') ?? $request->input('name'))->slug()
        ]);

        $rules = $this->rules();

        $rules['model']['unique'] = Rule::unique('products', 'model')->ignore($product->id);
        $rules['slug']['unique'] = Rule::unique('products', 'slug')->ignore($product->id);

        $validated = $request->validate($rules);

        if ($product->update($validated)) {
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.edit.success')
                ],
                'redirect' => relative_route('admin.products')
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
                    return $query->where('status', 1)->where('type', 1);
                })
            ],
            'name' => 'required|string|max:255',
            'model' => [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                'unique' => Rule::unique('products', 'model')
            ],
            'slug' => [
                'required',
                'string',
                'alpha_dash',
                'max:255',
                'unique' => Rule::unique('products', 'slug')
            ],
            'price' => 'required|numeric|between:0,1000000',
            'content' => 'required',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255'
        ];
    }
}
