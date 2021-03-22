<?php

    require_once "../conectionServer/dbconnect.php";
    require_once "../conectionServer/connection.php";

    $db = connect($host, $db_user, $db_password, $db_name);

    function login($sql){
        global $db;
        $tablica = [];
        if($rezultat = $db->query($sql)){
            while($tablicaa = $rezultat->fetch_assoc())
            {
                array_push($tablica, $tablicaa);
            }
            $rezultat->free();    
        }
        return $tablica;
    }

    function getAllDataArmia(){
        $sql = "SELECT * FROM armia ORDER BY idarmia ASC";
        
        $json_to_send = login($sql);

        return $json_to_send;
    }

    function bonusCheck($monster){
        $count = null;
        switch (count($monster)) {
            case 1:
                $count = 1;
                break;
            case 2:
                $count = 2;
                break;
            case 3:
                $count = 2;
                break;
            case 4:
                $count = 3;
                break;
            case 6:
                $count = 4;
                break;
            default:
                echo "Nie kombinuj";
                break;
        }
        $atak = [];
        for ($i=0; $i < $count ; $i++) {
            if ($monster[$i]['bonus_komu1'] != null) {
                array_push($atak,$monster[$i]['bonus_komu1']);
            }
            if ($monster[$i]['bonus_komu2'] != null) {
                array_push($atak,$monster[$i]['bonus_komu2']);
            }
            if ($monster[$i]['bonus_komu3'] != null) {
                array_push($atak,$monster[$i]['bonus_komu3']);
            }
        }
        return $atak;
    }

    function checkQueue ($monster) {
        $armia = getAllDataArmia();
        $monster_sorted = [];
        $for_count = count($monster);
        for($i = 0;  $i < $for_count; $i++){
            $count = null;
            $max_atak = 0;
            foreach ($monster as $key => $value) {
                if ($max_atak < $value['atak_sila']) {
                    $max_atak = $value['atak_sila'];
                    $count = $key;
                }
            }
            $deleted_monster = array_splice($monster, $count, 1);
            array_push($monster_sorted, $deleted_monster[0]);
        }
        if (count($monster_sorted)) {
            $atak = bonusCheck($monster_sorted);
            
            $kuloodporni = [];
            foreach ($armia as $value) {
                if (in_array($value['typ1'], $atak)) {
                    // continue;
                } elseif (in_array($value['typ2'], $atak)) {
                    // continue;
                } elseif (in_array($value['typ3'], $atak)) {
                    // continue;
                } else {
                    array_push($kuloodporni, $value);
                }
            }
        }
    }