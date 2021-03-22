
        <?php if(!isset($_SESSION['zalogowany'])):?>
        <form class="flex-center">
          <div class="input-group">
            <input type="text" name="nick" autocomplete="nickname" placeholder="Login" id="nick1" />
          </div>
          <div class="input-group">
            <input type="password" name="pass" autocomplete="new-password" placeholder="Password" id="pass1" />
          </div>
          <div class="button-container-2 flex-center">
            <div class="input-group login-exchange">
              <input type="button" name="loguj" value="Zaloguj" id="zaloguj" class="button button1 btn-login-menu log-in" />
            </div>
            <div class="input-group login-exchange-next">
              <input type="button" name="rejestruj" value="Zarejestruj" id="rejestruj" class="button button3 btn-login-menu sing-in" />
            </div>
          </div>
        </form>
        <?php else: ?>
        <form class="flex-center">
          <div class="input-group">
            <label><?php echo "Witaj ".$_SESSION['zalogowany']['login']?></label>
          </div>
          <div class="input-group">
            <label><?php echo $_SESSION['zalogowany']['email']?></label>
          </div>
          <div class="input-group">
            <label><?php echo "Dostęp: ".$_SESSION['zalogowany']['dostep']?></label>
          </div>
          <div class="input-group">
            <label><?php echo "Klan: ".$_SESSION['zalogowany']['klan']?></label>
          </div>
          <div class="button-container-2 flex-center">
            <div class="input-group">
              <input type="button" class="button button1" id="panel" value="Panel" />
            </div>
            <div class="input-group">
              <input type="button" class="button button3" id="logout" value="Wyloguj" />
            </div>
          </div>
        </form>
        <?php endif; ?>

<?php 
session_start();
require_once "./php/conectionServer/dbconnect.php";
require_once "./php/conectionServer/connection.php";
$db = connect($host, $db_user, $db_password, $db_name);
function sprawdz($test){
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
    //var_dump($tablica);
    return $tablica;
}
function rejestracja($errors = [], $name = 0, $email = 0){
    echo    '<section class="registration-form flex-center">
                <h2>Rejestracja</h2>';
    if (count($errors) > 0){
        echo '<div class="error">';
            foreach ($errors as $error){
                echo '<p class="font-size-14">'.$error .'</p>';
            }
        echo '</div>';
    }
    echo    '<form method="post" action="./oldWeb/login.php" class="flex-center">
                <div class="input-group">
                    <label>Username</label>
                    <input type="text" name="username" value="';
                    if(!empty($name))
                    echo $name;
                    echo'" autocomplete="nickname">
                </div>
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" value="';
                    if(!empty($email))
                    echo $email;
                    echo '" autocomplete="off">
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password_1" autocomplete="new-password">
                </div>
                <div class="input-group">
                    <label>Confirm password</label>
                    <input type="password" name="password_2" autocomplete="new-password">
                </div>
                <div class="input-group">
                    <button type="button" class="btn button button1 btn-registration" name="register_btn">Register</button>
                </div>
            </form>
        </section>';
}
function zarejestrowany($id){
    if(!empty($id)): ?>
    <div id="panel1">
    <form class="flex-center">
        <div class="input-group">
            <label>
                <h2>
                    Witaj <?php echo $_SESSION['zalogowany']['login']?>!
                </h2>
            </label>
        </div>
        <div class="input-group">
            <label>
                <h3>
                    Email: <?php echo $_SESSION['zalogowany']['email']?>
                </h3>
            </label>
            <input type="email" name="email"  autocomplete="email" placeholder="Nowy Email" id="mail">
            <input type="button" name="email" value="Zmień" id="email_panel" class="button button1"/>
        </div>
        <div class="input-group">
            <input type="password" name="pass" autocomplete="new-password" placeholder="Stare hasło" id="pass_change1"/>
            <input type="password" name="pass" autocomplete="new-password" placeholder="Nowe hasło" id="pass_change2"/>
            <input type="password" name="pass" autocomplete="new-password" placeholder="Nowe hasło" id="pass_change3"/>
            <input type="button" name="pass" value="Zmień" id="pass_panel" class="button button1"/>
        </div>
        <div class="input-group">
            <h3>Twój obecny dostęp: </h3>
            <h3>
                <?php 
                echo ($_SESSION['zalogowany']['dostep'] == 1) ? "Podstawowy" : "";
                echo ($_SESSION['zalogowany']['dostep'] == 2) ? "Urzytkownik": "";
                echo ($_SESSION['zalogowany']['dostep'] == 3) ? "Administrator": ""; 
                ?>
            </h3>
        </div>
        <div class="input-group">
            <h3>Data założenia konta: </h3>
            <h3>
                <?php echo $_SESSION['zalogowany']['data']; ?>
            </h3>
        </div>
        <div class="input-group">
            <h3>Obecny klan: </h3>
            <h3><?php echo $_SESSION['zalogowany']['klan'];?></h3>
            <input type="text" name="klan" autocomplete="text" placeholder="Klan" id="klan"/>
            <input type="button" name="klan" value="Zmień" id="klan_panel" class="button button1"/>
        </div>
        <div class="input-group">
            <h3>Liczba wpisów na stronie: </h3>
            <h3>
                <?php
                $user = $_SESSION['zalogowany']['login'];
                $sql = "SELECT `wpis_user`.`id`, `wpis_user`.`wpis` FROM `wpis_user`, `users` WHERE `users`.`login`='$user' AND `wpis_user`.`id_z_users` = `users`.`id_user`"; 
                $ile = login($sql);
                echo (count($ile));
                ?>
            </h3>
        </div>
        <div class="input-group">
            <h3>Usuwanie konta!!!</h3>
            <input type="button" name="delete" value="Delete" id="delete_panel" class="button button3" disabled/>
        </div>
    </form>
    </div>
    <?php endif;
}
if(isset($_POST['panel'])){
    if(isset($_SESSION['zalogowany'])){
        zarejestrowany($_SESSION['zalogowany']['id_user']);
    }
}
if(isset($_POST['rejestruj'])){
    rejestracja();
}
if(isset($_POST['loguj'])){
    $error = [];
    $nick = '';
    $sprawdzony_nick = '';
    $pass = '';
    $sprawdzony_pass = '';
    $hash_pass = '';
    if(empty($_POST['nick'])){
        array_push($error, 'Podaj nick');
    } else {
        $nick = sprawdz($_POST['nick']);
        if(!preg_match('/^[a-zA-Z0-9\s]+$/', $nick)){
            array_push($error, 'Niepoprawny nick');
        } else
        {
            $sprawdzony_nick = $nick;
        }
    }
    if(empty($_POST['pass'])){
        array_push($error, 'Podaj hasło');
    } else {
        $pass = sprawdz($_POST['pass']);
        if(strlen($pass) < 6){
            array_push($error, 'Hasło min 6 znaków');
        } else {
            $sprawdzony_pass = mysqli_real_escape_string($db, $pass);
        }
    }
    if(!empty($error)){
        echo    '
                <form class="flex-center">
                    <div class="input-group" >
                        <input type="text" name="nick" autocomplete="nickname" placeholder="Nick" id="nick1"/>
                    </div>
                    <div class="input-group">
                        <input type="password" name="pass" autocomplete="new-password" placeholder="Password" id="pass1"/>
                    </div>
                    <div class="input-group">
                        <input type="button" name="loguj" value="Zaloguj" id="zaloguj" class="button button1"/>
                    </div>
                    <div class="input-group">
                        <input type="button" name="rejestruj" value="Zarejestruj" id="rejestruj" class="button button3"/>
                    </div>
                    <div class="error font-size-14">';
                        foreach($error as $er){ 
                        echo '<p class="font-size-14">'.$er."</p>";
                        }
        echo        '</div>
                </form>';
        unset($error);    
        exit;
    } else {
        $hash_pass = md5($sprawdzony_pass);
        $sql = "SELECT * FROM `users` WHERE `login` = '$sprawdzony_nick' AND `haslo` = '$hash_pass'";
        $pierwszy = (login($sql));
        if(!empty($pierwszy))
        $_SESSION['zalogowany'] = $pierwszy[0];
        if(!empty($_SESSION['zalogowany'])): ?>
            <form class="flex-center">
                <div class="input-group">
                    <label><?php echo "Witaj ".$_SESSION['zalogowany']['login']?></label>
                </div>
                <div class="input-group">
                    <label><?php echo $_SESSION['zalogowany']['email']?></label>
                </div>
                <div class="input-group">
                    <label><?php echo "Dostęp ".$_SESSION['zalogowany']['dostep']?></label>
                </div>
                <div class="input-group">
                    <label><?php echo "Klan ".$_SESSION['zalogowany']['klan']?></label>
                </div>
                <div class="input-group">
                    <input type="button" class="button button1" id="panel" value="Panel"/>
                </div>
                <div class="input-group">
                    <input type="button" class="button button3" id="logout" value="Wyloguj"/>
                </div>
            </form>
        <?php else : ?>
            <form class="flex-center">
                <div class="input-group" >
                    <input type="text" name="nick" autocomplete="nickname" placeholder="Nick" id="nick1"/>
                </div>
                <div class="input-group">
                    <input type="password" name="pass" autocomplete="new-password" placeholder="Password" id="pass1"/>
                </div>
                <div class="input-group">
                    <input type="button" name="loguj" value="Zaloguj" id="zaloguj" class="button button1"/>
                </div>
                <div class="input-group">
                    <input type="button" name="rejestruj" value="Zarejestruj" id="rejestruj" class="button button3"/>
                </div>
            </form>
        <?php endif;
    }
}
if(isset($_POST['logout'])): ?>
    <form>
        <div class="input-group" >
            <input type="text" name="nick" autocomplete="nickname" placeholder="Nick" id="nick1"/>
        </div>
        <div class="input-group">
            <input type="password" name="pass" autocomplete="new-password" placeholder="Password" id="pass1"/>
        </div>
        <div class="input-group">
            <input type="button" name="loguj" value="Zaloguj" id="zaloguj" class="button button1" style="height: 40px;"/>
        </div>
        <div class="input-group">
            <input type="button" name="rejestruj" value="Zarejestruj" id="rejestruj" class="button button3" style="height: 40px;"/>
        </div>
    </form>
    <?php unset($_SESSION['zalogowany']); 
    endif;
if(isset($_POST['name'], $_POST['email'], $_POST['pass_1'], $_POST['pass_2'])){
    $error = [];
    $name = "";
    $email = "";
    if(empty($_POST['name'])){
        array_push($error, 'Username jest puste');
    } else {
        $nick = sprawdz($_POST['name']);
        if(!preg_match('/^[a-zA-Z0-9\s]+$/', $nick)){
            array_push($error, 'Niepoprawny nick');
        } else {
            $sql = "SELECT * FROM `users` WHERE `login`='$nick'";
            $dane = login($sql);
            if(count($dane)){
                array_push($error, 'Login jest juz zajety');
            } else {
                $name = $nick;
            }
        }
    }
    if(empty($_POST['email'])){
        array_push($error, 'Email jest puste');
    } else {
        $mail = sprawdz($_POST['email']);
        if(!filter_var($mail, FILTER_VALIDATE_EMAIL)){
            array_push($error, 'Niepoprawny email');
        } else {
            $sql = "SELECT * FROM `users` WHERE `email`='$mail'";
            $dane = login($sql);
            if(count($dane)){
                array_push($error, 'Email jest juz uzywany');
            } else {
                $email = $mail;
            }
        }
    }
    if(empty($_POST['pass_1'])){
        array_push($error, 'Password jest puste');
    } else {
        $pass_1 = sprawdz($_POST['pass_1']);
        if(strlen($pass_1) < 6){
            array_push($error, 'Hasło min 6 znaków');
        }
    }
    if($_POST['pass_1']!=$_POST['pass_2']){
        array_push($error, 'Password nie jest taki sam');
    }
    if(count($error) == 0){
        $password = md5($pass_1);
        date_default_timezone_set('Europe/Warsaw');
        $sql = "INSERT INTO `users` (login, email, haslo, dostep, data) 
        VALUES ('$name', '$email', '$password', 1, NOW())";
        $db->query($sql);
        $id = $db->insert_id;
        $sql = "SELECT * FROM `users` WHERE `id_user`='$id'";
        $przerabianie = login($sql);
        $_SESSION['zalogowany'] = $przerabianie[0];
    } else {
        rejestracja($error, $name, $email);
    }
}
//var_dump($_POST);
$db->close();
?>