<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function generateWarningPDF($reportId)
    {
        $report = Report::find($reportId);

        // HTML-Inhalt für das PDF
        $htmlContent = view('pdf.warning', compact('report'))->render();

        // Dompdf-Optionen konfigurieren
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);

        // HTML in PDF umwandeln
        $dompdf->loadHtml($htmlContent);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // PDF an den Browser senden
        return $dompdf->stream("warning_report_{$reportId}.pdf");
    }
}
