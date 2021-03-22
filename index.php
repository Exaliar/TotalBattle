<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kalkulator - Total Battle</title>
    <!--Css icon-->
    <link rel="stylesheet" href="./css/css/arrow.css" />
    <!--Css file-->
    <link rel="stylesheet" href="style.css" />
    <!-- biblioteka jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous" defer></script>
    <!-- Js file-->
    <script type="module" src="script.js" defer></script>
    <!-- Font awsome -->
    <script src="https://kit.fontawesome.com/ada93a1ab6.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <!--Top Menu-->
    <section class="top-menu shadow">
      <div class="logo flex-center">
        <span class="logo-head font-inconsolata font-size-24">TB</span>
        <p class="logo-tail font-inconsolata font-size-16">-data</p>
      </div>
      <!--Login / Sigin-->
      <section id="login" class="login flex-center font-size-20">
      <?php //include("./php/oldWeb/login.php");?>
      </section>
      <!--!Login / Sigin-->
      <div class="main-menu font-inconsolata font-size-20 flex-center">
        <ol class="flex-center">
          <li>
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
          </li>
          <li>
            <a href="#" id="kalk"><i class="fas fa-calculator"></i> Calculator<i class="new"> Old</i></a>
          </li>
          <li>
            <a href="#" id="kalk_new"><i class="fas fa-calculator"></i> Calculator<i class="new"> New</i></a>
          </li>
          <li>
            <a href="#" id="proj"><i class="fas fa-comments"></i> Forum</a>
          </li>
          <li>
            <a href="#" id="about_me"><i class="fas fa-info"></i> About</a>
          </li>
        </ol>
      </div>
      <div class="top-menu-button">
        <i class="fas fa-plus"></i>
      </div>
    </section>
    <!--!Top Menu-->
    
    <!--Body Content-->
    <section class="content flex-center">
      <section class="tiny-nav"><span>Home/</span></section>
    <?php include("./php/body.php"); ?>
    </section>
    <!--!Body Content-->
    <!-- Footer -->
    <?php include("./php/footer.php"); ?>
    <!-- !Footer -->
  </body>
</html>
