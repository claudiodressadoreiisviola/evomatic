<?php
// include mysql database configuration file
require __DIR__ . '/../../MODEL/product.php';
header("Content-type: application/json; charset=UTF-8");
 
if (isset($_POST['submit']))
{
 
    // Listo i tipi MIME di file csv
    $fileMimes = array(
        'text/x-comma-separated-values',
        'text/comma-separated-values',
        'application/octet-stream',
        'application/vnd.ms-excel',
        'application/x-csv',
        'text/x-csv',
        'text/csv',
        'application/csv',
        'application/excel',
        'application/vnd.msexcel',
        'text/plain'
    );
 
    // Controllo che il file sia effettivamente un csv
    if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $fileMimes))
    {
 
        // Apro il file in sola lettura
        $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
 
        // Salto la prima linea che contiene i tipi di dato
        fgetcsv($csvFile);
 
        // Prendo i dati dal csv una linea alla volta e li carico nel database
        while (($getData = fgetcsv($csvFile, 10000, ",")) !== FALSE)
        {
            // Get row data
            $name = $getData[0];
            $surname = $getData[1];
            $email = $getData[2];
            $password = $getData[3];
            $year = $getData[4];
            $section = $getData[5];
            $schoolYear = $getData[6];
            $active = $getData[7];
            
            $result = registerUser($name, $surname, $email, $password, $year, $section, $schoolYear, $active);
        }
 
        // Close opened CSV file
        fclose($csvFile);       
    }
    else
    {
        echo "Please select valid file";
    }
}
?>