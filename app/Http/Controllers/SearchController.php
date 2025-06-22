<?php

namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use App\Models\Perbaikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function suggestions(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            return response()->json([]);
        }

        $perbaikan = Perbaikan::with(['pelanggan'])
            ->where(function ($q) use ($query) {
                $q->where('id', 'like', "%{$query}%")
                    ->orWhere('nama_device', 'like', "%{$query}%")
                    ->orWhereHas('pelanggan', function ($subq) use ($query) {
                        $subq->where('nama_pelanggan', 'like', "%{$query}%")
                            ->orWhere('nomor_telp', 'like', "%{$query}%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $suggestions = [];

        foreach ($perbaikan as $item) {
            $url = $this->getDetailUrlByRole($item->id);

            $suggestions[] = [
                'id' => $item->id,
                'kode_perbaikan' => $item->id,
                'nama_device' => $item->nama_device,
                'nama_pelanggan' => $item->pelanggan->nama_pelanggan ?? 'N/A',
                'tanggal' => DateHelper::formatTanggalIndonesia($item->tanggal_perbaikan),
                'status' => $item->status,
                'url' => $url
            ];
        }

        return response()->json($suggestions);
    }

    private function getDetailUrlByRole($perbaikanId)
    {
        $user = Auth::user();

        if (!$user) {
            return '#';
        }

        if ($user->role === 'admin') {
            return route('admin.transaksi.show', $perbaikanId);
        } elseif ($user->role === 'kepala_toko') {
            return route('laporan.show', $perbaikanId);
        } elseif ($user->role === 'teknisi') {
            return route('teknisi.transaksi.show', $perbaikanId);
        }

        return route('admin.transaksi.show', $perbaikanId);
    }

    public function search(Request $request)
    {
        $searchTerm = $request->get('search');
        $user = Auth::user();

        if (empty($searchTerm)) {
            return $this->redirectToDashboard($user);
        }

        $perbaikanList = Perbaikan::with(['pelanggan', 'teknisi'])
            ->where(function ($query) use ($searchTerm) {
                $query->where('id', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('nama_device', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('keluhan', 'LIKE', "%{$searchTerm}%")
                    ->orWhereHas('pelanggan', function ($q) use ($searchTerm) {
                        $q->where('nama_pelanggan', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('nomor_telp', 'LIKE', "%{$searchTerm}%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $viewName = $this->getSearchViewByRole($user);

        return view($viewName, compact('perbaikanList', 'searchTerm'));
    }

    private function redirectToDashboard($user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'kepala_toko') {
            return redirect()->route('kepala-toko.dashboard');
        } elseif ($user->role === 'teknisi') {
            return redirect()->route('teknisi.dashboard');
        }

        return redirect()->route('admin.dashboard');
    }

    private function getSearchViewByRole($user)
    {
        if ($user->role === 'admin') {
            return 'admin.search_results';
        } elseif ($user->role === 'kepala_toko') {
            return 'kepala_toko.search_results';
        } elseif ($user->role === 'teknisi') {
            return 'teknisi.search_results';
        }

        return 'admin.search_results';
    }
}
