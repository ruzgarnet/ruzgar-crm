<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestMessage;
use App\Classes\Telegram;
use App\Models\Role;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RequestMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userrole = request()->user()->role_id;
        return view('admin.request-message.list', [

            'requestMessages' => RequestMessage::where('role_id', $userrole)->get()
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.request-message.add', [

            'roles' => Role::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $validated = $request->validate($this->rules());
        $validated["staff_id"] = $request->user()->staff_id;

        if (RequestMessage::create($validated)) {


            $username = Staff::find($validated["staff_id"]);
            $yetkili = Role::find($validated["role_id"]);
            $durum = trans('tables.request.message.status.1');
            Telegram::send(
                "YetkiTalep",
                trans(
                    'telegram.add_request_message',
                    [
                        'username' => $username->full_name,
                        'role' => $yetkili->name,
                        'status' => $durum
                    ]
                )
            );



            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.insert.success')
                ],
                'redirect' => relative_route('admin.request.messages')
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
     * @param  \App\Models\RequestMessage  $requestMessage
     * @return \Illuminate\Http\Response
     */
    public function show(RequestMessage $requestMessage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RequestMessage  $requestMessage
     * @return \Illuminate\Http\Response
     */
    public function edit(RequestMessage $requestMessage)
    {
        //
        return view('admin.request-message.edit', [
            'requestMessage' => $requestMessage,
            'roles' => Role::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RequestMessage  $requestMessage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RequestMessage $requestMessage)
    {
        //

        $validated = $request->validate(['status'=>'required']);
        if ($requestMessage->update($validated)) {

            $username = Staff::find($requestMessage["staff_id"]);

            $durum = trans('tables.request.message.status.'.$validated["status"]);


            Telegram::send(
                "YetkiTalep",
                trans(
                    'telegram.edit_request_message',
                    [
                        'username' => $username->full_name,
                        'user' => $request->user()->staff->full_name,
                        'status' => $durum
                    ]
                )
            );

            return response()->json([
                'success' => true,
                'toastr' => [
                    'type' => 'success',
                    'title' => trans('response.title.success'),
                    'message' => trans('response.edit.success')
                ],
                'redirect' => relative_route('admin.request.messages')
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
     * @param  \App\Models\RequestMessage  $requestMessage
     * @return \Illuminate\Http\Response
     */
    public function destroy(RequestMessage $requestMessage)
    {
        //
    }


    private function rules()
    {
        return [
            'role_id' => 'required',
            'message' => 'required|string'

        ];
    }
}
