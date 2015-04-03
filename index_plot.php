<?php
# $Id: data_table.example2.php 999 2011-08-05 19:00:48Z lbayuk Pisznice-nyereg
/*Égett-szállás, Orfű
Szakadás, Orfű
Száraz-kút-pihenő, Mecsek
Éger-völgy, Mecsek
Muskátli vendéglő, Orfű*/
# phplot / contrib / data_table example 2: Line plot with data table on the side
require_once 'phplot.php';
//$data['x'] =[];
$data[]=[1,2,3,1];
$data[]=[11,5,3,4];
$data[]=[13,2,3,1];
$data[]=[14.2,5,3,4];
//$data[]=[11,15,14,11.5,7,1,44,22.8];
 /*
$end = M_PI * 2.0;
$delta = $end / 15.0;
$data = array();
for ($x = 0; $x <= $end; $x += $delta)
{$data[] = array('', $x, sin($x), cos($x));}
*/

$plot = new PHPlot(1000, 600);
$plot->SetDataValues($data);
//$plot->TuneYAutoTicks(1, 'decimal',true);
$plot->SetXScaleType('log');
$plot->DrawGraph();

/*
//$plot->SetOutputFile($output_file);
$plot->SetTitle('Line Plot with Data Table on Right Side');
$plot->SetDataColors(array('red',  array(255, 0, 0, 80)));

$plot->SetDataType('data-data');
$plot->SetPlotType('lines');
$plot->SetPlotAreaPixels(NULL, NULL, 1400, NULL);
$plot->SetCallback('draw_graph', 'draw_data_table', $settings);
$plot->SetLegend(array('2Y', 'Y^2'));

// The $settings array configures the data table:
$settings = array(
    'headers' => array(NULL, 'X', '2Y', 'Y^2'),
    'position' => array(1800, 20),
    'width' => 200,
    'data' => $data,
    'font' => 3,
);



-*/