<?php
namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use Illuminate\Http\Request;
use App\Models\Perbaikan;
use App\Models\Pelanggan;
use App\Models\Garansi;
use Carbon\Carbon;

class TrackingController extends Controller
{
    public function index()
    {
        return view('tracking.index');
    }
    public function check(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
        ]);

        $pelanggan = Pelanggan::where('nomor_telp', $request->key)->first();

        if (!$pelanggan) {
            return redirect()->route('tracking.index')
                ->with('error', 'Nomor telepon tidak ditemukan. Mohon periksa kembali nomor telepon Anda.');
        }

        $allPerbaikan = Perbaikan::where('pelanggan_id', $pelanggan->id)
            ->with(['user', 'pelanggan', 'garansi'])
            ->orderBy('tanggal_perbaikan', 'desc')
            ->get()
            ->map(function ($item) {
                $item->tanggal_formatted = DateHelper::formatTanggalIndonesia($item->tanggal_perbaikan);
                return $item;
            });

        if ($allPerbaikan->isEmpty()) {
            return redirect()->route('tracking.index')
                ->with('error', 'Tidak ada perbaikan yang ditemukan untuk nomor telepon ini.');
        }


        $perbaikanAktif = collect();

        foreach ($allPerbaikan as $perbaikan) {
            if ($perbaikan->status === 'Selesai') {
                $tanggalSelesai = Carbon::parse($perbaikan->updated_at);
                $hariIni = Carbon::now();
                $selisihHari = $tanggalSelesai->diffInDays($hariIni);

                if ($selisihHari <= 7) {
                    $perbaikanAktif->push($perbaikan);
                }
            } else {
                $perbaikanAktif->push($perbaikan);
            }
        }

        return view('tracking.index', compact('perbaikanAktif', 'pelanggan'));
    }
}
