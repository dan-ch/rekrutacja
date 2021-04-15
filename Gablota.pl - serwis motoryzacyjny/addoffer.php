<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gablota.pl - profil</title>
  <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>

<body>
<?php 
if (empty($_SESSION['user']))
  {
    session_destroy();
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'singin.php';
    header("Location: http://$host$uri/$extra");
  }
  ?>
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
    <section>
      <form class="addoffer" action="addconfirm.php" method="POST" enctype="multipart/form-data">
      <h2 class="addoffer__title">Dodawanie ogłosznia</h2>
      <div class="addoffer__form">
      <div class="input">
          <select class="input-item listatypow" name="typogl" required>
            <option value="osobowe">Osobowy</option>
            <option value="motocykle">Motocykl</option>
            <option value="dostawcze">Dostawczy</option>
          </select>
        </div>
        <label for="mainPhoto" class='input'>Zdjęcie główne</label>
        <input type="file" class="input-file" accept="image/jpeg" name="zdjglowne" id="mainPhoto">
        <label for="dodatkowe" class='input'>Zdjęcia dodatkowe</label>
        <input type="file" class="input-file" accept="image/png, image/jpeg" name="zdjdod[]" id="dodatkowe" multiple>
        <input class="input" type="text" placeholder="Marka" required name="marka">
        <input class="input" type="text" placeholder="Model" required name="model">
        <input class="input" name="prod" type="number" placeholder="Rok produkcji" required>
        <input class="input" name="przeb" type="number" placeholder="Przebieg w tys." required>
        <div class="input" id="nadwozie">
          <select class="input-item" name="nadwozie">
            <option value="" disabled selected>Nadwozie</option>
            <option value="Kombi">Kombi</option>
            <option value="Coupe">Coupe</option>
            <option value="Sedan">Sedan</option>
            <option value="SUV">SUV</option>
            <option value="Kabiolet">Kabiolet</option>
            <option value="Miesjki">Miejski</option>
          </select>
        </div>
        <div class="input" id="typmoto" style="display: none" >
          <select class="input-item" name="typmotor">
            <option value="" disabled selected>Typ motoru</option>
            <option value="Skuter">Skuter</option>
            <option value="Miejski">Miejski</option>
            <option value="Cross">Cross</option>
            <option value="Scigacz">Scigacz</option>
          </select>
        </div>
        <div class="input" id="typdost" style="display: none">
          <select class="input-item" name="typdost">
            <option value="" disabled selected>Typ dostawczego</option>
            <option value="Tir">Tir</option>
            <option value="Bus">Bus</option>
          </select>
        </div>
        <input class="input" name="pojemnosc" type="number" placeholder="Pojemność w ccm" required>
        <div class="input">
          <select class="input-item" name="stan" id="state">
            <option value="">Stan</option>
            <option value="Nowy">Nowy</option>
            <option value="Uzywany">Używany</option>
            <option value="Uszkodzony">Uszkodzony</option>
          </select>
        </div>
        <div class="input">
          <select class="input-item" name="skrzynia" id="gearbox" required>
            <option value="" disable selected>Skrzynia</option>
            <option value="Manual">Manual</option>
            <option value="Automat">Automat</option>
          </select>
        </div>
        <input class="input" type="number" placeholder="Moc" name="moc" required>
        <div class="input" id="naped">
          <select class="input-item" name="naped" id="drive">
            <option value="">Napęd</option>
            <option value="AWD">AWD</option>
            <option value="RWD">RWD</option>
            <option value="FWD">FWD</option>
          </select>
        </div>
        <div class="input">
          <select class="input-item" name="paliwo" required>
            <option value="" disable selected>Paliwo</option>
            <option value="Benzyna">Benzyna</option>
            <option value="Diesel">Diesel</option>
            <option value="Benzyna + LPG">Benzyna + LPG</option>
            <option value="Elektryczny">Elektryczny</option>
          </select>
        </div>
        <div class="input" id="prawko" style="display: none">
          <select class="input-item" name="prawko">
            <option value="">Prawo jazdy</option>
            <option value="B">kat. B</option>
            <option value="AM">kat. AM</option>
            <option value="A1">kat. A1</option>
            <option value="A2">kat. A2</option>
            <option value="A">kat. A</option>
          </select>
        </div>
        <input class="input" type="text" placeholder="Kraj pochodzenia" name="kraj">
        <input class="input" type="text" placeholder="Lokalizacja" required name="lokalizacja">
        <input class="input" type="number" name="books" min="0" max="999999" step="1" id="ladownosc"
          placeholder="Ładowność" style="display: none">
          <input class="input" type="number" name="miejsca" min="0" max="9" step="1" id="miejsca"
          placeholder="Ilość miejsc">
        <input class="input addoffer__form-price" type="number" placeholder="Cena" required name="cena">
        <textarea class="input" name="opis" id="" cols="30"
          rows="10" style="resize: none" placeholder="Podaj opis"></textarea>
        <button type="submit" class="input">Dodaj ogłosznie</button>
      </div>
      </form>
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
  <script src="js/mobilemenu.js"></script>
  <script src="js/change.js"></script>
</body>

</html>