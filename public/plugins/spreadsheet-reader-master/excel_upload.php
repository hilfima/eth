<?php


require('php-excel-reader/excel_reader2.php');

require('SpreadsheetReader.php');


  $mimes = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.oasis.opendocument.spreadsheet'];



    $uploadFilePath = 'Gaji.xlsx';


    $Reader = new SpreadsheetReader($uploadFilePath);


    $totalSheet = count($Reader->sheets());


    echo "You have total ".$totalSheet." sheets".


    $html="<table border='1'>";

    $html.="<tr><th>Title</th><th>Description</th></tr>";


    /* For Loop for all sheets */

    for($i=0;$i<$totalSheet;$i++){


      $Reader->ChangeSheet($i);


      foreach ($Reader as $Row)

      {

        $html.="<tr>";

        $title = isset($Row[0]) ? $Row[0] : '';

        $description = isset($Row[1]) ? $Row[1] : '';
        $description2 = isset($Row[2]) ? $Row[2] : '';

        $html.="<td>".$title."</td>";

        $html.="<td>".$description."</td>";
        $html.="<td>".$description2."</td>";

        $html.="</tr>";


        //$query = "insert into items(title,description) values('".$title."','".$description."')";


        //$mysqli->query($query);

       }


    }


    $html.="</table>";

    echo $html;

    echo "<br />Data Inserted in dababase";


  


?>