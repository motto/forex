
<?php
class DB{
    private static $db=null;

    static public function parancs($sql){
        $sth =self::alap($sql);
    }
    static public function alap($sql){
        $sth = self::$db->prepare($sql);
        $sth->execute();
        //GOB::$hiba][]="assoc_tomb: ".$sth->errorInfo(); nem jó!!!
        //tömbhöz nem lehet hozzáfűzni	stringet!!!!!!!!!!!!!!!!!
        $h=$sth->errorInfo();
        //echo 'ffffffffffffffffffffff:'.$h[2].'</br>';
        if(!empty($h[2])){GOB::$hiba['pdo'][]=$sth->errorInfo();	}

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
        return self::$db->lastInsertId();
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