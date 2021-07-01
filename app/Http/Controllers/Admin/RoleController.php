<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ability;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin.role.list', ['roles' => Role::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.role.add', ['abilities' => Ability::all()->groupBy('group')]);
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

        $abilities = $validated['abilities'];
        unset($validated['abilities']);

        if ($role = Role::create($validated)) {
            $role->attachAbilities($abilities);
            return response()->json([
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.insert.success')
                ],
                'redirect' => relative_route('admin.roles')
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
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Role $role)
    {
        return view('admin.role.edit', [
            'role' => $role,
            'roleAbilities' => $role->abilities->pluck('key')->toArray(),
            'abilities' => Ability::all()->groupBy('group')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate($this->rules());

        $abilities = $validated['abilities'];
        unset($validated['abilities']);

        if ($role->update($validated)) {
            $role->attachAbilities($abilities);
            return response()->json([
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.edit.success')
                ],
                'redirect' => relative_route('admin.roles')
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
     * Validations
     *
     * @return array
     */
    private function rules()
    {
        return [
            'key' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'abilities' => 'required',
            'abilities.*' => 'exists:abilities,key'
        ];
    }
}
