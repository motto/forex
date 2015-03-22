
<?php
$ev=2014;
for($i=1; $i<13; $i++){
$i_padded = sprintf("%02s", $i);
echo'
php G:\www\process_dukascopy_data.php G:\www\EURUSD '.$ev.$i_padded.' '.$ev.$i_padded.' G:\www\EURUSD_CSV\\'.$ev.$i_padded.'.csv';
}

