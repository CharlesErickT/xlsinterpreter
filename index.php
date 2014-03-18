<?php


require_once('FirePHPCore/fb.php');
require_once './Classes/PHPExcel.php';
include 'upload.php';
include 'search.php';

ob_start();

session_start();


//error_reporting(E_ERROR | E_PARSE);


if($_SESSION['path'] != ''){
 
    $inputFileName = $_SESSION['path'];
    $search = new search($inputFileName);
    
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);     

    $objReader->setReadDataOnly(true);
    $objPHPExcel = $objReader->load($inputFileName);
    $objWorksheet = $objPHPExcel->getActiveSheet();
    $highestRow = $objWorksheet->getHighestRow();
    $highestColumm = $objWorksheet->getHighestColumn();
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumm);  

    $cellName = array();

    $list = array();
    $rowCell = 1;    

    for ($column = 0; $column < $highestColumnIndex; $column++) {
      $value = $objWorksheet->getCellByColumnAndRow($column, $rowCell)->getValue();
       $list[$column] = $value;
       array_push($cellName, $list[$column]);
    }

    $sizecellName = count($cellName);

    $colArray = array();

    for ($i=0; $i <= $sizecellName; $i++) {  
            $colArray[$i] = substr_replace($search->getCellByValue($cellName[$i]), "", -1);
    }

    $JSONArray = array();
    $JSONArrayFin = array();

    for ($row = 2; $row <= $highestRow; $row++) {
        $dataArray = array();
        for ($i=0; $i <= $sizecellName-1; $i++) { 
        	$data = $objPHPExcel->getActiveSheet()->getCell($colArray[$i].$row)->getValue();
            $dataArray[$cellName[$i]] = $data;            
        }
    	array_push($JSONArray, $dataArray);  
    }
    $jsonArrayConv = json_encode($JSONArray);
    //------------Array For Debug------------/
    //print "<pre>";
    //print_r($cellName);
    //print "</pre>";
    //---------------------------------------/
}																																  
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Excel File Interpreter</title>
        <link href='http://fonts.googleapis.com/css?family=Boogaloo' rel='stylesheet' type='text/css'>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
        <script type="text/javascript" src="js/multiupload.js"></script>
        <script type="text/javascript">
        var config = {
            support : "application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",     // Valid file formats
            form: "demoFiler",                  // Form ID
            dragArea: "dragAndDropFiles",       // Upload Area ID
            uploadUrl: "upload.php"             // Server side upload url
        }
        $(document).ready(function(){
            initMultiUploader(config);
        });
        </script>
        <link href="css/style.css" type="text/css" rel="stylesheet" />
    </head>
    <body lang="en"> 
        <a href="http://charlesericktremblay.com/portfolio.html"><button>Back</button></a>
        <center><h1 class="title">Excel File Interpreter</h1></center>
        <br/>
        <p>Votre tableau doit suivre ce model:</p>
        <img src="images/tutorial.jpg" />
        <br/>
        <br/>
        <div id="dragAndDropFiles" class="uploadArea">
            <h1>Drop XLSX or XLS File Here</h1>
        </div>
        <form name="demoFiler" id="demoFiler" enctype="multipart/form-data">
            <input type="file" name="multiUpload" id="multiUpload" />
            <input type="submit" name="submitHandler" id="submitHandler" value="Upload" class="buttonUpload" />
        </form>
        <div class="progressBar">
            <div class="status"></div>
        </div>
        <br/>
        <br/>
        <!--<form action="arrayType.php" method="post">
            <p>Type de tableaux: </p>
            <select name="arrayType">                                           
                <option value="array">Tableau Standard</option>
                <option value="json">JSON</option>
            </select>
        <input type="submit" value='Afficher'>
        </form>
        <br/>
        <br/>
        <br/>
        <br/>-->
        <table>
            <?php
            if($_SESSION['path'] != ''){
                if($_SESSION['type'] == 'json'){
                    echo "<pre>
                            <form action='arrayType.php' method='get'>
                            Type de tableaux:
                            <select name='arrayType'>                                           
                            <option value='json'>JSON</option>
                            <option value='array'>Tableau Standard</option>
                            </select>
                            <input type='submit' value='Afficher'>
                            </form><br/><br/>";
                    print_r($jsonArrayConv);
                    echo "</pre>";
                    echo "<br/><br/><br/><br/>";
                }else{
                    echo "<pre>
                            <form action='arrayType.php' method='get'>
                            Type de tableaux: 
                            <select name='arrayType'>                                                                      
                            <option value='array'>Tableau Standard</option>
                            <option value='json'>JSON</option> 
                            </select>
                            <input type='submit' value='Afficher'>
                            </form><br/><br/>";
                    print_r($JSONArray);
                    echo "</pre>";
                    echo "<br/><br/><br/><br/>";
                }
            }
            ?>
            </table>
    </body>
</html>
