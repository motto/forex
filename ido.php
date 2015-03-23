<?php
echo date('m-d-Y',1407189600).'</br>';
//echo date('Y-m-d H:i:s',mktime(0,0,0,07,07,2014)).'</br>'; //év-hónap-nap
//echo strtotime(05-05-2014).'</br>';
//strtotime(): linux időbélyeggel tér vissza angol tipusú dátumból pl.: hónap-nap-év
//echo strtotime(str_replace('/', '-', '06/06/2014'));


echo  strtotime('2014-11-01 00:00:00').'</br>' ;//ez ajó
echo  strtotime('09/04/2014 00:00:00').'</br>' ;//ez ajó
//echo mktime(0,0,0,04,08,2014) ;
//strtotime(): linux időbélyeggel tér vissza angol tipusú dátumból pl.: hónap/nap/év

$start="02-25-2016"; //hónap-nap-év
$end="03-02-2016"; //egy nappal előtte megáll
//strtotime(): linux időbélyeggel tér vissza angol tipusú dátumból pl.: hónap/nap/év

/*1388530800

while($start < $end) {
echo $start.'</br>';
$date1 = str_replace('-', '/', $start);

for($i = strtotime($date1); $i < strtotime($date1 . "+1 days"); $i += 3600) {
//00-23 ig számol
echo date('H',$i).'-';



$starttime = gmmktime(0,0,0,$startmonth,1,$startyear);
echo date("Y-m-d H:i:s", strtotime ("+1 hour"));
    $hour = gmstrftime('%H',$i);
	$localfile = $localpath . $hour . "h_ticks.bi5";
	if (filesize($localfile) > 0) { $orak=$orak.decode_ducascopy_bi5($localfile,$i);}
}
 strtotime($date1).'</br>';;
$start= date('m-d-Y',strtotime($date1 . "+1 days"));
}/*