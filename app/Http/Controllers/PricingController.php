<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pricing;

class PricingController extends Controller
{
    public function getPricings(Request $request)
    {
        $pricings = Pricing::orderBy('order', 'asc')->get();

        return response()->json($pricings);
    }
}
