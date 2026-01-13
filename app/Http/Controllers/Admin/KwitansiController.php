<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kwitansi;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KwitansiController extends Controller
{
    // LIST ALL KWITANSI
    public function index()
    {
        $kwitansis = Kwitansi::with('pembayaran.peserta.user')->orderBy('created_at','desc')->paginate(20);
        return view('admin.kwitansi.index', compact('kwitansis'));
    }

    //create kwitansi for pembayaran
    public function create()
    {
        $pembayarans = Pembayaran::with('peserta.user')->orderBy('tanggal','desc')->get();
        return view('admin.kwitansi.create', compact('pembayarans'));
    }

    // STORE
    public function store(Request $request)
    {
        $data = $request->validate([
            'pembayaran_id' => 'required|exists:pembayarans,id',
            'nomor_kwitansi' => 'required|string|unique:kwitansis,nomor_kwitansi',
            'file_pdf' => 'required|file|mimes:pdf|max:5120'
        ]);

        $path = $request->file('file_pdf')->store('kwitansi');
        $data['file_pdf'] = $path;

        Kwitansi::create($data);

        return redirect()->route('admin.kwitansi.index')->with('success','Kwitansi dibuat.');
    }

    // DELETE KWITANSI
    public function destroy(Kwitansi $kwitansi)
    {
        if ($kwitansi->file_pdf) {
            Storage::delete($kwitansi->file_pdf);
        }
        $kwitansi->delete();
        return redirect()->route('admin.kwitansi.index')->with('success','Kwitansi dihapus.');
    }

    /**
     * Download kwitansi file securely
     */
    public function download(Kwitansi $kwitansi)
    {
        if (!$kwitansi->file_pdf || !Storage::exists($kwitansi->file_pdf)) {
            abort(404, 'File kwitansi tidak ditemukan.');
        }

        $filename = $kwitansi->nomor_kwitansi . '.pdf';
        return Storage::download($kwitansi->file_pdf, $filename);
    }
    // Regenerate removed — admin can upload new kwitansi manually
}
