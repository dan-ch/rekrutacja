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
  if (!empty($_SESSION['user']))
  {
    session_destroy();
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'index.php';
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
    <section class="singin">
      <h2 class="singin__logo">Gablota.pl</h2>
      <form action="singinconf.php" method="POST">
        <input class="input-line" type="email" placeholder="Email" name="email" required>
        <input class="input-line" type="password" placeholder="Hasło" name="haslo" required>
        <button class="button-confirm" type="submit">Zaloguj</button>
      </form>
      <a href="singup.php" class="singin__singup link-reset">Nie mam konta - rejestracja</a>
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
</body>

</html>