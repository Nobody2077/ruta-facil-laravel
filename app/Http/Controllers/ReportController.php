<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    public function __construct(private ReportService $service) {}

    public function resumen()
    {
        $data = $this->service->summary();

        return view('reportes.resumen', compact('data'));
    }

    public function resumenPdf(): Response
    {
        $data = $this->service->summary();

        $pdf = Pdf::loadView('reportes.pdf.resumen', compact('data'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('reporte-resumen-'.now()->format('Y-m-d').'.pdf');
    }

    public function recorridos()
    {
        $data = $this->service->recorridos();

        return view('reportes.recorridos', compact('data'));
    }

    public function recorridosPdf(): Response
    {
        $data = $this->service->recorridos();

        $pdf = Pdf::loadView('reportes.pdf.recorridos', compact('data'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('reporte-recorridos-'.now()->format('Y-m-d').'.pdf');
    }
}
