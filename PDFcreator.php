<?php
require($_SERVER["DOCUMENT_ROOT"].'/-/fpdf.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/-/config.php');

class PDF extends FPDF 
{
	//Load data
	function LoadData()
	{
		$string="";
		$fileCount=0;
		$filePath= $_SERVER["DOCUMENT_ROOT"].IMAGEFOLDER; # Specify the path you want to look in. 
		$dir = opendir($filePath); # Open the path
		while ($file = readdir($dir)) { 
		  if (eregi("\.(gif|jpg)",$file)) { # Look at only files with a .php extension
		    $Images[] = $file;
		  }
		}
		closedir($dir);
		return $Images;
	}	

//Simple table
	function BasicTable($data)
	{
	    $colCount = 0;
		$rowCount = 0;
		$imgX = 15; // across
		$imgY = 15; // up down
	    foreach($data as $col) {
			$colCount++;
	       // $this->Cell(40,7,$col,1);
	 		$this->Image($_SERVER["DOCUMENT_ROOT"].IMAGEFOLDER.$col,$imgX,$imgY,35,35);

			$imgX+=40;
						
			if($colCount == 4) { // every X columns make a new line
				$this->Ln(50);		
				$colCount = 0;
				$imgY+=40;
				$imgX=15;
				$rowCount+=1;
			}
			if($rowCount == 6) { // lets start a new page
				$this->AddPage();
				$imgX = 15; // across
				$imgY = 15; // up down	
				$colCount = 0;
				$rowCount = 0;
			}
		}
	}	
	
//Page header
	function Header() {
		
		global $title;

		$this->SetY(1);
	    //Arial bold 15
	    $this->SetFont('Arial','B',10);
	    //Calculate width of title and position
	    $w=$this->GetStringWidth($title)+6;
	    $this->SetX((210-$w)/2);
	    //Thickness of frame (1 mm)
	    $this->SetLineWidth(0);
	    //Title
	    $this->Cell($w,10,$title,0,0,'C');
	    //Line break
	    $this->Ln(10);
	}
	
	//Page footer
	function Footer() {
		// Position at 1.5cm from bottom
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Page number
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}

$pdf=new PDF();

$title=PAGETITLE;
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true,20);

//Data loading
$data=$pdf->LoadData();
$pdf->SetFont('Arial','',14);
$pdf->BasicTable($data);

$pdf->SetFont('Arial','B',16);
$pdf->Output($_SERVER["DOCUMENT_ROOT"].OUTPUTFOLDER.OUTPUTNAME, "F");
?>