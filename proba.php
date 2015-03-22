<?php
define('DS', '\\');
// futtatás:   C:\wamp\bin\php\php5.5.12\php.exe G:\www\index.php
include 'fuggvenyek'.DS.'regresszio.php';
include 'fuggvenyek'.DS.'db.php';
//include "libchart/libchart/classes/libchart.php";
require_once 'phpplot/phplot.php';
require_once 'phpplot/contrib/data_table.php';
//$vRegression = new CRegressionLinear($vSales);
//echo 'vregessio:'. $vRegression->predict(100); //viszatér a századik xhez tartozó y értékkel

$path="2014_1/01/";
$nap="04";
function csv_tomb($path,$nap) {
$file = fopen($path.'/'.$nap.'.csv', 'r');
// alapértékek------------------------------------------
$line = fgetcsv($file);
$kezdo_1p=round($line[0]);
$bid=$line[1];
$bid_vol=$line[2];
$idobelyeg=round($line[0]);
$adattomb[]=array($bid,$bid_vol);
$bid_tomb[]=$bid;

//*********** sorok kiolvasása **********************************************
for ($i = 1; $i <= 100; $i++){
$line = fgetcsv($file);
$bid=$line[4];
$bid_vol=$line[2];
$idobelyeg=round($line[0]);
//$adattomb[]=array($bid,$bid_vol);
$bid_tomb[]=$bid;
// 1perces tömb

if($idobelyeg>$kezdo_1p+60){
$vRegression = new CRegressionLinear($bid_tomb);

$regressio_tomb='';
$x=0;
while($x < count($bid_tomb)) {
$line_pont=$vRegression->predict($x);
$regressio_tomb[]=$line_pont;
$data[]=array('y'.$x, $x, $line_pont, $bid_tomb[$x]);
$x++;
}
//print_r($data);
$kepszam=1;


//beir($rekord);
$adattomb='';
//echo 'eltelt EGY PERC</br>';
$kezdo_1p=$idobelyeg;



//Define the object
$plot = new PHPlot(800,600);

//Set titles
$plot->SetTitle("A 3-Line Plot\nMade with PHPlot");
$plot->SetXTitle('X Data');
$plot->SetYTitle('Y Data');


//Define some data
$example_data = array(
     array('a',3,4,2),
     array('b',5,'',1),  // here we have a missing data point, that's ok
     array('c',7,2,6),
     array('d',8,1,4),
     array('e',2,4,6),
     array('f',6,4,5),
     array('g',7,2,3)
);
$plot->SetDataValues($example_data);

//Turn off X axis ticks and labels because they get in the way:
$plot->SetXTickLabelPos('none');
$plot->SetXTickPos('none');

//Draw it
$plot->DrawGraph();

$kep_src=$kep_src.'<img src="generated/demo'.$kepszam.'.png"/>';
$kepszam++;
//print_r ($bid_tomb);
return $kep_src;
}
}

//*************sor kiolvasás vége
}
$kep_src=csv_tomb($path,$nap);
//echo $kep;
?>
<!DOCTYPE html>
<html dir="ltr" lang="hu-HU">
<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>
</head>
<body>
<div id="container" style="width:100%; height:400px;"></div>
<div id="container2" style="width:100%; height:400px;"></div>
<script>

$(function () {
    $('#container').highcharts({
    
        series: [{
            name: 'Tokyo',
            data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 139, 9.6, 139, 9.6]
        }, {
            name: 'New York',
            data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 8.6, 2.5]
        }, {
            name: 'London',
            data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
        }]
    });
	    $('#container2').highcharts({
    
        series: [{
         
            name: 'London',
            data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
        }]
    });
});
</script>
</body></html>
