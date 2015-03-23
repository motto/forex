 <?php
//include 'fuggvenyek'.DS.'regresszio.php';
include 'db_fgv.php';
$vRegression = new CRegressionLinear($vSales);
$db=DB::connect();
 $pair='csv_EURUSD/';
 $datum_mappa='2014/08/03'; //$dir=$pair.$datum_mappa;//kibontandó mappa a dir_nyit() függvény használja
 $www_mappa='g:/www/forex'; // nem kell könyvtár elválasaztó a végére:/  !!!!!!!!!!

 class Stat{
 $tabla	='';
 $intervallum=1;
 $aktualis_perc=0;
 $intervallum_db=1;
 $ora='';
function __construct($tabla,$intervallum)
{
	$this->tabla = $tabla;
	$this->intervallum = $intervallum;
}
function futtat($file){
	while (($line = fgetcsv($file)) !== FALSE) {

		if($perc>$this->aktualis_perc) {
			$this->feldolgoz($data);
			$this->aktualis_perc=$perc;
			$data=[];
		}
		//datum,időbélyeg,bid,bidvol,ask,askvol
		$idobelyeg=round($line[1]);
		$datum=$line[0];
		$perc=substr($datum, 14, 2);
		$this->ora=substr($datum, 11, 2);
		$data['bid'][]=$line[2];
		$data['bidvol'][]=$line[3];
		$data['bid_ido'][]=[$bid,$idobelyeg];
	}
	$this->feldolgoz($data);
}

	 function feldolgoz($data) {
		 $rekord['atlag_ar'] = atlag($data['bid']);
		 $rekord['datum'] = $datum;
		 ADAT::beszur_tombbol('egyperces',$rekord , $mezok = 'all');
		 $aktualis_perc=$perc;
		 $adattomb=array();

	 }


 }



 function dir_nyit($pair,$datum_mappa){
	 global $www_mappa;
	 global $out_elotag ;
	 $dir=$pair.$datum_mappa;//kibontandó mappa
	 $datum = str_replace('-', '/',$datum_mappa );
	 $stat1= new Stat('egyperces',1,$file);
	 //$stat5= new Stat('otperces',5,$file);
	 if(is_dir($www_mappa.'/'.$dir))
	 {
		 $dh  = opendir($www_mappa.'/'.$dir);
		 while (false !== ($filename = readdir($dh)))
		 {
			 if($filename=="." or $filename=="..") {}
			 else{
					 if (is_dir($www_mappa.'/'.$dir .'/'.$filename)) {
						 dir_nyit($pair,$datum_mappa.'/'.$filename);
					 } else {
						 echo "file:" .$filename. "\n";
						// $ora=substr($filename, 0, 2);
						 $file = fopen($www_mappa.'/'.$dir .'/'.$filename, 'r');
						 $stat1->futtat($file);
						 }
					 }
			 	}

	 }else{echo 'hiba nem létező könyvtárnév'.$www_mappa.'/'.$dir;}
 }

//echo 'vregessio:'. $vRegression->predict(100); //viszatér a századik xhez tartozó y értékkel

function atlag($array) {
$average = array_sum($array) / count($array);
return $average;
}
function meredekseg($array) {
$vRegression = new CRegressionLinear($array);
$elso=$vRegression->predict(1);
$utolso=$vRegression->predict(count($array);
$meredekseg=$utolso-$elso;
return $meredekseg;
}
function beir($rekord) {
$average = array_sum($array) / count($array);
}



dir_nyit($pair,$datum_mappa);

?>

