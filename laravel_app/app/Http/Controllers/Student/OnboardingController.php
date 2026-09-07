<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function complete(Request $request)
    {
        $user = auth()->user();
        if ($user && $user->role === 'siswa') {
            $user->has_seen_onboarding = true;
            $user->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 403);
    }
}
