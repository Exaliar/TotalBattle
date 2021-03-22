<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();

function prepare_data_armia ($stare = null) {
    $nowe = [];
    foreach ($stare as $value) {
        if ($value['armyIlosc'] != 0) {    
            $dane = array(
                'idarmia' => $value['armyId'],
                'nazwa' => $value['name'],
                'lvl' => $value['lvl'],
                'typ' => $value['typ'],
                'typ1' => $value['typ1'],
                'typ2' => $value['typ2'],
                'typ3' => $value['typ3'],
                'sila' => $value['sila'],
                'zycia' => $value['zycie'],
                'bonus_ile1' => $value['bonusIle1'],
                'bonus_komu1' => $value['bonusKomu1'],
                'bonus_ile2' => $value['bonusIle2'],
                'bonus_komu2' => $value['bonusKomu2'],
                'bonus_ile3' => $value['bonusIle3'],
                'bonus_komu3' => $value['bonusKomu3'],
                'ile_jednostek' => $value['armyIlosc'],
                'odbyty_atak' => 1,
                'bonus_zycia' => $value['armyHpBonus'],
                'bonus_ataku' => $value['armyApBonus'],
                'atak_sila' => $value['armyIlosc'] * (($value['sila'] / 100) * (100 + $value['armyApBonus'])),
                'zycie_walka' => $value['armyIlosc'] * (($value['zycie'] / 100) * (100 + $value['armyHpBonus']))
            );
            array_push($nowe, $dane);
        }
    }
    return $nowe;
}

function prepare_data_monster ($stare = null) {
    $nowe = [];
    foreach ($stare as $value) {
        $dane = array(
            'idpotwory' => $value['id'],
            'lvl' => $value['lvl'],
            'nazwa' => $value['nazwa'],
            'typ1' => $value['typ'][0],
            'typ2' => $value['typ'][1],
            'typ3' => $value['typ'][2],
            'sila' => $value['atak'],
            'zycie' => $value['zycie'],
            'bonus_ile1' => $value['bonus'][0],
            'bonus_komu1' => $value['komu'][0],
            'bonus_ile2' => $value['bonus'][1],
            'bonus_komu2' => $value['komu'][1],
            'bonus_ile3' => $value['bonus'][2],
            'bonus_komu3' => $value['komu'][2],
            'ile_jednostek' => $value['ilosc'],
            'odbyty_atak' => 1,
            'zycie_walka' => $value['ilosc'] * $value['zycie'],
            'atak_sila' => $value['ilosc'] * $value['atak']
        );
        array_push($nowe, $dane);
    }
    return $nowe;
}

if (isset($_SESSION['co_ile_liczyc'])) {
    $time = time();
    if ($time - $_SESSION['co_ile_liczyc'] > 1) {
        
        $json = json_decode(file_get_contents('php://input'), true);
    
        if (!isset($json)) {
            header("Location: ../../index.php");
            exit;
        }

        $armia = prepare_data_armia($json['armia']);
        $potwory = prepare_data_monster($json['monster']['jednostkiObiektu']);

        if (empty($armia) || empty($potwory)) {
            echo "bledna wartosc";
            exit();
        } else {
        //0 = potwory, 1 = gracz --- KTO PIERWSZY ATAKUJE
        $kolejka = 0;
        if ($json['first'] === 'gracz') {
            $kolejka = 1;
        }
        $_SESSION['co_ile_liczyc'] = time();

        require_once "raport.php";

        $json_to_send = tb_raport($potwory, $armia, $kolejka);
        echo json_encode($json_to_send);
        }
    } else {
        exit();
    }
} else {
    $_SESSION['co_ile_liczyc'] = time();
   
}

?>