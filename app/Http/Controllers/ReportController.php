<?php

namespace App\Http\Controllers;

use App\Models\Mob;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function show($slug)
    {
        $mob = Mob::where("slug", $slug)->firstOrFail();
        $details = json_decode($mob->storage);
        return view('report',compact('mob', 'details'));

    }
}
