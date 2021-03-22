<?php
session_start();
if((isset($_POST['meni'])) && ($_POST['meni'] == 1)){
    echo '
    <section class="tiny-nav"><span>Calculator/Calculator<i class="new"> Old</i></span></section>
    <section class="old-calculator">
            <div id="meni">
                <ul>
                    <li><a href="#">Zwykle</a>
                        <ol>
                            <li class="lvl">Lvl/Typ</li>
                            <li class="nieumarly">Nieumarły</li>
                            <li class="elf">Elfy</li>
                            <li class="wyklety">Wyklęci</li>
                            <li class="barbarzynca">Barbarzyńcy</li>
                            <li class="piekielny-demon">Piekielni</li>
                            <div style="clear:both"></div>';
                            zwykly();
    echo					'</ol>
                    </li>
                    <li><a href="#">Rzadkie</a>
                        <ol>
                            <li class="lvl">Lvl/Typ</li>
                            <li class="nieumarly">Nieumarły</li>
                            <li class="elf">Elfy</li>
                            <li class="wyklety">Wyklęci</li>
                            <li class="barbarzynca">Barbarzyńcy</li>
                            <li class="piekielny-demon">Piekielni</li>
                            <div style="clear:both"></div>';
                            rzadki();
    echo					'</ol>
                    </li>
                    <li><a href="#">Heroiczne</a>
                        <ol>
                            <li class="lvl">Lvl/Typ</li>
                            <li class="nieumarly">Nieumarły</li>
                            <li class="elf">Elfy</li>
                            <li class="wyklety">Wyklęci</li>
                            <li class="barbarzynca">Barbarzyńcy</li>
                            <li class="piekielny-demon">Piekielni</li>
                            <div style="clear:both"></div>';
                            heroiczny();
    echo					'</ol>
                    </li>
                    <li><a href="#">Inne</a>
                        <ol>
                            <li class="lvl">Lvl/Typ</li>
                            <li class="nieumarly">Nieumarły</li>
                            <li class="elf">Elfy</li>
                            <li class="wyklety">Wyklęci</li>
                            <li class="barbarzynca">Barbarzyńcy</li>
                            <li class="piekielny-demon">Piekielni</li>
                            <div style="clear:both"></div>';
                            inny();
    echo					'</ol>
                    </li>
                </ul>
            </div style="clear:both">
            <div id="kalkulator"><h2>Proszę wybrać co chesz zaatakować!<br/> Z menu powyżej...</h2></div>';
            echo '<div id="dane_gracza">';
            gracz();
            echo '</div>';
            echo '<div id="przyciski">
                    <button id="upper">Plus</button>
                    <button id="lower">Minus</button>
            Gracz - <input  class="box" type="checkbox" name="gracz" value="1"/>
                    </div>';
            echo '<div id="raport"></div><section>';
}
if((isset($_POST['zwykly_id'])) || (isset($_POST['rzadki_id'])) || (isset($_POST['heroiczny_id'])) || (isset($_POST['inne_id']))){
    $wiersz = 0;
    $sql = '';
    $jaki = '';
    if(isset($_POST['zwykly_id'])){
        $wiersz = 2;
        $jaki = 'Zwykły';
        $int = $_POST['zwykly_id'];
        if(filter_var($int, FILTER_VALIDATE_INT) === 0 || filter_var($int, FILTER_VALIDATE_INT)){
        $sql = 'SELECT * FROM `zwykle` WHERE `idzwykly`='.$int;
        kalku($wiersz, $sql, $jaki);
        } else{
            echo "The <b>$int</b> is a invalid value";
        }
    }
    elseif(isset($_POST['rzadki_id'])){
        $wiersz = 3;
        $jaki = 'Rzadki';
        $int = $_POST['rzadki_id'];
        if(filter_var($int, FILTER_VALIDATE_INT) === 0 || filter_var($int, FILTER_VALIDATE_INT)){
        $sql = 'SELECT * FROM `rzadkie` WHERE `idrzadki`='.$int;
        kalku($wiersz, $sql, $jaki);
        } else{
            echo "The <b>$int</b> is a invalid value";
        }
    }
    elseif(isset($_POST['heroiczny_id'])){
        $wiersz = 4;
        $jaki = 'Heroiczny';
        $int = $_POST['heroiczny_id'];
        if(filter_var($int, FILTER_VALIDATE_INT) === 0 || filter_var($int, FILTER_VALIDATE_INT)){
        $sql = 'SELECT * FROM `heroiczne` WHERE `idheroiczny`='.$int;
        kalku($wiersz, $sql, $jaki);
        } else{
            echo "The <b>$int</b> is a invalid value";
        }
    }
    elseif(isset($_POST['inne_id'])){
        $wiersz = 6;
        $jaki = 'Twierdza';
        $int = $_POST['inne_id'];
        if(filter_var($int, FILTER_VALIDATE_INT) === 0 || filter_var($int, FILTER_VALIDATE_INT)){
        $sql = 'SELECT * FROM `inne` WHERE `idinne`='.$int;
        kalku($wiersz, $sql, $jaki);
        } else{
            echo "The <b>$int</b> is a invalid value";
        }
    }
}
if((isset($_POST['upper'])) || (isset($_POST['lower']))){
        gracz();
}
if((isset($_POST['change'])) || (isset($_POST['keyup']))){
    gracz();
}
function kolor($lvl){
    switch ($lvl)
    {
        case 0:
        {
        return "#444444";
        break;
        }
        case 1:
        {
        return "#C0C0C0";
        break;
        }
        case 2:
        {
        return "#008000";
        break;
        }
        case 3:
        {
        return "#0000FF";
        break;
        }
        case 4:
        {
        return "#800080";
        break;
        }
        case 5:
        {
        return "#FF6600";
        break;
        }
        case 6:
        {
        return "#CC0000";
        break;
        }
        case 7:
        {
        return "#FFCC00";
        break;
        }
        default:
        {
        return "lightgray";
        break;
        }
    }
}
function zwykly(){
    //-----------ZAPYTANIE DO BAZY DANYCH I WYWOLANIE FUNKCJI LACZACEJ SIE I WCZYTUJACEJ DANE DO SESII---------------
    $sql = "SELECT `idzwykly`,`lvl`,`typ` FROM `zwykle` ORDER BY `zwykle`.`lvl` ASC";
    $zwykly = login($sql);
    $licz_zwykly = count($zwykly);
    $id = '"zwykly"';
    $id2 = 'idzwykly';
    menu($zwykly, $licz_zwykly, $id, $id2);
}
function rzadki(){
    //-----------ZAPYTANIE DO BAZY DANYCH I WYWOLANIE FUNKCJI LACZACEJ SIE I WCZYTUJACEJ DANE DO SESII---------------
    $sql = "SELECT `idrzadki`,`lvl`,`typ` FROM `rzadkie` ORDER BY `rzadkie`.`lvl` ASC";
    $rzadki = login($sql);
    $licz_rzadki = count($rzadki);
    $id = '"rzadki"';
    $id2 = 'idrzadki';
    menu($rzadki, $licz_rzadki, $id, $id2);
}
function heroiczny(){
    //-----------ZAPYTANIE DO BAZY DANYCH I WYWOLANIE FUNKCJI LACZACEJ SIE I WCZYTUJACEJ DANE DO SESII---------------
    $sql = "SELECT `idheroiczny`,`lvl`,`typ` FROM `heroiczne` ORDER BY `heroiczne`.`lvl` ASC";
    $heroiczny = login($sql);
    $licz_heroiczny = count($heroiczny);
    $id = '"heroiczny"';
    $id2 = 'idheroiczny';
    menu_heroiczny($heroiczny, $licz_heroiczny, $id, $id2);
}
function inny(){
    $sql = "SELECT `idinne`,`lvl`,`typ` FROM `inne` ORDER BY `inne`.`lvl` ASC";
    $inne = login($sql);
    $licz_inne = count($inne);
    $id = '"inne"';
    $id2 = 'idinne';
    menu_inne($inne, $licz_inne, $id, $id2);
}
function menu_inne($rzadki, $licznik, $id, $id2){
    $wiersz = 0;
    $lvl = $nieumarly = $elf = $wyklety = $barbarzynca = $piekielny = '<li class="brak">Brak</li>';
    for($i=0;$i<$licznik;$i++)
    {
        if($rzadki[$i]['typ']=='Nieumarly')
        {
            $nieumarly = '<li class="'.$rzadki[$i]['typ'].'" id='.$id.' value="'.$rzadki[$i][$id2].'">'.$rzadki[$i]['lvl'].' '.$rzadki[$i]['typ'].'</li>';
            $wiersz++;
        }
        if($rzadki[$i]['typ']=='Elf')		
        {
            $elf = '<li class="'.$rzadki[$i]['typ'].'" id='.$id.' value="'.$rzadki[$i][$id2].'">'.$rzadki[$i]['lvl'].' '.$rzadki[$i]['typ'].'</li>';
            $wiersz++;
        }
        if($rzadki[$i]['typ']=='Wyklety')		
        {
            $wyklety = '<li class="'.$rzadki[$i]['typ'].'" id='.$id.' value="'.$rzadki[$i][$id2].'">'.$rzadki[$i]['lvl'].' '.$rzadki[$i]['typ'].'</li>';
            $wiersz++;
        }
        if($rzadki[$i]['typ']=='Barbarzynca')		
        {
            $barbarzynca = '<li class="'.$rzadki[$i]['typ'].'" id='.$id.' value="'.$rzadki[$i][$id2].'">'.$rzadki[$i]['lvl'].' '.$rzadki[$i]['typ'].'</li>';
            $wiersz++;
        }
        if($rzadki[$i]['typ']=='Piekielny-Demon')
        {
            $piekielny = '<li class="'.$rzadki[$i]['typ'].'" id='.$id.' value="'.$rzadki[$i][$id2].'">'.$rzadki[$i]['lvl'].' '.$rzadki[$i]['typ'].'</li>';
            $wiersz++;
        }

        if(($rzadki[$i]['lvl'] <= 15) && ($wiersz == 1))
        {
                $lvl = '<li class="lvl">'.$rzadki[$i]['lvl'].'</li>'; 
                echo $lvl.$nieumarly.$elf.$wyklety.$barbarzynca.$piekielny;
                echo '<div style="clear:both"></div>';
                $nieumarly = $elf = $wyklety = $barbarzynca = $piekielny = '<li class="brak">Brak</li>';
                $wiersz = 0;
        }
        if(($rzadki[$i]['lvl'] <= 25) && ($wiersz == 2))
        {
                $lvl = '<li class="lvl">'.$rzadki[$i]['lvl'].'</li>'; 
                echo $lvl.$nieumarly.$elf.$wyklety.$barbarzynca.$piekielny;
                echo '<div style="clear:both"></div>';
                $nieumarly = $elf = $wyklety = $barbarzynca = $piekielny = '<li class="brak">Brak</li>';
                $wiersz = 0;
        }
        if(($rzadki[$i]['lvl'] > 25) && ($wiersz == 1))
        {
                $lvl = '<li class="lvl">'.$rzadki[$i]['lvl'].'</li>'; 
                echo $lvl.$nieumarly.$elf.$wyklety.$barbarzynca.$piekielny;
                echo '<div style="clear:both"></div>';
                $nieumarly = $elf = $wyklety = $barbarzynca = $piekielny = '<li class="brak">Brak</li>';
                $wiersz = 0;
        }
    }
}
function menu_heroiczny($rzadki, $licznik, $id, $id2){
    $wiersz = 0;
    $lvl = $nieumarly = $elf = $wyklety = $barbarzynca = $piekielny = '<li class="brak">Brak</li>';
    for($i=0;$i<$licznik;$i++)
    {
        if($rzadki[$i]['typ']=='Nieumarly')
        {
            $nieumarly = '<li class="'.$rzadki[$i]['typ'].'" id='.$id.' value="'.$rzadki[$i][$id2].'">'.$rzadki[$i]['lvl'].' '.$rzadki[$i]['typ'].'</li>';
            $wiersz++;
        }
        if($rzadki[$i]['typ']=='Elf')		
        {
            $elf = '<li class="'.$rzadki[$i]['typ'].'" id='.$id.' value="'.$rzadki[$i][$id2].'">'.$rzadki[$i]['lvl'].' '.$rzadki[$i]['typ'].'</li>';
            $wiersz++;
        }
        if($rzadki[$i]['typ']=='Wyklety')		
        {
            $wyklety = '<li class="'.$rzadki[$i]['typ'].'" id='.$id.' value="'.$rzadki[$i][$id2].'">'.$rzadki[$i]['lvl'].' '.$rzadki[$i]['typ'].'</li>';
            $wiersz++;
        }
        if($rzadki[$i]['typ']=='Barbarzynca')		
        {
            $barbarzynca = '<li class="'.$rzadki[$i]['typ'].'" id='.$id.' value="'.$rzadki[$i][$id2].'">'.$rzadki[$i]['lvl'].' '.$rzadki[$i]['typ'].'</li>';
            $wiersz++;
        }
        if($rzadki[$i]['typ']=='Piekielny-Demon')
        {
            $piekielny = '<li class="'.$rzadki[$i]['typ'].'" id='.$id.' value="'.$rzadki[$i][$id2].'">'.$rzadki[$i]['lvl'].' '.$rzadki[$i]['typ'].'</li>';
            $wiersz++;
        }

        if(($rzadki[$i]['lvl'] <= 50) && ($wiersz == 1))
        {
                $lvl = '<li class="lvl">'.$rzadki[$i]['lvl'].'</li>'; 
                echo $lvl.$nieumarly.$elf.$wyklety.$barbarzynca.$piekielny;
                echo '<div style="clear:both"></div>';
                $nieumarly = $elf = $wyklety = $barbarzynca = $piekielny = '<li class="brak">Brak</li>';
                $wiersz = 0;
        }
    }
}
function menu($rzadki, $licznik, $id, $id2){
    $wiersz = 0;
    $lvl = $nieumarly = $elf = $wyklety = $barbarzynca = $piekielny = '<li class="brak">Brak</li>';
    for($i=0;$i<$licznik;$i++)
    {
        if($rzadki[$i]['typ']=='Nieumarly')
        {
            $nieumarly = '<li class="'.$rzadki[$i]['typ'].'" id='.$id.' value="'.$rzadki[$i][$id2].'">'.$rzadki[$i]['lvl'].' '.$rzadki[$i]['typ'].'</li>';
            $wiersz++;
        }
        if($rzadki[$i]['typ']=='Elf')		
        {
            $elf = '<li class="'.$rzadki[$i]['typ'].'" id='.$id.' value="'.$rzadki[$i][$id2].'">'.$rzadki[$i]['lvl'].' '.$rzadki[$i]['typ'].'</li>';
            $wiersz++;
        }
        if($rzadki[$i]['typ']=='Wyklety')		
        {
            $wyklety = '<li class="'.$rzadki[$i]['typ'].'" id='.$id.' value="'.$rzadki[$i][$id2].'">'.$rzadki[$i]['lvl'].' '.$rzadki[$i]['typ'].'</li>';
            $wiersz++;
        }
        if($rzadki[$i]['typ']=='Barbarzynca')		
        {
            $barbarzynca = '<li class="'.$rzadki[$i]['typ'].'" id='.$id.' value="'.$rzadki[$i][$id2].'">'.$rzadki[$i]['lvl'].' '.$rzadki[$i]['typ'].'</li>';
            $wiersz++;
        }
        if($rzadki[$i]['typ']=='Piekielny-Demon')
        {
            $piekielny = '<li class="'.$rzadki[$i]['typ'].'" id='.$id.' value="'.$rzadki[$i][$id2].'">'.$rzadki[$i]['lvl'].' '.$rzadki[$i]['typ'].'</li>';
            $wiersz++;
        }

        if(($rzadki[$i]['lvl'] <= 10) && ($wiersz == 1))
        {
                $lvl = '<li class="lvl">'.$rzadki[$i]['lvl'].'</li>'; 
                echo $lvl.$nieumarly.$elf.$wyklety.$barbarzynca.$piekielny;
                echo '<div style="clear:both"></div>';
                $nieumarly = $elf = $wyklety = $barbarzynca = $piekielny = '<li class="brak">Brak</li>';
                $wiersz = 0;
        }
        else if(($rzadki[$i]['lvl'] <= 15) && ($wiersz == 2))
        {
                $lvl = '<li class="lvl">'.$rzadki[$i]['lvl'].'</li>'; 
                echo $lvl.$nieumarly.$elf.$wyklety.$barbarzynca.$piekielny;
                echo '<div style="clear:both"></div>';
                $nieumarly = $elf = $wyklety = $barbarzynca = $piekielny = '<li class="brak">Brak</li>';
                $wiersz = 0;
        }
        else if(($rzadki[$i]['lvl'] <= 20) && ($wiersz == 3))
        {
                $lvl = '<li class="lvl">'.$rzadki[$i]['lvl'].'</li>'; 
                echo $lvl.$nieumarly.$elf.$wyklety.$barbarzynca.$piekielny;
                echo '<div style="clear:both"></div>';
                $nieumarly = $elf = $wyklety = $barbarzynca = $piekielny = '<li class="brak">Brak</li>';
                $wiersz = 0;
        }
        else if(($rzadki[$i]['lvl'] <= 35) && ($wiersz == 5))
        {
                $lvl = '<li class="lvl">'.$rzadki[$i]['lvl'].'</li>'; 
                echo $lvl.$nieumarly.$elf.$wyklety.$barbarzynca.$piekielny;
                echo '<div style="clear:both"></div>';
                $nieumarly = $elf = $wyklety = $barbarzynca = $piekielny = '<li class="brak">Brak</li>';
                $wiersz = 0;
        }
        else if(
            (($rzadki[$i]['lvl'] == 36) && ($wiersz == 3)) ||
            (($rzadki[$i]['lvl'] == 37) && ($wiersz == 4)) ||
            (($rzadki[$i]['lvl'] == 38) && ($wiersz == 3)) ||
            (($rzadki[$i]['lvl'] == 39) && ($wiersz == 3)) ||
            (($rzadki[$i]['lvl'] == 40) && ($wiersz == 3)) ||
            (($rzadki[$i]['lvl'] == 41) && ($wiersz == 3)) ||
            (($rzadki[$i]['lvl'] == 42) && ($wiersz == 4)) ||
            (($rzadki[$i]['lvl'] == 43) && ($wiersz == 3)) ||
            (($rzadki[$i]['lvl'] == 44) && ($wiersz == 3)) ||
            (($rzadki[$i]['lvl'] == 45) && ($wiersz == 3))) //wiersz == 2 dokonac zmiany po dodaniu kolejnych baz danych
        {
                $lvl = '<li class="lvl">'.$rzadki[$i]['lvl'].'</li>'; 
                echo $lvl.$nieumarly.$elf.$wyklety.$barbarzynca.$piekielny;
                echo '<div style="clear:both"></div>';
                $nieumarly = $elf = $wyklety = $barbarzynca = $piekielny = '<li class="brak">Brak</li>';
                $wiersz = 0;
        }
    }
}
function login($sql){
    $tablica = [];
    require "../conectionServer/dbconnect.php";
    $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
    if ($polaczenie->connect_errno) 
    {
        die("Connection failed: " . $polaczenie->connect_errno);
    }
    else
    {
        $rezultat = $polaczenie->query($sql);
        while($wiersz = $rezultat->fetch_assoc())
            {				 
                array_push($tablica, $wiersz);
            }
        $polaczenie->close();
    }
    return $tablica;
}	
function gracz(){
    $sql ="SELECT * FROM armia";
    $gracz = login($sql);
    $_SESSION['armia_walka'] = [];
    echo '<h3>Wybierz jednostki którymi chcesz atakować i uzulpełnij bonusy ataku/życia</h3>';
	 echo '<table id="tabela">';
	 echo '<tbody id="gracz" class="gracz-old-menu">';
	 echo '<tr style="background-color: orange">';
	 echo '<th>Jednostki Wlasne</th>';
	 echo '<th>Ilosc</th>';
	 echo "<th>Typ</th>";
	 echo "<th>Bonus</th>";
	 echo '<th>Sila Ataku</th>';
	 echo "<th>Zycie</th>";
	 echo '<th>Bonus Ataku</th>';
	 echo "<th>Bonus Zycia</th>";
	 echo "</tr>";
    //*****************************PETLA DO WHILE WYPISUJACA ILE RAZY SELECT***************************
     //ile wyswietlanych jednostek
     if(isset($_POST['obecnie'])){
         $flag = $_POST['obecnie'];
     } else {
         $flag = 1;
     }
     if(isset($_POST['valflag'])){
         $pierwszy = $_POST['valflag'];//tablica jednostek gracza 
     } else {
         $pierwszy[0] = 0;
     }
     if(isset($_POST['upper'])){
         $flag += 1;
         if($flag > 10){
             $flag = 10;
         }
     }
     if(isset($_POST['lower']))
         $flag -= 1;
         if($flag == 0)
            $flag = 1;
	 $f1 = 0;
    while($f1 < $flag)
        {
            $wiersz = 0;
        $licz_armia = count($gracz);
        $flaga = 1;
        $flagb = 1;	//ok
        echo "<tr>";
        echo '<td style="padding:0;padding-top: 5px;background-color: ';
        for($i = 0; $i < $licz_armia; $i++)
        {
            if($pierwszy[$f1]==$i){
                    echo kolor($gracz[$i]['lvl']);
                    $wiersz = $gracz[$i];
                    if(isset($_POST['ilosc'])){
                        if($_POST['ilosc'][$f1] > 0)
                        $wiersz['ile_jednostek'] = $_POST['ilosc'][$f1];
                        else
                        $wiersz['ile_jednostek'] = 0;
                    } else {
                        $wiersz['ile_jednostek'] = 0;
                    }
                }
        }
        $wiersz['odbyty_atak'] = 1;

        if(isset($_POST['bonus_zycia'])){
            if($_POST['bonus_zycia'] > 0)
                $wiersz['bonus_zycia'] = $_POST['bonus_zycia'][$f1];
            else
                $wiersz['bonus_zycia'] = 0;
        }
        else 
            $wiersz['bonus_zycia'] = 0;

        if(isset($_POST['bonus_ataku'])){
            if ($_POST['bonus_ataku'] > 0)
                $wiersz['bonus_ataku'] = $_POST['bonus_ataku'][$f1];
            else
            $wiersz['bonus_ataku'] = 0;
        }
        else 
            $wiersz['bonus_ataku'] = 0;


        echo '">';	 
        echo '<select name="armia'.$f1.'" id="pierwszy'.$f1.'"style="width: 140px;height: 20px;">';
        for($i = 0; $i < $licz_armia; $i++)
            {
                if($flagb == $gracz[$i]['typ'])
                {
                        echo '<option value="'.$gracz[$i]['idarmia'].'" style=color:'.kolor($gracz[$i]['lvl']);
                            if($pierwszy[$f1]==$i)
                                echo " selected";
                        echo '>'.$gracz[$i]['nazwa'].' '.$gracz[$i]['lvl'].'</option>';
                }
                else
                {
                    $flagb = $gracz[$i]['typ'];
                    if($flaga == 1)
                    {
                            echo '<optgroup label="'.$gracz[$i]['typ'].'">';
                            echo '<option value="'.$gracz[$i]['idarmia'].'" style=color:'.kolor($gracz[$i]['lvl']);
                            if($pierwszy[$f1]==$i)
                                echo " selected";
                            echo '>'.$gracz[$i]['nazwa'].' '.$gracz[$i]['lvl'].'</option>';
                            $flaga = 0;
                    }
                        else
                    {
                            echo "</optgroup>";
                            echo '<optgroup label="'.$gracz[$i]['typ'].'">';
                            echo '<option value="'.$gracz[$i]['idarmia'].'" style=color:'.kolor($gracz[$i]['lvl']);
                            if($pierwszy[$f1]==$i)
                                echo " selected";
                            echo '>'.$gracz[$i]['nazwa'].' '.$gracz[$i]['lvl'].'</option>';
                            $flaga = 1;
                    }
                }
            }
            echo "</optgroup>";
            echo "</td>";		 		 
            // SKOPIOWAC DO ZADANIA PHP	 
            echo '<td style="padding:0;padding-top: 5px;">';
            echo '<input type="number" value="';
            if(isset($_POST['ilosc']))
                echo $_POST['ilosc'][$f1];
            else 
                echo 0;
            echo '" min="0" max="2000000" class="ilosc" id="ilosc'.$f1.'"></input>';
            echo "</td>";
            //-------TYP
            echo "<td>";
            for($i = 0; $i < $licz_armia; $i++)
            {
                if($pierwszy[$f1]==$i)
                {
                        echo "Podstawa<br />";
                        if($gracz[$i]['typ1'])
                            echo $gracz[$i]['typ1'];
                        if($gracz[$i]['typ2'])
                            echo "<br />".$gracz[$i]['typ2'];
                        if($gracz[$i]['typ3'])
                            echo "<br />".$gracz[$i]['typ3'];
                }
            }
            echo "</td>";
            //-------BONUS
            echo '<td style="width: 170px";>----------<br />';
            for($i = 0; $i < $licz_armia; $i++)
            {
                if($pierwszy[$f1]==$i)
                {
                    if ($gracz[$i]['bonus_ile1'])
                        echo $gracz[$i]['bonus_ile1']."% ".$gracz[$i]['bonus_komu1'];
                    if ($gracz[$i]['bonus_ile2'])
                        echo "<br />".$gracz[$i]['bonus_ile2']."% ".$gracz[$i]['bonus_komu2'];
                    if ($gracz[$i]['bonus_ile3'])
                        echo "<br />".$gracz[$i]['bonus_ile3']."% ".$gracz[$i]['bonus_komu3'];
                }
            }
            echo "</td>";
            //-------SILA ATAKU
            echo "<td>";
            for($i = 0; $i < $licz_armia; $i++)
            {
                if($pierwszy[$f1]==$i)
                {
                    if(isset($_POST['ilosc']))
                    {
                        if (is_numeric($_POST['ilosc'][$f1]))
                        {
                                echo $_POST['ilosc'][$f1]*(($gracz[$i]['sila']/100)*(100 + ((isset($_POST['bonus_ataku']))? $_POST['bonus_ataku'][$f1] : 0)));
                                $wiersz['atak_sila'] = $_POST['ilosc'][$f1]*(($gracz[$i]['sila']/100)*(100 + ((isset($_POST['bonus_ataku']))? $_POST['bonus_ataku'][$f1] : 0)));
                            if ($gracz[$i]['bonus_ile1'])
                                echo "<br />".$_POST['ilosc'][$f1]*(($gracz[$i]['sila']/100)*(100+$gracz[$i]['bonus_ile1']+ ((isset($_POST['bonus_ataku']))? $_POST['bonus_ataku'][$f1] : 0)));
                            if ($gracz[$i]['bonus_ile2'])
                                echo "<br />".$_POST['ilosc'][$f1]*(($gracz[$i]['sila']/100)*(100+$gracz[$i]['bonus_ile2']+ ((isset($_POST['bonus_ataku']))? $_POST['bonus_ataku'][$f1] : 0)));
                            if ($gracz[$i]['bonus_ile3'])
                                echo "<br />".$_POST['ilosc'][$f1]*(($gracz[$i]['sila']/100)*(100+$gracz[$i]['bonus_ile3']+ ((isset($_POST['bonus_ataku']))? $_POST['bonus_ataku'][$f1] : 0)));
                        }
                        else{
                            echo "Podaj poprawna ilosc";
                            $wiersz['atak_sila'] = 0;
                        }
                    }
                }
            }
            echo "</td>";
            //-------ZYCIE
            echo "<td>";
            for($i = 0; $i < $licz_armia; $i++)
            {
                if($pierwszy[$f1]==$i)
                {
                    if(isset($_POST['ilosc']))
                    {
                        if (is_numeric($_POST['ilosc'][$f1]))
                        {
                            echo $_POST['ilosc'][$f1]*(($gracz[$i]['zycia']/100)*(100+((isset($_POST['bonus_zycia']))? $_POST['bonus_zycia'][$f1] : 0)));
                            $wiersz['zycie_walka'] = $_POST['ilosc'][$f1]*(($gracz[$i]['zycia']/100)*(100+((isset($_POST['bonus_zycia']))? $_POST['bonus_zycia'][$f1] : 0)));
                        }
                        else{
                            echo "Podaj poprawna ilosc";
                            $wiersz['zycie_walka'] = 0;
                        }
                    }
                }
            }
            echo "</td>";
             //-------BONUS ATAKU
            echo '<td style="padding:0;padding-top: 5px;">';
            echo '<input type="number" value="';
            if(isset($_POST['bonus_ataku']))
			    echo $_POST['bonus_ataku'][$f1];
		    else 
			    echo 0;
            echo '" min="0" step="0.5" max="5000" class="bonus_ataku" id="bonus_ataku'.$f1.'"></input>';
            echo "</td>";
             //-------BONUS ZYCIA
            echo '<td style="padding:0;padding-top: 5px;">';
            echo '<input type="number" value="';
            if(isset($_POST['bonus_zycia']))
			    echo $_POST['bonus_zycia'][$f1];
		    else 
			    echo 0;
            echo '" min="0" step="0.5" max="5000" class="bonus_zycia" id="bonus_zycia'.$f1.'"></input>';
            echo "</td>";
            // SKOPIOWAC DO TEGO MOMENTU
            echo "</tr>";
            if((isset($wiersz['zycie_walka'])) && ($wiersz['zycie_walka'] > 0))
            $_SESSION['armia_walka'][] = $wiersz;
            $f1++;
        }
        //****************************KONIEC PETLI*************************
        echo "</tbody>";
        echo "</table>";
}
//&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
function kalku($ile, $sql, $jaki){
     $potwory_walka = login($sql);
     $potwory_walka = $potwory_walka[0];
     $_SESSION['potwory_walka'] = [];
     $licz_potwory = 0;
     for($i = 1; $i <= $ile; $i++){
        $nazwa = 'nazwa'.$i;
        if(isset($potwory_walka[$nazwa])){
            $sql = "SELECT * FROM `potwory` WHERE nazwa='$potwory_walka[$nazwa]'";
            $rad = login($sql);
            $potwory_walka[$nazwa] = $rad[0];
        }
     }
    echo '<h3>'.$jaki.' Lvl: '.$potwory_walka['lvl'].' Typ: '.$potwory_walka['typ'].'</h3>';
    echo '<table id="tabela">';
    echo '<tbody id="potwory">';
    echo '<tr style="background-color: orange">';
    echo '<th>Jednostki Wroga</th>';
    echo '<th>Ilosc</th>';
    echo "<th>Typ</th>";
    echo '<th style="width: 170px;">Bonus</th>';
    echo '<th>Sila Ataku</th>';
    echo "<th>Zycie</th>";
    echo "</tr>";
    for($i = $ile; $i >= 1; $i--)
    {
        $nazwa = 'nazwa'.$i;
        $ile = 'ile'.$i;
        $wiersz = 0;
        if(isset($potwory_walka[$nazwa]))
        {
            $wiersz = $potwory_walka[$nazwa];
            $wiersz['ile_jednostek'] = $potwory_walka[$ile];
            $wiersz['odbyty_atak'] = 1;
            $wiersz['zycie_walka'] = $potwory_walka[$ile]*$potwory_walka[$nazwa]['zycie'];
            $wiersz['atak_sila'] = $potwory_walka[$ile] * $potwory_walka[$nazwa]['sila'];
            echo "<tr>";
            echo '<td style="background-color: '.kolor($potwory_walka[$nazwa]['lvl']).'">'.$potwory_walka[$nazwa]['nazwa'].'</td>';
            echo '<td>'.$potwory_walka[$ile]."</td>";
            echo "<td>Podstawa<br />";
            if($potwory_walka[$nazwa]['typ1'])
            echo $potwory_walka[$nazwa]['typ1'];
            if($potwory_walka[$nazwa]['typ2'])
                echo "<br />".$potwory_walka[$nazwa]['typ2'];
            if($potwory_walka[$nazwa]['typ3'])
                echo "<br />".$potwory_walka[$nazwa]['typ3'];
            echo "</td>";
            echo "<td>----------<br />";
            if ($potwory_walka[$nazwa]['bonus_ile1'])
            echo $potwory_walka[$nazwa]['bonus_ile1']."% ".$potwory_walka[$nazwa]['bonus_komu1'];
            if ($potwory_walka[$nazwa]['bonus_ile2'])
                echo "<br />".$potwory_walka[$nazwa]['bonus_ile2']."% ".$potwory_walka[$nazwa]['bonus_komu2'];
            if ($potwory_walka[$nazwa]['bonus_ile3'])
                echo "<br />".$potwory_walka[$nazwa]['bonus_ile3']."% ".$potwory_walka[$nazwa]['bonus_komu3'];
            echo "</td>";
            echo "<td>".$potwory_walka[$ile] * $potwory_walka[$nazwa]['sila'];
            if ($potwory_walka[$nazwa]['bonus_ile1'])
                echo "<br />".$potwory_walka[$ile]*(($potwory_walka[$nazwa]['sila']/100)*(100+$potwory_walka[$nazwa]['bonus_ile1']));
            if ($potwory_walka[$nazwa]['bonus_ile2'])
                echo "<br />".$potwory_walka[$ile]*(($potwory_walka[$nazwa]['sila']/100)*(100+$potwory_walka[$nazwa]['bonus_ile2']));
            if ($potwory_walka[$nazwa]['bonus_ile3'])
                echo "<br />".$potwory_walka[$ile]*(($potwory_walka[$nazwa]['sila']/100)*(100+$potwory_walka[$nazwa]['bonus_ile3']));
            echo "</td>";
            echo '<td>'.$potwory_walka[$ile]*$potwory_walka[$nazwa]['zycie']."</td>";
            echo "</tr>";
            if(($jaki == 'Zwykły') || ($jaki == 'Rzadki')){// || ($jaki == 'Twierdza')   --- dodac po dopisaniu warunku TWIERDZY w ataku
                $_SESSION['potwory_walka'][] = $wiersz;
            }
            if($jaki == 'Heroiczny'){
                for ($k=0; $k < 5; $k++) { 
                    $_SESSION['potwory_walka'][] = $wiersz;
                }
            }
        }
    }
    echo '</table>';
    echo "</tbody>";
}

?>