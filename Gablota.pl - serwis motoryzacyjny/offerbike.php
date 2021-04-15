<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ogłoszenie</title>
  <link href="assets/dist/css/lightbox.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>

<body>
  <header class="header">
    <nav class="header-wrap">
      <div class="header__nav">
        <a href="index.php" class="header__title link-reset ">Gablota.pl</a>
        <img class="header__burger" src="assets/icon/bars-solid.svg" alt="Burger icon">
        <div class="header__nav-menu">
                    <ul class="list-reset header__nav-list">
                        <li><a class="header__nav-link link-reset" href="addoffer.php">Dodaj ogłosznie</a></li>
                        <li><a class="header__nav-link link-reset" href="profile.php">Moje konto</a></li>
                        <?php 
                        if (!empty($_SESSION['user'])) echo '<li><a class="header__nav-link link-reset" href="singin.php">Wyloguj</a></li>';
                        else
                        {
                             echo '<li><a class="header__nav-link link-reset" href="singin.php">Zaloguj</a></li>';
                             session_destroy();
                        }
                        ?>
                    </ul>
                </div>
      </div>
      <div class="header__mobile">
        <ul class="list-reset header__mobile-list">
          <li><a class="header__mobile-link link-reset" href="addoffer.php">Dodaj ogłosznie</a></li>
          <li><a class="header__mobile-link link-reset" href="profile.php">Moje konto</a></li>
          <li><a class="header__mobile-link link-reset" href="singin.php">Zaloguj/Wyloguj</a></li>
        </ul>
      </div>
    </nav>
  </header>
  <main class="main">
    <section class="show">
      <?php
      try {
        $baza = new PDO("mysql:host=localhost;dbname=Gablota", "root");
        $zwrot = "";
      } catch (PDOException $e) {
        echo 'Błąd połączenia' . $e->getMessage();
      }

      $car = $baza->query("SELECT * FROM motocykle WHERE id = ".$_GET['id'])->fetch();
      $person = $baza->query("SELECT Imie, Telefon FROM uzytkownicy WHERE Id = ".$car["id_uzytkownika"])->fetch();
      echo '<h2 class="show__title">'.$car["Marka"].' '.$car["Model"].'</h2>';
      $folder = "assets/img/motocykle/".$_GET['id']."/";
      $zdjecia = glob("$folder*.{jpeg,jpg}", GLOB_BRACE);
      $ile = count($zdjecia);
      for($i = 0; $i<$ile; $i++)
      {
        if($i==0) echo '<a class="show__slider link-reset" href="'.$zdjecia[$i].'" data-lightbox="fota"><img class="stopro" src="'.$zdjecia[$i].'" alt=""></a>';
        else echo '<a class="link-reset display-none" href="'.$zdjecia[$i].'" data-lightbox="fota"></a>';
      }
      ?>
      <div class="show__row">
        <p>Marka:</p>
        <?php echo '<p>'.$car["Marka"].'</p>' ?>
      </div>
      <div class="show__row">
        <p>Model:</p>
        <?php echo '<p>'.$car["Model"].'</p>' ?>
      </div>
      <div class="show__row">
        <p>Rok produkcji:</p>
        <?php echo '<p>'.$car["Rok_produkcji"].'</p>' ?>
      </div>
      <div class="show__row">
        <p>Przebieg:</p>
        <?php echo '<p>'.$car["Przebieg"].'</p>' ?>
      </div>
      <div class="show__row">
        <p>Pojemnosc(ccm):</p>
        <?php echo '<p>'.$car["Pojemnosc"].'</p>' ?>
      </div>
      <div class="show__row">
        <p>Skrzynia:</p>
        <?php echo '<p>'.$car["Skrzynia"].'</p>' ?>
      </div>
      <div class="show__row">
        <p>Paliwo:</p>
        <?php echo '<p>'.$car["Paliwo"].'</p>' ?>
      </div>
      <div class="show__row">
        <p>Prwado jazdy:</p>
        <?php echo '<p>'.$car["Prawo_jazdy"].'</p>' ?>
      </div>
      <div class="show__row">
        <p>Moc:</p>
        <?php echo '<p>'.$car["Moc"].'</p>' ?>
      </div>
      <div class="show__row">
        <p>Ilość miejsc:</p>
        <?php echo '<p>'.$car["Miejsca"].'</p>' ?>
      </div>
      <div class="show__row">
        <p>Lokalizacja:</p>
        <?php echo '<p>'.$car["Lokalizacja"].'</p>' ?>
      </div>
      <div class="show__row">
        <p>Kraj pochodzenia:</p>
        <?php echo '<p>'.$car["Kraj"].'</p>' ?>
      </div>
      <div class="show__row">
        <p>Stan:</p>
        <?php echo '<p>'.$car["Stan"].'</p>' ?>
      </div>
      <div class="show__row">
        <p>Numer ogłoszenia:</p>
        <?php echo '<p>'.$car["id"].'</p>' ?>
      </div>
      <?php echo '<p class="show__price">'.$car["Cena"].' zł</p>' ?>
      <?php echo '<p class="show__desc">'.$car["Opis"].'</p>' ?>
      <div class="show__row">
        <p>Użytkownik:</p>
        <?php echo '<p>'.$person["Imie"].'</p>' ?>
      </div>
      <div class="show__row">
        <p>Telefon:</p>
        <?php echo '<p>'.$person["Telefon"].'</p>' ?>
      </div>
    </section>
  </main>
  <footer class="footer-wrap">
    <div class="footer">
      <div class="footer__social">
        <a href="index.php" class="footer__title link-reset">Gablota.pl</a>
        <div class="footer__icons">
          <a href="https://www.facebook.com/" target="_blank" class="link-reset footer__icons-icon"><img
              src="assets/icon/facebook-square-brands.svg" alt="Facebook link icon"></a>
          <a href="https://twitter.com/" target="_blank" class="link-reset footer__icons-icon"><img
              src="assets/icon/twitter-square-brands.svg" alt="Twitter link icon"></a>
          <a href="https://www.youtube.com/" target="_blank" class="link-reset footer__icons-icon"><img
              src="assets/icon/youtube-square-brands.svg" alt="Youtube link icon"></a>
        </div>
      </div>
      <div class="footer__nav">
        <ul class="list-reset footer__nav-ul">
          <li><a class="footer__nav-link link-reset" href="addoffer.php">Dodaj ogłosznie</a></li>
          <li><a class="footer__nav-link link-reset" href="profile.php">Moje konto</a></li>
          <li><a class="footer__nav-link link-reset" href="profile.php">Moje ogłosznia</a></li>
          <li><a class="footer__nav-link link-reset" href="singin.php">Zaloguj/Wyloguj</a></li>
        </ul>
      </div>
    </div>
    <a href="https://lokeshdhakar.com/projects/lightbox2/" class="lightbox link-reset">LokeshDhakar - Lightbox</a>
  </footer>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>  <script src="js/mobilemenu.js"></script>
  <script src="assets/dist/js/lightbox.js"></script>
  <script src="js/mobilemenu.js"></script>
</body>

</html>