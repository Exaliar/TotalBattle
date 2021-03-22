<?php
    $button = 'Wyslij';
if (isset($_POST['contact_placeholder-email']) && !empty($_POST['contact_placeholder-email'])) {
    $email = $_POST['contact_placeholder-email'];
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        require_once "./php/conectionServer/dbconnect.php";
        require_once "./php/conectionServer/connection.php";
        require_once "./php/mail/validate.php";
        $_email = null;
        $db = connect($host, $db_user, $db_password, $db_name);
        $_email = sprawdz($_POST['contact_placeholder-email']);
        if($_email !== null){
            include("./php/mail/mail.php");
            Email($_email);
            $button = 'Wysłano';
        }
    }
}
?>
<section class="footer-main flex-center">
    <div class="menu-us flex-center">
        <h1 class="font-inconsolata">Kategorie</h1>
        <div class="footer-menu-nav flex-center">
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <a href="#" id="kalk"><i class="fas fa-calculator"></i> Calculator<i class="new"> Old</i></a>
            <a href="#" id="kalk_new"><i class="fas fa-calculator"></i> Calculator<i class="new"> New</i></a>
            <a href="#" id="proj"><i class="fas fa-comments"></i> Forum</a>
            <a href="#" id="about_me"><i class="fas fa-info"></i> About</a>
        </div>
    </div>
    <div class="contact-us flex-center">
        <h1 class="font-inconsolata">Połączmy siły</h1>
        <form action="#" class="emai-button" method="post">
            <input type="email" class="input-text-contact font-size-20" name="contact_placeholder-email" id="contact_placeholder-email" placeholder="Wpisz maila tutaj...">
            <input type="submit" class="input-button-contact font-size-20" id="contact_button-email" value="<?php echo $button; ?>">
        </form>
    </div>
    <div class="follow-us flex-center">
        <h1 class="font-inconsolata">Kontakt</h1>
        <div class="social-icon-footer flex-center">
            <a href="#" tittle="Facebook"><i class="fab fa-facebook-square"></i></a>
            <a href="#" tittle="Messenger"><i class="fab fa-facebook-messenger"></i></a>
            <a href="#" tittle="Instagram"><i class="fab fa-instagram-square"></i></a>
        </div>
    </div>
</section>