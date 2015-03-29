 <?php
//include 'fuggvenyek'.DS.'regresszio.php';
include 'db_fgv.php';
$vRegression = new CRegressionLinear($vSales);
$db=DB::connect();
 $pair='csv_EURUSD/';
 $datum_mappa='2014/08/03'; //$dir=$pair.$datum_mappa;//kibontandó mappa a dir_nyit() függvény használja
 $www_mappa='g:/www/forex'; // nem kell könyvtár elválasaztó a végére:/  !!!!!!!!!!

 class Stat{
	 // paraméterek------------------------
 $tabla	='';
 $intervallum=1;
 $aktualis_perc=0;
 $intervallum_db=1;
 $ora='';
 $munka_tomb=[];

function __construct($tabla,$intervallum,$ora)
{
	$this->tabla = $tabla;
	$this->intervallum = $intervallum;
	$this->ora = $ora;
}
function futtat($file){
	while (($line = fgetcsv($file)) !== FALSE) {

		if($perc>$this->aktualis_perc) {
			$this->feldolgoz();
			$this->aktualis_perc=$perc;
			$this->munka_tomb=[];

		}
		//datum,időbélyeg,bid,bidvol,ask,askvol
		$idobelyeg=round($line[1]);
		$datum=$line[0];
		$perc=substr($datum, 14, 2);
		$this->ora=substr($datum, 11, 2);
		$this->munka_tomb['bid'][]=$line[2];
		$this->munka_tomb['vol'][]=$line[3];
		$this->munka_tomb['bid_ido'][]=[$line[2],$idobelyeg];
		$this->munka_tomb['vol_ido'][]=[$line[3],$idobelyeg];
	}
	$this->feldolgoz();
	$this->aktualis_perc=0;
	$this->munka_tomb=[];
}
	 function feldolgoz($munka_tomb,$elotag='') {

		 $rekord['datum'] = $datum;
		 ADAT::beszur_tombbol('egyperces',$rekord , $mezok = 'all');
	 }
	 function szamol($munka_tomb,$elotag='') {
		 $min=0;$max=0;$db=1;$elozo=0;
		 $rekord['meredekseg']=$this->meredekseg($this->munka_tomb['bid_ido']);
		 foreach ($munka_tomb as $munka) {
			 if($db==1){$min=$munka;$max=$munka;$elozo=$munka;}
			 if($munka>$max){$rekord[$elotag.'max']=$munka;}
			 if($munka<$min){$rekord[$elotag.'min']=$munka;}
			 =$ossz+$munka;
			 $db++;

		 }
		 $rekord[$elotag.'db']=$db;
		 $rekord[$elotag.'atlag']=$ossz/$db;
		 $rekord[$elotag.'szoras']=0;
		 $rekord[$elotag.'szoras_atlaghoz']=0;
	return $rekord;
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
=$utolso-$elso;
return $meredekseg;
}
function beir($rekord) {
$average = array_($array) / count($array);
}

 function calculate_median($arr) {
	 sort($arr);
	 $count = count($arr); //total numbers in array
	 $middleval = floor(($count-1)/2); // find the middle value, or the lowest middle value
	 if($count % 2) { // odd number, middle is the median
		 $median = $arr[$middleval];
	 } else { // even number, calculate avg of 2 medians
		 $low = $arr[$middleval];
		 $high = $arr[$middleval+1];
		 $median = (($low+$high)/2);
	 }
	 return $median;
 }
 function calculate_median($arr)
 {
	 rsort($array);
	 $middle = round(count($arr) / 2);
	 $total = $array[$middle - 1];
	 dir_nyit($pair, $datum_mappa);
	 return $total;
 }

 function modus($arr)
 {
	 $v = array_count_values($arr);
	 arsort($v);
	 foreach($v as $k => $v){$total = $k; break;}
	 return $total;
 }


?>

