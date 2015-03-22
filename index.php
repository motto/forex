 <?php   
define('DS', '\\');
//define('DS', '/'); //ha linuxon fut
// futtatás:   C:\wamp\bin\php\php5.5.12\php.exe G:\www\index.php
include 'fuggvenyek'.DS.'regresszio.php';
include 'fuggvenyek'.DS.'db.php';
$vRegression = new CRegressionLinear($vSales);
//$vNextQuarter = $vRegression->predict(); // return the forecast for next period

echo 'vregessio:'. $vRegression->predict(100); //viszatér a századik xhez tartozó y értékkel
//echo '<br/>vNextQuarter:'.$vNextQuarter;



?>

