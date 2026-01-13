<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peserta;
use App\Models\User;

class PklController extends Controller
{
    public function index(Request $request)
    {
        $query = Peserta::with('user')->where('jenis', 'pkl');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('user', fn($b) => $b->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%"));
        }

        $pesertas = $query->orderBy('created_at','desc')->paginate(20)->withQueryString();
        return view('admin.peserta.index', compact('pesertas'));
    }

    public function create()
    {
        $jenis = 'pkl';
        return view('admin.peserta.create', compact('jenis'));
    }
}
