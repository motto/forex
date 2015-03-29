<?php
# $Id: data_table.example2.php 999 2011-08-05 19:00:48Z lbayuk $
# phplot / contrib / data_table example 2: Line plot with data table on the side
require_once 'phplot.php';
require_once 'contrib/data_table.php';
$file = fopen('05.csv', 'r');
$i=1;
while (($line = fgetcsv($file)) !== FALSE) {
  //$line is an array of the csv elements
  $adat[]=array($i,$i,($line[4]*100000)-135800);
  $i++;
}
/*
for($i=1;$i<1000; $i++){
$line = fgetcsv($file); 
  $adat[]=array($i,$i,($line[4]*100000)-135800);
}*/
//print_r($adat);
fclose($file);

$data = array();
for ($i = 0; $i < 24; $i++)
  $data[] = array('y'.$i, $i, 200* sin(($i+20)/2), $i * $i);

// The $settings array configures the data table:
$settings = array(
    'headers' => array(NULL, 'X', '2Y', 'Y^2'),
    'position' => array(1800, 20),
    'width' => 200,
    'data' => $data,
    'font' => 3,
);

$plot = new PHPlot(1600, 600);
//$plot->SetOutputFile($output_file);
$plot->SetTitle('Line Plot with Data Table on Right Side');
$plot->SetDataColors(array('red',  array(255, 0, 0, 80)));
$plot->SetDataValues($adat);
$plot->SetDataType('data-data');
$plot->SetPlotType('lines');
$plot->SetPlotAreaPixels(NULL, NULL, 1400, NULL);
$plot->SetCallback('draw_graph', 'draw_data_table', $settings);
$plot->SetLegend(array('2Y', 'Y^2'));
$plot->DrawGraph();
