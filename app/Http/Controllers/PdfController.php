<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function viewPdf($filename)
    {
        $pdfPath = storage_path('app/pdf/' . $filename); 

        if (file_exists($pdfPath)) {
            return view('pdf_viewer', ['pdfPath' => $pdfPath]);
        } else {
            abort(404, 'PDF not found');
        }
    }
}
