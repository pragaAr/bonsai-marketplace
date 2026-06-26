<?php

namespace App\Services;

use App\Models\Pricelist;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Service untuk generate PDF reports.
 * Menggunakan DomPDF yang ringan dan ramah shared hosting.
 */
class PdfService
{
    /**
     * Generate PDF ringkasan pricelist vendor.
     *
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generateVendorPricelist(User $user)
    {
        $pricelists = $user->pricelists()
            ->with('commodity')
            ->active()
            ->orderBy('commodity_id')
            ->get();

        $pdf = Pdf::loadView('pdf.vendor-pricelist', [
            'user' => $user,
            'pricelists' => $pricelists,
            'generatedAt' => now()->format('d F Y H:i'),
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf;
    }

    /**
     * Generate PDF laporan admin (komparasi harga).
     *
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generateAdminReport(array $filters = [])
    {
        $query = Pricelist::with(['user', 'commodity'])->active();

        if (! empty($filters['category'])) {
            $query->whereHas('commodity', fn ($q) => $q->where('category', $filters['category']));
        }

        if (! empty($filters['vendor_id'])) {
            $query->where('user_id', $filters['vendor_id']);
        }

        $pricelists = $query->orderBy('commodity_id')->get();

        // Kelompokkan berdasarkan komoditas
        $grouped = $pricelists->groupBy('commodity_id');

        $pdf = Pdf::loadView('pdf.admin-report', [
            'grouped' => $grouped,
            'filters' => $filters,
            'generatedAt' => now()->format('d F Y H:i'),
        ]);

        $pdf->setPaper('a4', 'landscape');

        return $pdf;
    }
}
