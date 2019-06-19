<?php
// outputs a pdf file

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['grn'])) {
    $grn = trim($_GET['grn']);

    $file = realpath("../challans/" . $grn . '.pdf');

    if (file_exists($file)) {
        header('Content-Type: application/pdf');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }
}