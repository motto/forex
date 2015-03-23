  <?php 
class CRegressionLinear {

 private $mDatas; // input data, array of (x1,y1);(x2,y2);... pairs, or could just be a time-series (x1,x2,x3,...)
  /** constructor */
  function __construct($pDatas) {
    $this->mDatas = $pDatas;
  }
  function calculate(){
    $n = count($this->mDatas);
    $vSumXX = $vSumXY = $vSumX = $vSumY = 0;
    $vCnt = 0; 
    foreach ($this->mDatas AS $vOne){
      if (is_array($vOne)){
        list($x,$y) = $vOne;
      } else {
        $x = $vCnt; $y = $vOne;
      }
      $vSumXY += $x*$y;
      $vSumXX += $x*$x;
      $vSumX += $x;
      $vSumY += $y;
      $vCnt++;
	}
	$vTop = $n * $vSumXY - $vSumX * $vSumY ;
	$vBottom = ($n*$vSumXX - $vSumX*$vSumX);
    $a = $vBottom!=0?$vTop/$vBottom:0;
    $b = ($vSumY - $a*$vSumX)/$n;
    //var_dump($a,$b);
    return array($a,$b);
  }
  /** given x, return the prediction y */
  function predict($x) {
    list($a,$b) = $this->calculate();
    $y = $a*$x+$b;
    return $y;
  }
}


// sales data for the last 30 quarters
/*
$vSales1 = array(
 637381,700986,641305,660285,604474,565316,598734,688690,723406,697358,
 669910,605636,526655,555165,625800,579405,588317,634433,628443,570597,
 662584,763516,742150,703209,669883,586504,489240,648875,692212,586509
);
$vSales = array(
 10,10.2345,10,10,10,10,10,10,10,10,);*/
?>