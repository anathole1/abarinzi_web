<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    public function searchMembers(Request $request)
    {
        $searchTerm = $request->input('q');
        if (!$searchTerm) {
            return response()->json(['items' => []]);
        }

        $query = User::query()
            ->with('memberProfile')
            ->whereHas('memberProfile', fn ($q) => $q->where('status', 'approved'))
            ->orderBy('name');

        $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('email', 'LIKE', "%{$searchTerm}%")
              ->orWhereHas('memberProfile', function ($mpQuery) use ($searchTerm) {
                  $mpQuery->where('accountNo', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('phone_number', 'LIKE', "%{$searchTerm}%")
                          ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%{$searchTerm}%");
              });
        });

        $users = $query->limit(20)->get();

        $formattedUsers = $users->map(function ($user) {
            $accountInfo = $user->memberProfile->accountNo ? " | Acc: " . $user->memberProfile->accountNo : "";
            return ['id' => $user->id, 'text' => "{$user->name} ({$user->email}){$accountInfo}"];
        });

        return response()->json(['items' => $formattedUsers]);
    }

    public function getMemberCategoryAmounts(User $user)
    {
        if ($user->memberProfile && $user->memberProfile->memberCategory) {
            return response()->json([
                'monthly' => $user->memberProfile->memberCategory->monthly_contribution,
                'social' => $user->memberProfile->memberCategory->social_monthly_contribution,
            ]);
        }
        return response()->json(null, 404);
    }
}