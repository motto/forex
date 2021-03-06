<?php
# PHPlot Example: Simple line graph
require_once 'phplot.php';
//$data=array(xfelirat,x,y1,y2....yn); hiányzó adatot '' -jelekkel helyetessítünk
include 'regresszio.php';
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
        $gg=array();
        while (($line = fgetcsv($file)) !== FALSE) {

            $perc= substr($line[0], 14, 2);
            if ($perc==$this->perc){
                //datum,időbélyeg,bid,bidvol,ask,askvol
                $idobelyeg2 = round($line[1]);
                $idobelyeg = $line[1];
                //$datum = $line[0];
                // $perc= substr($line[0], 14, 2);
                // echo $perc;
                // $this->ora = substr($datum, 11, 2);
                $this->munka_tomb['bid_ask'][] =array('',$idobelyeg,$line[2],$line[4]);
                $this->munka_tomb['vol'][] = array('',$idobelyeg,$line[3]);

                //  $gg[] =array($idobelyeg2, $line[2]);
                $gg[] =$line[2];
                // $gg=$this->munka_tomb;
                //$this->munka_tomb['vol_ido'][] = [$line[3], $idobelyeg];
                // if( $perc>$this->perc){break;}
            }
        }
        //print_r($this->munka_tomb['bid_ask']);
        $vRegression = new CRegressionLinear($gg);
        $elso=$vRegression->predict(1);
        $utolso=$vRegression->predict(count($gg));
        $this->munka_tomb['bid_ask'][0][]=$elso;
        $this->munka_tomb['bid_ask'][count($this->munka_tomb['bid_ask'])-1][]= $utolso;
        // $this->munka_tomb['bid_ask'][count($this->munka_tomb['bid_ask'])][]=1.29573;
        // $this->aktualis_perc = 0;
        // $this->munka_tomb = [];
        //echo'+++++++++++++++++++++++++++++';
        // print_r($this->munka_tomb['bid_ask']);
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
for ($i = 10; $i <= 24; $i++) {
    $a = new Egyperces_plot('2014-08-01 '.$i.'-45-35','bid_ask');
}
//$a = new Egyperces_plot('2014-08-01 19-45-35','bid_ask');

