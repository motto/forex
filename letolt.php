<?php
/*
    Copyright (C) 2009-2011 Cristi Dumitrescu <birt@eareview.net>
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

    Version: 0.26
*/
$currencies = array(
   /* "AUDJPY" => 1175270400, // starting from 2007.03.30 16:00
    "AUDNZD" => 1229961600, // starting from 2008.12.22 16:00
    "AUDUSD" => 1175270400, // starting from 2007.03.30 16:00
    "CADJPY" => 1175270400, // starting from 2007.03.30 16:00
    "CHFJPY" => 1175270400, // starting from 2007.03.30 16:00
    "EURAUD" => 1175270400, // starting from 2007.03.30 16:00
    "EURCAD" => 1222167600, // starting from 2008.09.23 11:00
    "EURCHF" => 1175270400, // starting from 2007.03.30 16:00
    "EURGBP" => 1175270400, // starting from 2007.03.30 16:00
    "EURJPY" => 1175270400, // starting from 2007.03.30 16:00
    "EURNOK" => 1175270400, // starting from 2007.03.30 16:00
    "EURSEK" => 1175270400, // starting from 2007.03.30 16:00*/
    //"EURUSD" => 1175270400, // starting from 2007.03.30 16:00
    //"EURUSD" =>  1305010800, // starting from 2011.05.10 07:00
  "EURUSD" =>  1409868000, // starting from 2014.08.04 00:00
   /* "GBPCHF" => 1175270400, // starting from 2007.03.30 16:00
    "GBPJPY" => 1175270400, // starting from 2007.03.30 16:00
    "GBPUSD" => 1175270400, // starting from 2007.03.30 16:00
    "NZDUSD" => 1175270400, // starting from 2007.03.30 16:00
    "USDCAD" => 1175270400, // starting from 2007.03.30 16:00
    "USDCHF" => 1175270400, // starting from 2007.03.30 16:00
    "USDJPY" => 1175270400, // starting from 2007.03.30 16:00
    "USDNOK" => 1222639200, // starting from 2008.09.28 22:00
    "USDSEK" => 1222642800, // starting from 2008.09.28 23:00
    "USDSGD" => 1222642800, // starting from 2008.09.28 23:00
    "AUDCAD" => 1266318000, // starting from 2010.02.16 11:00
    "AUDCHF" => 1266318000, // starting from 2010.02.16 11:00
    "CADCHF" => 1266318000, // starting from 2010.02.16 11:00
    "EURNZD" => 1266318000, // starting from 2010.02.16 11:00
    "GBPAUD" => 1266318000, // starting from 2010.02.16 11:00
    "GBPCAD" => 1266318000, // starting from 2010.02.16 11:00
    "GBPNZD" => 1266318000, // starting from 2010.02.16 11:00
    "NZDCAD" => 1266318000, // starting from 2010.02.16 11:00
    "NZDCHF" => 1266318000, // starting from 2010.02.16 11:00
    "NZDJPY" => 1266318000, // starting from 2010.02.16 11:00
    "XAGUSD" => 1289491200, // starting from 2010.11.11 16:00
    "XAUUSD" => 1305010800, // starting from 2011.05.10 07:00*/
    );

foreach($currencies as $pair => $firsttick) {
    $firsttick -= $firsttick % 3600;
    error("Info: Downloading $pair starting with ".gmstrftime("%m/%d/%y %H:%M:%S",$firsttick)."\n");
    for($i = $firsttick; $i < time(); $i += 3600) {
        $year = gmstrftime('%Y',$i);
        $month = str_pad(gmstrftime('%m',$i) - 1, 2, '0', STR_PAD_LEFT);
        $day = gmstrftime('%d',$i);
        $hour = gmstrftime('%H',$i);
        $url = "http://www.dukascopy.com/datafeed/$pair/$year/$month/$day/{$hour}h_ticks.bi5";
        error("Info: Processing $pair $i - ".gmstrftime("%m/%d/%y %H:%M:%S",$i)." --- $url\n");
        $localpath = "G:/www/$pair/$year/$month/$day/";
        $binlocalfile = $localpath . $hour . "h_ticks.bin";
        $localfile = $localpath . $hour . "h_ticks.bi5";
        if (!file_exists($localpath)) {
            mkdir($localpath, 0777, true);
        }
        if (!file_exists($localfile) && !file_exists($binlocalfile)) {
            $ch = FALSE;
            $j = 0;
            do {
                if ($ch !== FALSE) {
                    curl_close($ch);
                }
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                $result = curl_exec($ch);
                $j++;
            } while ($j <= 3 && curl_errno($ch));
            if (curl_errno($ch)) {
                error("FATAL: Couldn't download $url.\nError was: ".curl_error($ch)."\n");
                exit(1);
            }
            else {
                if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 404) {
                    // file not found
                    $weekday = gmstrftime('%a',$i);
                    if (strcasecmp($weekday,'sun') == 0 || strcasecmp($weekday,'sat') == 0) {
                        // ignore missing weekend files
                        //error("Info: missing weekend file $url\n");
                    }
                    else {
                        error("WARNING: missing file $url ($i - ".gmstrftime("%m/%d/%y %H:%M GMT",$i).")\n");
                    }
                }
                else if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200) {
                    $outfd = fopen($localfile, 'wb');
                    if ($outfd === FALSE) {
                        error("FATAL: Couldn't open $localfile ($url - $i)\n");
                        exit(1);
                    }
                    fwrite($outfd, $result);
                    fclose($outfd);
                   error("Info: successfully downloaded $url\n");
                }
                else {
                    error("WARNING: did not download $url ($i - ".gmstrftime("%m/%d/%y %H:%M GMT",$i).") - error code was ".curl_getinfo($ch, CURLINFO_HTTP_CODE)."\nContent was: $result\n");
                }
            }
            curl_close($ch);
        }
        else {
           error("Info: skipping $url, local file already exists.\n");
        }
    }
}

function error($error) {
    echo $error;
    $fd = fopen('error.log', 'a+');
    fwrite($fd, $error);
    fclose($fd);
}

?>
