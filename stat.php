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
 public $tabla	='egyperces';
 public $intervallum=1;
 public $intervallum_db=1;
 //public	$perc ='';
// public$ora ='' ;
// public $nap = '';
//public$honap = '';
// public$ev ='' ;

function __construct($tabla='egyperces',$intervallum=1,$ora='00')
{
	$this->tabla = $tabla;
	$this->intervallum = $intervallum;
	$this->ora = $ora;
}
function futtat($file){
	$indulo_perc=0; $munka_tomb=[];$aktualis_perc=0;
	while (($line = fgetcsv($file)) !== FALSE) {
		if($indulo_perc==0){
			$rekord['datum']=substr($line[0], 0, 17).'00';
			$rekord['idobelyeg']=strtotime($rekord['datum']);
		}
		if($indulo_perc<$aktualis_perc) {
			$this->feldolgoz($munka_tomb,$rekord);
			$rekord['datum']=substr($line[0], 0, 17).'00';
			$rekord['idobelyeg']=strtotime($rekord['datum']);
			$aktualis_perc=$indulo_perc;
			$munka_tomb=[];
			$munka_tomb['bid_elozo']=$line[2];
			$munka_tomb['vol_elozo']=$line[3];
		}
		$munka_tomb['bid'][]=$line[2];
		$munka_tomb['vol'][]=$line[3];
		//$this->munka_tomb['bid_ido'][]=[$line[2],$idobelyeg];
		//$this->munka_tomb['vol_ido'][]=[$line[3],$idobelyeg];
	}
	$this->feldolgoz($munka_tomb,$rekord);
	$rekord['datum']=substr($line[0], 0, 17).'00';
	$rekord['idobelyeg']=strtotime($rekord['datum']);
	$aktualis_perc=$indulo_perc;
	$munka_tomb=[];
	$munka_tomb['bid_elozo']=$line[2];
	$munka_tomb['vol_elozo']=$line[3];

}
	 function feldolgoz($munka_tomb,$rekord) {
		 $rekord=$this->bid_szamol($munka_tomb,$rekord);
		 $rekord=$this->vol_szamol($munka_tomb,$rekord);
		 ADAT::beszur_tombbol('egyperces',$rekord , $mezok = 'all');
	 }
	 function  bid_szamol($munka_tomb,$rekord,$elozo=''){
		 $min=0;$max=0;$db=1; $kulonbseg=0;

		 foreach ($munka_tomb as $munka) {
			 if($db==1){$min=$munka;$max=$munka;}
			 if($munka>$max){$max=$munka;}
			 if($munka<$min){$min=$munka;}
			 $ossz =$ossz+$munka;
			 $db++;
			 if(!empty($elozo)){$kulonbseg=$elozo-$munka;
				 if($kulonbseg>$max_kulonbseg){$max_kulonbseg=$kulonbseg;}
			 $ossz_kulonbseg= $ossz_kulonbseg+$kulonbseg;
				 $kulonbseg_tomb[]=$kulonbseg;
			 }
			 $elozo=$munka;

		 }
		 $rekord['szoras']=$ossz_kulonbseg/$db; //szórás átlag
		 $rekord['sz_max']=$munka; //legnagyobb különbség (szórás)
		 $rekord['min']=$munka;
		 $rekord['max']=$munka;
		 $rekord['db']=$db;
		 $rekord['atlag']=$ossz/$db;

		 foreach ($munka_tomb as $munka ) {
			if($munka>$rekord['atlag']){$felso_tomb[]=$munka;}else{$also_tomb[]=$munka;}
			 $meredekseg=$this->meredekseg($felso_tomb);
			 $rekord['felso_m']=$meredekseg['meredekseg'];
			 $rekord['f_utolso']=$meredekseg['utolso'];
			 $meredekseg=$this->meredekseg($also_tomb);
			 $rekord['also_m']=$meredekseg['meredekseg'];
			 $rekord['a_utolso']=$meredekseg['utolso'];
		 }
		 foreach ($kulonbseg_tomb as $munka) {
			 if($munka>$rekord['atlag']){$felso_k_tomb[]=$munka;}else{$also_k_tomb[]=$munka;}
			 $meredekseg=$this->meredekseg($felso_k_tomb);
			 $rekord['felso_k_m']=$meredekseg['meredekseg'];
			 $rekord['f_k_utolso']=$meredekseg['utolso'];
			 $meredekseg=$this->meredekseg($also_k_tomb);
			 $rekord['also_k_m']=$meredekseg['meredekseg'];
			 $rekord['a_k_utolso']=$meredekseg['utolso'];

		 }
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

function meredekseg($array) {
$vRegression = new CRegressionLinear($array);
$elso=$vRegression->predict(1);
$meredekseg['utolso']=$vRegression->predict(count($array);
$meredekseg['meredekseg']=$utolso-$elso;
return $meredekseg;
}






 function atlag($array) {
	 $average = array_sum($array) / count($array);
	 return $average;
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

