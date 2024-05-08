<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuestController extends Controller
{
    public function getAccess(Request $request)
    {
        if (!$request->cookie('guest_id') || !$request->cookie('access_token')) {
            $guest = Guest::create([
                'guest_token' => Hash::make(rand(1, 9999999)),
            ]);

            return response()->json([
                'guest_id' => $guest->id,
                'guest_token' => $guest->guest_token,
            ]);
        }

        $guest = Guest::where('id', $request->cookie('guest_id'))
            ->where('guest_token', $request->cookie('guest_token'))
            ->first();

        if (!$guest) {
            return response()->json(['error' => 'Guest not found'], 400);
        }

        return response()->json([
            'guest_id' => $guest->id,
            'guest_token' => $guest->guest_token,
        ]);
    }

    private function isRegistered(Request $request)
    {
        return response()->json($request->json('owner_id') && $request->json('access_token'));
    }
}
