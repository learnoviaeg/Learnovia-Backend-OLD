<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NotificationSetting;

class NotificationSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notificationSetting=NotificationSetting::where('type','attendance')->first();
        return response()->json(['message' => 'Notification setting.','body' => $notificationSetting], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'after_min' => 'required|integer',
            'roles' => 'nullable',
            'roles.*' => 'exists:roles,id',
            'users' => 'nullable',
            'users.*' => 'exists:users,id',
            'type' => 'required|string|in:attendance,fees'
        ]);

        NotificationSetting::updateOrCreate([
            'after_min' => $request->after_min,
            'roles' => json_encode($request->roles),
            'users' => json_encode($request->users),
            'type' => $request->type
        ]);

        return response()->json(['message' => 'Notification was set.','body' => null], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
