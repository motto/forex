<?php
# PHPlot Example: Simple line graph
require_once 'phplot.php';
//$data=array(xfelirat,x,y1,y2....yn); hiányzó adatot '' -jelekkel helyetessítünk


$pair='csv_EURUSD/';
$datum_mappa='2014/08/03'; //$dir=$pair.$datum_mappa;//kibontandó mappa a dir_nyit() függvény használja
$www_mappa='g:/www/forex'; // nem kell könyvtár elválasaztó a végére:/  !!!!!!!!!!

class Egyperces_plot
{
    var $pair='csv_EURUSD/';
    var $datum_mappa='2014/08/03'; //$dir=$pair.$datum_mappa;//kibontandó mappa a dir_nyit() függvény használja
    var $www_mappa='g:/www/forex'; // nem kell könyvtár elválasaztó a végére:/  !!!!!!!!!!
    // paraméterek------------------------
    var $start_datum= '';
    var $end_datum= '';
    var $kihagy= 0;

    var $aktualis_perc = 0;
    var $intervallum_db = 1;
    var $ev = '';var $honap = '';var $nap = '';var $ora = '';var $perc = '';
    var $munka_tomb = [];
    // function __construct($start_datum,$end_datum,$kihagy)
    function __construct($datum,$dataname='vol')
    {
        $this->perc = substr($datum, 14, 2);
        $this->ora = substr($datum, 11, 2);
        $this->nap = substr($datum, 8, 2);
        $this->honap = substr($datum, 5, 2);
        $this->ev = substr($datum, 0, 4);

        $file= fopen( $this->www_mappa.'/'.$this->pair.$this->ev.'/'.$this->honap.'/'.$this->nap.'/'.$this->ora.'.csv','r');
        $this-> futtat($file);
        $this->plot($dataname);
        echo $this->ev.' :'.$this->honap.' :'.$this->nap.' :'.$this->ora.' :'.$this->perc;
    }

    function futtat($file)
    {

        while (($line = fgetcsv($file)) !== FALSE) {
            $perc= substr($line[0], 14, 2);
            if ($perc==$this->perc){
                //datum,időbélyeg,bid,bidvol,ask,askvol
                //$idobelyeg = round($line[1]);
                $idobelyeg = $line[1];
                //$datum = $line[0];
                // $perc= substr($line[0], 14, 2);
                // echo $perc;
                // $this->ora = substr($datum, 11, 2);
                $this->munka_tomb['bid_ask'][] =array('',$idobelyeg,$line[2],$line[4]);
                $this->munka_tomb['vol'][] = array('',$idobelyeg,$line[3]);
                //$this->munka_tomb['bid_ido'][] = [$line[2], $idobelyeg];
                //$this->munka_tomb['vol_ido'][] = [$line[3], $idobelyeg];
                // if( $perc>$this->perc){break;}
            }
            // $this->aktualis_perc = 0;
            // $this->munka_tomb = [];
        }
    }

    function plot($dataname='vol'){

        $plot = new PHPlot(800, 600);
        $plot->SetImageBorderType('plain');

        $plot->SetPlotType('lines');
        $plot->SetDataType('data-data');
        $plot->SetDataValues($this->munka_tomb[$dataname]);
        //print_r($this->munka_tomb);
//$plot->SetDataValues($data2);

# Main plot title:
        // $plot->SetTitle('US Population, in millions');

# Make sure Y axis starts at 0:
        // $plot->SetPlotAreaWorld(NULL, 0, NULL, NULL);

        $plot->DrawGraph();

    }



}

$a = new Egyperces_plot('2014-08-15 02-45-35','bid_ask');


/*


$data1 = array(
    array('', 1800,   5),
    array('', 1860, 130), array('', 1870,  39, 11,55), array('', 1880,  50,22),
    array('', 1890,  63), array('', 1900,  76), array('', 1910,  92),
    array('', 1920, 106), array('', 1930, 123), array('', 1940, 132),
    array('', 1950, 151), array('', 1960, 179), array('', 1970, 203),
    array('', 1980, '',44), array('', 1990, '',44,33), array('', 2000, 281),
);
$data2 = array(
    array('', 1800,   5), array('', 1810,   7), array('', 1820,  10),
    array('', 1830,  13), array('', 1840,  90), array('', 1850,  23),

    array('', 1980, 227), array('', 1990, 249), array('', 2000, 281),
);

$plot = new PHPlot(800, 600);
$plot->SetImageBorderType('plain');

$plot->SetPlotType('lines');
$plot->SetDataType('data-data');
$plot->SetDataValues($data1);
//$plot->SetDataValues($data2);

# Main plot title:
$plot->SetTitle('US Population, in millions');

# Make sure Y axis starts at 0:
$plot->SetPlotAreaWorld(NULL, 0, NULL, NULL);

$plot->DrawGraph();

*/