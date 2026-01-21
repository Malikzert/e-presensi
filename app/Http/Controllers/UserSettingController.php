<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSettingController extends Controller
{
    public function update(Request $request)
        {
            $user = auth()->user();

            $user->update([
                'notif_pengingat'         => $request->has('notif_pengingat'),
                'notif_status_pengajuan' => $request->has('notif_status_pengajuan'),
                'track_lokasi'           => $request->has('track_lokasi'),
            ]);

            return back()->with('success', 'Pengaturan diperbarui');
        }
}