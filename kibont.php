<?php
$dir='EURUSD/2014/08';//kibontandó mappa
$www_mappa='g:/www/forex'; // nem kell könyvtár elválasaztó a végére:/  !!!!!!!!!!
$out_elotag='csv_'; // A kimenő adatok fő könyvtáránk létrehozásához kell
$felulir='nem'; // ha létezik a kimenő file nem csinál semmit ha 'igen' felülírja
$extract = '';
$iswindows = true;
$point = 0.00001;
$csinal='';
$outdir_tomb=explode('/',$out_elotag.$dir);
foreach ( $outdir_tomb as $od ) {
    $csinal=$csinal.'/'.$od;
   if(!is_dir($www_mappa.$csinal)){mkdir($www_mappa.$csinal);}

}

//------------------------------------------------------------------

    exec('7za 2>NUL', $output);
    if (count($output) > 0) {
        $extract = '7za e -o"%s" %s';
    }
//----------------------------------------------------------------
print "mappa kibontás: ".$dir."\n";

$tmpdir = tempnam(sys_get_temp_dir(), "tickdata-");
unlink($tmpdir);
mkdir($tmpdir);


function dir_nyit($dir){

global $www_mappa;
global $out_elotag ;

    if(is_dir($www_mappa.'/'.$dir))
    {
    $dh  = opendir($www_mappa.'/'.$dir);
        while (false !== ($filename = readdir($dh)))
         {
             if($filename=="." or $filename=="..") {}else{
                 if (is_dir($www_mappa.'/'.$dir .'/'.$filename)) {
                     echo "mappa:" . $filename. "\n";
                           if(!is_dir($www_mappa.'/'.$out_elotag.$dir.'/'.$filename)){mkdir($www_mappa.'/'.$out_elotag.$dir.'/'.$filename);}
                            dir_nyit($dir.'/'.$filename);
                 } else {
                     echo "file:" .$filename. "\n";

                    //$egynap= egy_napos_string($datum);
                    $egynap= '99999';
                  $filenev_bont=explode('_',$filename) ;
                  $ujfilenev=$filenev_bont[0].'.csv';
                 $outfd = fopen($www_mappa.'/'.$out_elotag.$dir.'/'.$ujfilenev,'a+');
                 if(!empty($egynap)){fwrite($outfd,$egynap);}
                 fclose($outfd);

                 }
             }
        }
    }else{echo 'hiba nem létező könyvtárnév'.$www_mappa.'/'.$dir;}
}

dir_nyit($dir);

function egy_napos_string($datum) {
global $pair;
$orak='';
$localpath = "$pair/".$datum.'/';
$date1 = str_replace('-', '/', $datum);
for($i = strtotime($date1); $i < strtotime($date1 . "+1 days"); $i += 3600) {
 $hour = gmstrftime('%H',$i);
	$localfile = $localpath.$hour."h_ticks.bi5";
	if (filesize($localfile) > 0) { $orak=$orak.decode_ducascopy_bi5($localfile,$i);}
}
return $orak;
}

function decode_ducascopy_bi5($fname, $hourtimestamp) {
    print "$fname\n";
    global $iswindows, $extract, $tmpdir, $point;
    if ($iswindows) {
        $cmd = sprintf($extract, $tmpdir, $fname);
        shell_exec($cmd);
        $extracted = $tmpdir.'\\'.substr($fname, strrpos($fname, '/') + 1);
        $extracted = substr($extracted, 0, strrpos($extracted, '.'));
        if (!file_exists($extracted)) {
            echo "Error: failed to extract [$fname]\n";
            exit(1);
        }
        $bin = file_get_contents($extracted);
        unlink($extracted);
    }
    else {
        $cmd = sprintf($extract, $fname);
        $bin = shell_exec($cmd);
    }
    if (strlen($bin) == 0) {
        echo "Error: unable to read extracted file\n";
        exit(1);
    }
    $idx = 0;
    $size = strlen($bin);
$sor='';
    while($idx < $size) {
        //print "$idx $size\n";
        $q = unpack('@'.$idx.'/N', $bin);
        $deltat = $q[1];
        $timesec = $hourtimestamp + $deltat / 1000;
        $timems = $deltat % 1000;


        $q = unpack('@'.($idx + 4)."/N", $bin);
        $ask = $q[1] * $point;
        $q = unpack('@'.($idx + 8)."/N", $bin);
        $bid = $q[1] * $point;
        $q = unpack('@'.($idx + 12)."/C4", $bin);
        $s = pack('C4', $q[4], $q[3], $q[2], $q[1]);
        $q = unpack('f', $s);
        $askvol = $q[1];
        $q = unpack('@'.($idx + 16)."/C4", $bin);
        $s = pack('C4', $q[4], $q[3], $q[2], $q[1]);
        $q = unpack('f', $s);
        $bidvol = $q[1];

        if ($bid == intval($bid)) {
            $bid = number_format($bid, 1, '.', '');
        }
        if ($ask == intval($ask)) {
            $ask = number_format($ask, 1, '.', '');
        }	
	
        $sor=$sor.date('Y-m-d H:i:s', $timesec).','.$timesec.','.$bid.','.number_format($bidvol,2,'.','').','.$ask.','.number_format($askvol,2,'.','')."\n";
	
        $idx += 20;
    }
return $sor;
}
