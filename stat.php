 <?php   
define('DS', '\\');
//define('DS', '/'); //ha linuxon fut
// futtatás:  php G:\www\stat.php
include 'fuggvenyek'.DS.'regresszio.php';
include 'fuggvenyek'.DS.'db.php';
//$vRegression = new CRegressionLinear($vSales);

//echo 'vregessio:'. $vRegression->predict(100); //viszatér a századik xhez tartozó y értékkel

function atlag($array) {
$average = array_sum($array) / count($array);
}
function elemez($array) {
$average = array_sum($array) / count($array);
}
function beir($rekord) {
$average = array_sum($array) / count($array);
}

function csv_tomb($path,$nap) {
$file = fopen($path.'/'.$nap.'.csv', 'r');
$line = fgetcsv($file);
//echo round($line[0]);
$kezdo_1p=round($line[0]);
while (($line = fgetcsv($file)) !== FALSE) {
$bid=$line[1];
$bid_vol=$line[2];
$idobelyeg=round($line[0]);
$adattomb[]=array($bid,$bid_vol);
// 1perces tömb
if($idobelyeg>$kezdo_1p+60){
$rekord=elemez($adattomb);
beir($rekord);
$adattomb='';
//echo 'eltelt EGY PERC</br>' ;
$kezdo_1p=$idobelyeg;
}
}
}

function csv_darabol($darab_tomb) {
$file = fopen('G:\www\EURUSD_CSV\201403.csv', 'r');
$l=0;$g=0;
		while(!feof($file)) { 
		$g++;
		 if(in_array($g,$darab_tomb)){
			if(!is_file('G:/www/EURUSD_CSV/egynapos/201403'.$g.'.csv')){
			$outfd = fopen('G:/www/EURUSD_CSV/egynapos/201403'.$g.'.csv','a+');
			fwrite($outfd,$egynap);
			fclose($outfd);
			$egynap='';
			}
		}
		$egynap=$egynap.fgets($file,filesize('G:\www\EURUSD_CSV\201403.csv'));
		//$egynap=$egynap.$line[0].','.$line[1].','.$line[2].','.$line[3].','.$line[4].','.$line[5].','.$line[6].','.$line[7].','.$line[8].','.$line[9].','.$line[10].','.$line[11].','.$line[12]."\n";
	}
}

function csv_darab_kezdet() {
$file = fopen('G:\www\EURUSD_CSV\201403.csv', 'r');
$l=0;$g=0;
	while (($line = fgetcsv($file)) !== FALSE) {
	$g++;
		 if($line[3]> $l){
			$l=$line[3];
			$kezdo_tomb[]=$g;
			}
		}
return $kezdo_tomb;
	}



//$kezdo_ido = microtime(); 
//csv_teszt() ;
//$befejez_ido = microtime(); 
csv_darabol(csv_darab_kezdet());
?>

