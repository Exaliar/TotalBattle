<?php
session_start();
require_once "../conectionServer/dbconnect.php";
require_once "../conectionServer/connection.php";
$db = connect($host, $db_user, $db_password, $db_name);

function wypiszPosty(){
    $sql = "SELECT `wpis_user`.`wpis`, `users`.`login`, `wpis_user`.`id` FROM `wpis_user`, `users` WHERE `poziom`='Komentarz' AND `wpis_user`.`id_z_users`=`users`.`id_user`";
    if(isset($_SESSION['zalogowany'])){
            $sql = "SELECT `users`.`login`, `wpis_user`.`wpis`, `wpis_user`.`id_z_users`, `wpis_user`.`id`  FROM `wpis_user`, `users` WHERE `poziom`='Komentarz' AND `wpis_user`.`id_z_users`=`users`.`id_user`";
            if($_SESSION['zalogowany']['dostep'] == 3){
                $sql = "SELECT `users`.`login`, `wpis_user`.`wpis`, `wpis_user`.`id_z_users`, `wpis_user`.`id`  FROM `wpis_user`, `users` WHERE `wpis_user`.`id_z_users`=`users`.`id_user`";
            }
        }
    $posty = array_reverse(login($sql));
    foreach ($posty as $value): ?>
        <div class="post_projekt" id="<?php echo $value['id']?>">
            <div class="user_projekt"><p class="login"><?php echo $value['login'];?></p></div>
            <div class="wpis_projekt"><p class="wpis"><?php echo $value['wpis'];?></p></div>
            <?php if(isset($_SESSION['zalogowany']['dostep'])):
                if(($_SESSION['zalogowany']['dostep'] > 1) || $_SESSION['zalogowany']['id_user'] == $value['id_z_users']): ?>
                    <div class="operacje_projekt">
                        <input type="button" class="button2 projekt" name="update_projekt" value="Edit">
                        <?php if($_SESSION['zalogowany']['dostep'] == 3): ?>
                            <input type="button" class="button3 projekt" name="delete_projekt" value="Delete">
                        <?php endif; ?>
                    </div>
            <?php endif;
            endif; ?>
        </div>
    <?php endforeach;
}
function login($sql){
    global $db;
    $db->set_charset("utf8");
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
function sprawdz($test){
    global $db;
    $test = $db->real_escape_string($test);
    return trim(htmlspecialchars($test));
}
if(isset($_POST['conent'])){
    if(empty($_POST['conent'])){
        wypiszPosty();
    } else {
        $sprawdzony_post = sprawdz($_POST['conent']);
        $id_user_post = 1;
        $poziom = "Zablokowany";
        if(isset($_SESSION['zalogowany']['id_user'])){
            $id_user_post = $_SESSION['zalogowany']['id_user'];
            $poziom = "Komentarz";
        }
        $sql = "INSERT INTO `wpis_user` (`id_z_users`, `wpis`, `poziom`, `data`) VALUES ('$id_user_post', '$sprawdzony_post', '$poziom', NOW())";
        $db->set_charset("utf8");
        $db->query($sql);
        wypiszPosty();
    }
}
if(isset($_POST['projekt'])){
    if($_POST['projekt'] == 1):?>
<section class="tiny-nav"><span>Forum/</span></section>
    <section class="post-projekt">
        <hr>
        <p>Jeżeli chcesz zostawić pomysł na dalszy rozwój strony zostaw komentarz ciekawe pomysły mile widziane</p>
        <hr>
        <div class="wyslij_projekt">
            <h2><?php echo (isset($_SESSION['zalogowany']) ? $_SESSION['zalogowany']['login'] : "Gość"); ?></h2>
            <textarea id="coment_1"></textarea>
            <input type="button" class="button button1" id="conent" value="Wyslij" style="width: 100%; border-radius: 0px 5px 0px 0px;"/>
        </div>
        <div id="projekt_content">
            <?php wypiszPosty(); ?>
        </div>
    </section>
    <?php endif;
}
$db->close();
?>