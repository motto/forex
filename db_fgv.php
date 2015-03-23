<?php
class DB
{
    static public function connect(){
        try {
            $db = new PDO("mysql:dbname=".MoConfig::$adatbazis.";host=".MoConfig::$host,MoConfig::$felhasznalonev, MoConfig::$jelszo, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
            //$db->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        } catch (PDOException $e) {
            die(GOB::$hiba['pdo']="Adatbazis kapcsolodasi hiba: ".$e->getMessage());
            return false;
        }
        return $db;
    }
    static public function parancs($sql){
        $sth =self::alap($sql);
    }
    static public function alap($sql){
        global $db;
        $sth = $db->prepare($sql);
        $sth->execute();
        //GOB::$hiba][]="assoc_tomb: ".$sth->errorInfo(); nem jó!!!
        //tömbhöz nem lehet hozzáfűzni	stringet!!!!!!!!!!!!!!!!!
      //  $h=$sth->errorInfo();
        //echo 'ffffffffffffffffffffff:'.$h[2].'</br>';
       // if(!empty($h[2])){GOB::$hiba['pdo'][]=$sth->errorInfo();	}
        return $sth;
    }
    static public function assoc_tomb($sql){
        $sth =self::alap($sql);
        while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $eredmeny_tomb[]= $row;
            //$row= $sth->fetchAll();//sorszámozottan is és associatívan is tárolja a mezőket(duplán)
        }
        return $eredmeny_tomb;
    }
    static public function assoc_sor($sql){
        $sth =self::alap($sql);
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    static public function beszur($sql){
        $sth =self::alap($sql);
       // return $db->lastInsertId();
    }

    static public function torol_sor($tabla,$id,$id_nev='id')
    {
        $sql="DELETE FROM $tabla WHERE $id_nev = '$id'";
        $sth =self::alap($sql);
    }

    static public function torol_tobb_sor($tabla,$id_tomb=[],$id_nev='id')
    {
        foreach($id_tomb as $id){self::torol_sor($tabla,$id,$id_nev); }
    }
}
class MoConfig {

//public static $felhasznalonev = 'pnet354_motto001';
//public static $jelszo = 'motto6814';
//public static $adatbazis = 'pnet354_motto001_fejleszt';
    public static $host = 'localhost';
    public static $felhasznalonev = 'root';
    public static $jelszo = '';
    public static $adatbazis = 'eurusd';
    public static $mailfrom= 'motto001@gmail.com';
    public static $fromnev= 'Admin';
    public static $offline = 'nem'; //igen bekapcsolja az offline módot
    public static $offline_message = 'Weblapunk fejlesztés alatt.';
}
class ADAT
{

    static public function beszur_tombbol($tabla, $adat_tomb, $mezok = 'all')
    {
        if (is_array($mezok)) {
            $mezotomb = $mezok;
        } else {
            $mezotomb = explode(',', $mezok);
        }
//print_r($adat_tomb);
        foreach ($adat_tomb as $key => $value) {
            if ($mezok == 'all') {
                $ertek = $ertek . "'" . $value . "',";
                $clm = $clm . $key . ",";
            } else {
                if (in_array($key, $mezotomb)) {
                    $ertek = $ertek . "'" . $value . "',";
                    $clm = $clm . $key . ",";
                }
            }
        }
        $clm2 = rtrim($clm, ',');
        $ertek2 = rtrim($ertek, ',');
        $sql = "INSERT INTO $tabla ($clm2) VALUES ($ertek2)";
        $id = DB::beszur($sql);
//echo $sql;
        return $id;
    }
}
?>