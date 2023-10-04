<?php

/**
 * @author BoTaXs
 * @copyright 2011
 */
class MyExcel{
    //$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
    public function FontBold($ActiveSheet,$Cell){
        $ActiveSheet->getStyle($Cell)->getFont()->setBold(true);
    }

    public function FontSize($ActiveSheet,$Cell,$Size){
        $ActiveSheet->getStyle($Cell)->getFont()->setSize($Size);
    }
    
    public function FillColor($ActiveSheet,$Cell,$Color){
        $ActiveSheet->getStyle($Cell)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $ActiveSheet->getStyle($Cell)->getFill()->getStartColor()->setARGB($Color);
    }
    
    public function SetBorder($ActiveSheet,$Cell,$Color,$Size){
        switch($Size){
            case '1' : $Style=PHPExcel_Style_Border::BORDER_THICK; break;
            default : $Style=PHPExcel_Style_Border::BORDER_THIN; break;
        }
        $styleThickBrownBorderOutline = array(
	       'borders' => array(
		      'outline' => array(
			     'style' => $Style,
			     'color' => array('argb' => $Color),
		      ),
	       ),
        );
        $ActiveSheet->getStyle($Cell)->applyFromArray($styleThickBrownBorderOutline);   
    }
    
    public function FillColorGradien($ActiveSheet,$Cell,$StartColor,$ToColor){
        $ActiveSheet->getStyle($Cell)->applyFromArray(
            array(
                'fill' => array(
                    'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation'   => 90,
                    'startcolor' => array(
                        'argb' => $StartColor
                    ),
                    'endcolor'   => array(
                        'argb' => $ToColor
                    )
                )
            )
        );
    }
    
    public function MergerCell($ActiveSheet,$Cell){
        $ActiveSheet->mergeCells($Cell);   
    }
    
    public function SetValue($ActiveSheet,$Cell,$Value,$Align,$VAlign){
        $ActiveSheet->getCell($Cell)->setValue($Value);
        switch(strtolower($Align)){
            case 'r' : $Align=PHPExcel_Style_Alignment::HORIZONTAL_RIGHT; break;
            case 'l' : $Align=PHPExcel_Style_Alignment::HORIZONTAL_LEFT; break;
            case 'c' : $Align=PHPExcel_Style_Alignment::HORIZONTAL_CENTER; break;
            default : $Align=PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY; break;
        }
        switch(strtolower($VAlign)){
            case 't' : $VAlign=PHPExcel_Style_Alignment::VERTICAL_TOP; break;
            case 'c' : $VAlign=PHPExcel_Style_Alignment::VERTICAL_CENTER; break;
            case 'm' : $VAlign=PHPExcel_Style_Alignment::VERTICAL_CENTER; break;
            case 'b' : $VAlign=PHPExcel_Style_Alignment::VERTICAL_BOTTOM; break;
        }
        $ActiveSheet->getStyle($Cell)->getAlignment()->setHorizontal($Align);
        $ActiveSheet->getStyle($Cell)->getAlignment()->setVertical($VAlign);
    }
    
    public function SetTitle($ActiveSheet, $Value){
        $ActiveSheet->setTitle($Value);
    }
    
    public function SetWidthColumn($ActiveSheet, $Column, $Value){
        if(trim($Value)=='' || $Value=='auto'){
            $ActiveSheet->getColumnDimension($Column)->setAutoSize(true);   
        }
        else{
            $ActiveSheet->getColumnDimension($Column)->setWidth($Value);
        }
    }
    
    public function SetProtect($ActiveSheet, $Cell, $Status, $Password){
        $ActiveSheet->getProtection()->setSheet($Status);
        $ActiveSheet->protectCells($Cell, $Password);
    }
}


?>