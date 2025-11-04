<?php

namespace App;

use App\Models\User;
use App\Models\Hotel;

class AssignManager
{
    public static function assign($userEmail, $hotelId)
    {
        $user = User::where('email', '=', $userEmail)->first();
        if (!$user) {
            return "User with email {$userEmail} not found";
        }

        $hotel = Hotel::find($hotelId);
        if (!$hotel) {
            return "Hotel with ID {$hotelId} not found";
        }

        $user->update(['role' => 'manager']);

        $hotel->update(['manager_id' => $user->id]);

        return "User {$user->full_name} assigned as manager to hotel {$hotel->title}";
    }
}