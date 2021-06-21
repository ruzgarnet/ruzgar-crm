<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ContractType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin.category.list', ['categories' => Category::orderBy('id', 'DESC')->get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.category.add', [
            'contractTypes' => ContractType::all(),
            'categories' => Category::where('status', 1)->get(),
            'types' => Category::getTypes()
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
            'slug' => (string)Str::of($request->input('slug') ?? $request->input('name'))->slug(),
            'key' => (string)Str::of($request->input('key'))->slug()
        ]);

        $rules = $this->rules();

        $validated = $request->validate($rules);

        if (Category::create($validated)) {
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.insert.success')
                ],
                'redirect' => relative_route('admin.categories')
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
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Category $category)
    {
        return view('admin.category.edit', [
            'category' => $category,
            'contractTypes' => ContractType::all(),
            'categories' => Category::where('status', 1)->where('id', '!=', $category->id)->get(),
            'types' => Category::getTypes()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->merge([
            'slug' => (string)Str::of($request->input('slug') ?? $request->input('name'))->slug(),
            'key' => (string)Str::of($request->input('key'))->slug()
        ]);

        $rules = $this->rules();

        $rules['key']['unique'] = Rule::unique('categories', 'key')->ignore($category->id);
        $rules['slug']['unique'] = Rule::unique('categories', 'slug')->ignore($category->id);

        $validated = $request->validate($rules);

        if ($category->update($validated)) {
            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.edit.success')
                ],
                'redirect' => relative_route('admin.categories')
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
            'contract_type_id' => 'required|exists:contract_types,id',
            'type' => 'required|in:' . Category::getTypes(true),
            'parent_id' => 'nullable|exists:categories,id',
            'key' => [
                'nullable',
                'string',
                'alpha_dash',
                'max:255',
                'unique' => Rule::unique('categories', 'key')
            ],
            'name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'alpha_dash',
                'max:255',
                'unique' => Rule::unique('categories', 'slug')
            ],
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255'
        ];
    }
}
