<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionCheckController extends Controller
{
    public function check(Request $request)
    {
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            
            if ($user->role === 'siswa') {
                if (!$user->last_seen_at || \Carbon\Carbon::now()->diffInMinutes($user->last_seen_at) >= 1) {
                    $user->update(['last_seen_at' => \Carbon\Carbon::now()]);
                }
            }

            return response()->json([
                'authenticated' => true,
                'is_active' => (bool) $user->is_active,
            ]);
        }

        return response()->json(['authenticated' => false]);
    }
}
