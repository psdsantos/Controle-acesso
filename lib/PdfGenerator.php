<?php

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfGenerator {
    public static function toPDF($html){
        //$html = file_get_contents('..\app\view\home.html');

        $options = new Options();

        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();
        $dompdf->stream();
    }
}

?>
