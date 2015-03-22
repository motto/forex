<?php 
class Adatbazis
{
public $con=null;

function kapcsolat() 
{
$con = mysql_connect('localhost','root','');
mysql_query("SET NAMES 'utf8'");
mysql_query("SET CHARACTER SET 'utf8'");
mysql_query("SET COLLATION_CONNECTION='utf8_general_ci'");
mysql_query("SET character_set_results = 'utf8'");
mysql_query("SET character_set_server = 'utf8'");
mysql_query("SET character_set_client = 'utf8'");
mysql_select_db('eurusd') ;
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
}

function __construct(){$this->kapcsolat();}

function parancs($sql) 
{
$result = mysql_query($sql);
 if (!$result) {
	  global $hiba; global $userid;
       $hiba['sql'][]=array('ido'=>date("Y-m-d H:i:s") ,'userid'=>$userid,'fuggveny'=>'parancs('.$sql.')','hiba' =>mysql_error());
 }
// if(!empty(mysql_error()){$result= mysql_error();}
//echo '<br/>parancs hiba:'.mysql_error();
return $result;
}
function adat_ir($sql,$ir='nem')
{
$result = mysql_query($sql);
 if (!$result) {
	  global $hiba; global $userid;
       $hiba['sql'][]=array('ido'=>date("Y-m-d H:i:s") ,'userid'=>$userid,'fuggveny'=>'adat_ir('.$sql.','.$ir.')','hiba' =>mysql_error());
 }
if($ir=='igen'){$result= mysql_insert_id();}  

return $result;
}

function indexelt_sor($sql)
{
$result = mysql_query($sql);
 if (!$result) {
	  global $hiba; global $userid;
       $hiba['sql'][]=array('ido'=>date("Y-m-d H:i:s") ,'userid'=>$userid ,'fuggveny'=>'indexelt_sor('.$sql.')','hiba' =>mysql_error());
 }

if(!empty($result)){$eredmeny = mysql_fetch_row($result);}
return $eredmeny;
}
function indexelt_tomb($sql)
{
$result = mysql_query($sql);
 if (!$result) {
	  global $hiba; global $userid;
       $hiba['sql'][]=array('ido'=>date("Y-m-d H:i:s") ,'userid'=>$userid ,'fuggveny'=>'indexelt_sor('.$sql.','.$ir.')','hiba' =>mysql_error());
 }
	if(!empty($result))
	{
	while ($sor=mysql_fetch_row($result))
	   {
	  $eredmeny[]=$sor;
	   }
	}
return $eredmeny;	
}

function assoc_sor($sql)
{
$result = mysql_query($sql);
 if (!$result) {
	  global $hiba; global $userid;
       $hiba['sql'][]=array('ido'=>date("Y-m-d H:i:s") ,'userid'=>$userid,'fuggveny'=>'assoc_sor('.$sql.')','hiba' =>mysql_error());
 }else{if(!empty($result)){$eredmeny = mysql_fetch_assoc($result);}}
return $eredmeny;
}
function assoc_tomb($sql)
{
$result = mysql_query($sql);
 if (!$result) {
	  global $hiba; global $userid;
       $hiba['sql'][]=array('ido'=>date("Y-m-d H:i:s") ,'userid'=>$userid,'fuggveny'=>'assoc_tomb('.$sql.')','hiba' =>mysql_error());
 }else{if(!empty($result)){while ($sor=mysql_fetch_assoc($result)) { $eredmeny[]=$sor;}}}
return $eredmeny;	
}
}
class Lekerdez {

function torol_sor($tabla,$id) 
{
$sql="DELETE FROM $tabla WHERE id = '$id'";
$ob=new Adatbazis;$result=$ob->parancs($sql); return $result; 
}
function parancs($sql)
{$ob=new Adatbazis;$result=$ob->parancs($sql); return $result;}

function adat_ir($sql,$ir='nem')
{$ob=new Adatbazis;$result=$ob->adat_ir($sql,$ir); return $result;}
function beszur($sql,$ir='igen')
{$ob=new Adatbazis;$result=$ob->adat_ir($sql,$ir); return $result;}

function indexelt_sor($sql)
{$ob=new Adatbazis;$result=$ob->indexelt_sor($sql); return $result;}

function indexelt_tomb($sql)
{$ob=new Adatbazis;$result=$ob->indexelt_tomb($sql); return $result;}

function assoc_sor($sql)
{$ob=new Adatbazis;$result=$ob->assoc_sor($sql); return $result;}

function assoc_tomb($sql)
{$ob=new Adatbazis;$result=$ob->assoc_tomb($sql); return $result;}

function mezonevek($table){
// a table változóban megadott tábla mezőnveivel tér vissza egy indexelt tömbben -------------
 $result =Lekerdez::parancs('SHOW COLUMNS FROM '.$table);
 // $result = mysql_query("SHOW COLUMNS FROM ". $table);
      $fieldnames=array();
      if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_assoc($result)) {
          $fieldnames[] = $row['Field'];
        }
      }
  return $fieldnames;
} 
}


class Feltolt {
function insert_ment($tabla,$mezok){
$mezotomb=explode(',',$mezok);
foreach ($mezotomb as $mezo){
$ertek=$ertek."'".$_POST[$mezo]."',"; 
$clm=$clm.$mezo.","; 
}
$clm2=rtrim($clm);
$ertek2=rtrim($ertek);
$sql="INSERT INTO table_name ($clm2) VALUES ($ertek2)";
$ob=new Adatbazis;$result=$ob->parancs($sql); return $result;

}
//Feltolt::update_ment('userek','foto,name,pubname,leiras,cimke',$userid);
function update_ment($tabla,$mezok,$id=''){
$mezotomb=explode(',',$mezok);
foreach ($mezotomb as $mezo){$setek=$setek.$mezo."='".$_POST[$mezo]."', ";}

//$setek2=rtrim($setek);
$setek2 = substr($setek, 0, -2); 
$sql="UPDATE $tabla SET $setek2 WHERE id='$id'";
//echo $sql;
$con = mysql_connect(MoConfig::$host,MoConfig::$felhasznalonev,MoConfig::$jelszo);
mysql_select_db(MoConfig::$adatbazis) ;$result = mysql_query($sql);
//$ob=new Adatbazis;$result=$ob->parancs($sql); return $result;

}}
	
?>