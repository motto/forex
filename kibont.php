<?php
$pair='EURUSD/';
$datum_mappa='2014/08'; //$dir=$pair.$datum_mappa;//kibontandó mappa a dir_nyit() függvény használja
$www_mappa='g:/www/forex'; // nem kell könyvtár elválasaztó a végére:/  !!!!!!!!!!
$out_elotag='csv_'; // A kimenő adatok fő könyvtáránk létrehozásához kell
$extract = '';
$iswindows = true;
$point = 0.00001;
$csinal='';
$outdir_tomb=explode('/',$out_elotag.$pair.$datum_mappa);
foreach ( $outdir_tomb as $od ) {
    $csinal = $csinal . '/' . $od;
    if (!is_dir($www_mappa . $csinal)) {
        mkdir($www_mappa . $csinal);
    }
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


function dir_nyit($pair,$datum_mappa){
global $www_mappa;
global $out_elotag ;
    $dir=$pair.$datum_mappa;//kibontandó mappa
    $datum = str_replace('-', '/',$datum_mappa );

    if(is_dir($www_mappa.'/'.$dir))
    {
    $dh  = opendir($www_mappa.'/'.$dir);
        while (false !== ($filename = readdir($dh)))
         {
             if($filename=="." or $filename=="..") {}else{
                 if (is_dir($www_mappa.'/'.$dir .'/'.$filename)) {
                     echo "mappa:" . $filename. "\n";
                           if(!is_dir($www_mappa.'/'.$out_elotag.$dir.'/'.$filename)){mkdir($www_mappa.'/'.$out_elotag.$dir.'/'.$filename);}
                            dir_nyit($pair,$datum_mappa.'/'.$filename);
                 } else {
                     echo "file:" .$filename. "\n";

                     $ora=substr($filename, 0, 2);
                     $idobelyeg= strtotime($datum.' '.$ora.':00:00');
                     $ujfilenev=$ora.'.csv';
                 if (!is_file($www_mappa.'/'.$out_elotag.$dir.'/'.$ujfilenev)) {
                     $egy_ora = decode_ducascopy_bi5($www_mappa . '/' . $dir . '/' . $filename, $idobelyeg);
                     if($egy_ora =='hiba'){ $ujfilenev=$ora.'_hibas.csv';}
                     $outfd = fopen($www_mappa . '/' . $out_elotag . $dir . '/' . $ujfilenev, 'a+');
                     fwrite($outfd, $egy_ora);
                     fclose($outfd);
                 }
                 }
             }
        }
    }else{echo 'hiba nem létező könyvtárnév'.$www_mappa.'/'.$dir;}
}

dir_nyit($pair,$datum_mappa);


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
            //exit(1);
            $hiba='van';
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
        //exit(1);
        $hiba='van';
    }
    if(empty($hiba)) {
        $idx = 0;
        $size = strlen($bin);
        $sor = '';
        while ($idx < $size) {
            //print "$idx $size\n";
            $q = unpack('@' . $idx . '/N', $bin);
            $deltat = $q[1];
            $timesec = $hourtimestamp + $deltat / 1000;
            $timems = $deltat % 1000;


            $q = unpack('@' . ($idx + 4) . "/N", $bin);
            $ask = $q[1] * $point;
            $q = unpack('@' . ($idx + 8) . "/N", $bin);
            $bid = $q[1] * $point;
            $q = unpack('@' . ($idx + 12) . "/C4", $bin);
            $s = pack('C4', $q[4], $q[3], $q[2], $q[1]);
            $q = unpack('f', $s);
            $askvol = $q[1];
            $q = unpack('@' . ($idx + 16) . "/C4", $bin);
            $s = pack('C4', $q[4], $q[3], $q[2], $q[1]);
            $q = unpack('f', $s);
            $bidvol = $q[1];

            if ($bid == intval($bid)) {
                $bid = number_format($bid, 1, '.', '');
            }
            if ($ask == intval($ask)) {
                $ask = number_format($ask, 1, '.', '');
            }

            $sor = $sor . date('Y-m-d H:i:s', $timesec) . ',' . $timesec . ',' . $bid . ',' . number_format($bidvol, 2, '.', '') . ',' . $ask . ',' . number_format($askvol, 2, '.', '') . "\n";
            // $sor=$sor. gmstrftime("%Y.%m.%d %H:%M:%S", $timesec).".".str_pad($timems,3,'0',STR_PAD_LEFT).",$bid,$ask,".number_format($bidvol,2,'.','').",".number_format($askvol,2,'.','')."\n";

            $idx += 20;
        }
    }else{$sor='hiba';}
return $sor;
}
