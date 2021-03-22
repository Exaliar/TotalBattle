<?php
    $json = json_decode(file_get_contents('php://input'), true);

    if (!isset($json)) {
        header("Location: ../../index.php");
        exit;
    }

    require_once "../conectionServer/dbconnect.php";
    require_once "../conectionServer/connection.php";

    #Tablica odpowiadajaca nazwom bazy danych oddzialy.potwory[0-3]

    $potwory = ["zwykle", "rzadkie", "heroiczne", "inne"];
    $minlvl = 1;
    $maxlvl = 50;
    $jumplvl = 5;
    $json_to_send = "";
    $json_array_to_send = [];

    class ObjToSend {
        public $nazwaObiektu;
        public $lvlObiektu;
        public $typObiektu;
        public $jednostkiObiektu;
        
        public function __construct ($name, $lvl, $typ, $jednostki){
            $this->nazwaObiektu = $name;
            $this->lvlObiektu = $lvl;
            $this->typObiektu = $typ;
            $this->jednostkiObiektu = $jednostki;
        }
    }

    class Jednostka {
        public $nazwa;
        public $ilosc;
        public $typ;
        public $bonus;
        public $komu;
        public $atak;
        public $zycie;
        public $lvl;
        public $id;

        public function __construct($nazwa, $ilosc, $typ, $bonus, $komu, $atak, $zycie, $lvl, $id){
            $this->nazwa = $nazwa;
            $this->ilosc = intval($ilosc);
            $this->typ = $typ;
            $this->bonus = $bonus;
            $this->komu = $komu;
            $this->atak = intval($atak);
            $this->zycie = intval($zycie);
            $this->lvl = intval($lvl);
            $this->id = intval($id);
        }
    }

    $db = connect($host, $db_user, $db_password, $db_name);

    function check_valid_varible($test){
        global $db;
        $test = mysqli_real_escape_string($db, $test);
        return trim(htmlspecialchars($test));
    }

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

    function getDataMonsters($potwory){
        $json_to_send = [];
        foreach ($potwory as $value) {
            
            $_value = check_valid_varible($value);

            $sql = "SELECT * FROM $_value ORDER BY $_value.lvl ASC";

            $potworyJSON = login($sql);

            array_push($json_to_send, $potworyJSON);
        }
        return $json_to_send;
    }

    function getDataArmia(){
        $sql = "SELECT  `idarmia`, `nazwa`, `lvl`, `typ` FROM armia ORDER BY idarmia ASC";
        
        $json_to_send = login($sql);

        return $json_to_send;
    }

    function getSingleArmia($id){
        $_id = check_valid_varible($id);

        $sql = "SELECT * FROM armia WHERE `idarmia`=$_id";

        $json_to_send = login($sql);

        return $json_to_send;
    }

    function oneMonster($name){
        $_name = check_valid_varible($name);
        $sql = "SELECT * FROM `potwory` WHERE `potwory`.`nazwa`='$_name'";

        $each = login($sql);

        return $each[0];
    }

    function getEachMonster($json_to_send = null){
        $objToSend = [];
        for ($i=1; $i < 7; $i++) { 
            if (isset($json_to_send["nazwa$i"]) && $json_to_send["nazwa$i"] != null) {
                $json_to_send["nazwa$i"] = oneMonster($json_to_send["nazwa$i"]);
                
                $monster = new Jednostka (
                    $json_to_send["nazwa$i"]["nazwa"],
                    $json_to_send["ile$i"],
                    [$json_to_send["nazwa$i"]["typ1"], $json_to_send["nazwa$i"]["typ2"], $json_to_send["nazwa$i"]["typ3"]],
                    [$json_to_send["nazwa$i"]["bonus_ile1"], $json_to_send["nazwa$i"]["bonus_ile2"], $json_to_send["nazwa$i"]["bonus_ile3"]],
                    [$json_to_send["nazwa$i"]["bonus_komu1"], $json_to_send["nazwa$i"]["bonus_komu2"], $json_to_send["nazwa$i"]["bonus_komu3"]],
                    $json_to_send["nazwa$i"]["sila"],
                    $json_to_send["nazwa$i"]["zycie"],
                    $json_to_send["nazwa$i"]["lvl"],
                    $json_to_send["nazwa$i"]["idpotwory"]
                );
                array_push($objToSend, $monster);
            }
        }
        return $objToSend;
    }

    function getSingleMonster($typ, $id){
        $_typ = check_valid_varible($typ);
        $_id = check_valid_varible($id);

        switch ($_typ) {
            case 'zwykle':
                {
                    $sql = "SELECT * FROM zwykle WHERE `idzwykly`=$_id";
                    $json_to_send = login($sql);
                    $_json_to_send = getEachMonster($json_to_send[0]);
                    $toSend = new ObjToSend("Zwykły", $json_to_send[0]['lvl'], $json_to_send[0]['typ'], $_json_to_send);
                    return $toSend;
                    break;
                }
            case 'rzadkie':
                {
                    $sql = "SELECT * FROM rzadkie WHERE `idrzadki`=$_id";
                    $json_to_send = login($sql);
                    $_json_to_send = getEachMonster($json_to_send[0]);
                    $toSend = new ObjToSend("Rzadki", $json_to_send[0]['lvl'], $json_to_send[0]['typ'], $_json_to_send);
                    return $toSend;
                    break;
                }
            case 'heroiczne':
                {
                    $sql = "SELECT * FROM heroiczne WHERE `idheroiczny`=$_id";
                    $json_to_send = login($sql);
                    $_json_to_send = getEachMonster($json_to_send[0]);
                    $toSend = new ObjToSend("Heroiczny", $json_to_send[0]['lvl'], $json_to_send[0]['typ'], $_json_to_send);
                    return $toSend;
                    break;
                }
            case 'twierdze':
                {
                    $sql = "SELECT * FROM inne WHERE `idinne`=$_id";
                    $json_to_send = login($sql);
                    $_json_to_send = getEachMonster($json_to_send[0]);
                    $toSend = new ObjToSend("Twierdza", $json_to_send[0]['lvl'], $json_to_send[0]['typ'], $_json_to_send);
                    return $toSend;
                    break;
                }
            
            default:
                echo "Brak danych";
                break;
        }
    }

    if(isset($json['data']) && $json['data'] == "monsters"){

        echo json_encode(getDataMonsters($potwory));

    } 

    if(isset($json['data']) && $json['data'] == "armia"){
        
        echo json_encode(getDataArmia());
    }

    if (isset($json['id'])) {

        echo json_encode(getSingleArmia($json['id']));

    }

    if (isset($json['typSingle']) && isset($json['idSingle'])) {

        echo json_encode(getSingleMonster($json['typSingle'], $json['idSingle']));

    }
    $db->close();

?>