<?php


class search{

	protected $objPHPExcel;
	protected $cellValues;


	function __construct($filename){
		$this->objPHPExcel = PHPExcel_IOFactory::load($filename);
	}

    public function getCellValues($force = false){
        if ( !is_null($this->cellValues) && $force === false ){
            return $this->cellValues;
        }
        $currentIndex = $this->objPHPExcel->getActiveSheetIndex();
        $this->objPHPExcel->setActiveSheetIndex(0);


        $sheet = $this->objPHPExcel->getActiveSheet();
        $highestColumn = $sheet->getHighestColumn(); 
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
        $highestRow= $sheet->getHighestRow();

        $this->cellValues = array();
        for ( $i =0 ; $i < $highestColumnIndex; $i++ ){
            $column = PHPExcel_Cell::stringFromColumnIndex($i);
            for ( $j = 1; $j <= $highestRow; $j++ ){
                $this->cellValues[$column . $j] = $sheet->getCellByColumnAndRow($i, $j)->getValue();
            }
        }
        $this->objPHPExcel->setActiveSheetIndex($currentIndex);
        return $this->cellValues;
    }

    public function getCellByValue($search) {
        $nonPrintableChars = array("\n", "\r", "\t", "\s");
        $search = str_replace($nonPrintableChars, '', $search);
        foreach ( $this->getCellValues() as $cell => $value ){
            if ( strcasecmp(str_replace($nonPrintableChars, '', $value), $search) == 0  ){
                return $cell;
            }
        }
        return false;
    }
}


?>