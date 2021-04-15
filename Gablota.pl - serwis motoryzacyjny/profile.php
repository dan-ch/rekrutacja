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
  <main class="main profile-flex">
    <section class="profile content-box">
      <form class="profile__form" method="POST">
        <h4 class="profile__title">Mój profil</h4>
        <img class="profile__img" src="assets/img/profilowe.png" alt="Profile image">
        <div class="profile__inputs">
        <?php
        try {
          $baza = new PDO("mysql:host=localhost;dbname=Gablota", "root");
          $id = $_SESSION["user"];
          $dane = $baza->query("SELECT * FROM uzytkownicy WHERE id='$id'")->fetch();
        } catch (PDOException $e) {
          echo 'Błąd połączenia' . $e->getMessage();
        }
        echo '<input class="input-line" name="imie" type="text" value='.$dane["Imie"].' reqired>';
        echo '<input class="input-line" name="nazwisko" type="text" value='.$dane["Nazwisko"].' reqired>';
        echo '<input class="input-line" name="miejscowosc" type="text" value='.$dane["Miejscowosc"].'>';
        echo '<input class="input-line" name="telefon" type="tel" value='.$dane["Telefon"].' reqired>';
        echo '<input class="input-line" name="email" type="email" value='.$dane["Email"].' reqired>';
        echo '<input class="input-line" name="haslo" type="password" placeholder="Hasło" reqired>';
        echo '<input class="input-line" name="haslo2" type="password" placeholder="Powtórz hasło" reqired>';
        ?>
        </div>
        <button class="button-confirm" type="submit" name="zmianadanych">Zapisz zamiany</button>
      </form>
      <?php
      if(isset($_POST['zmianadanych']))
      {
        $emaillist = $baza->query("SELECT email FROM uzytkownicy WHERE id != ".$_SESSION['user'])->fetchAll();

        $flag=true;
        foreach ($emaillist as $email) {
          if($email["email"] == $_POST["email"]) 
          {
            echo "Taki emali jest juz w bazie";
            $flag = false;
          }
        }

        if($_POST['haslo'] != $_POST['haslo2'])
        {
          $flag=false;
          echo "Podane hasła nie są identyczne";
        }

        if($flag)
        {
          $dodaj = $baza -> prepare("UPDATE uzytkownicy SET imie = :imie, nazwisko = :nazwisko, telefon = :telefon, miejscowosc = :miejscowosc, email = :email, haslo = :haslo) WHERE id = ".$_SESSION['user']);
          $dodaj -> bindValue(':imie',$_POST['imie'] , PDO::PARAM_STR);
          $dodaj -> bindValue(':nazwisko',$_POST['nazwisko'] , PDO::PARAM_STR);
          $dodaj -> bindValue(':telefon',$_POST['telefon'] , PDO::PARAM_INT);
          if(empty($_POST['miejscowosc'])) $dodaj -> bindValue(':miejscowosc',null, PDO::PARAM_NULL);
          else $dodaj -> bindValue(':miejscowosc',$_POST['miejscowosc'] , PDO::PARAM_STR);
          $dodaj -> bindValue(':email',$_POST['email'] , PDO::PARAM_STR);
          $dodaj -> bindValue(':haslo', hash('sha256', $_POST['haslo']), PDO::PARAM_STR);
          $dodaj->execute();
          echo "Udało się zmienić dane";
        }
      } 
      ?>
    </section>
    <section class="myoffer content-box">
      <h4 class="myoffer__title">Moje ogłoszenia</h4>
      <?php
      try {
        $osobowe = $baza->query("SELECT Marka, Model, Rok_produkcji, Przebieg, Cena, Id  FROM osobowe WHERE id_uzytkownika='$id'")->fetchAll();
        $dostawcze = $baza->query("SELECT Marka, Model, Rok_produkcji, Przebieg, Cena, Id  FROM dostawcze WHERE id_uzytkownika='$id'")->fetchAll();
        $motocykle = $baza->query("SELECT Marka, Model, Rok_produkcji, Przebieg, Cena, Id  FROM motocykle WHERE id_uzytkownika='$id'")->fetchAll();
      } catch (PDOException $e) {
        echo 'Błąd połączenia' . $e->getMessage();
      }

      foreach($osobowe as $pojazd)
      {
      echo '<form class="myoffer__offer" method="POST" action="#">';
      echo '<img src="assets/img/1/1.jpg" alt="Zdjecie ogłosznia">';
      echo '<div>';
      echo '<a href="offer.php?id='.$pojazd["Id"].'" class="link-reset">';
      echo '<h2 class="myoffer__offer-title">'.$pojazd["Marka"].' '.$pojazd["Model"].'</h2>';
      echo '</a>';
      echo '<p class="myoffer__offer-year">Rok: '.$pojazd["Rok_produkcji"].'</p>';
      echo '<p class="myoffer__offer-odo">Przebieg: '.$pojazd["Przebieg"].'</p>';
      echo '<h3 class="myoffer__offer-location">Id: '.$pojazd["Id"].'</h3>';
      echo '<input type="hidden" name="id_ogloszenia" value='.$pojazd["Id"].'>';
      echo '<p class="myoffer__offer-price">Cena: '.$pojazd["Cena"].'zł</p>';
      echo '</div>';
      echo '<button class="myoffer__delete" type="submit" name="usun_osob">Usuń</button>';
      echo '</form>';
      }

      foreach($dostawcze as $pojazd)
      {
      echo '<form class="myoffer__offer" method="POST" action="#">';
      echo '<img src="assets/img/1/1.jpg" alt="Zdjecie ogłosznia">';
      echo '<div>';
      echo '<a href="offertruck.php?id='.$pojazd["Id"].'" class="link-reset">';
      echo '<h2 class="myoffer__offer-title">'.$pojazd["Marka"].' '.$pojazd["Model"].'</h2>';
      echo '</a>';
      echo '<p class="myoffer__offer-year">Rok: '.$pojazd["Rok_produkcji"].'</p>';
      echo '<p class="myoffer__offer-odo">Przebieg: '.$pojazd["Przebieg"].'</p>';
      echo '<h3 class="myoffer__offer-location">Id: '.$pojazd["Id"].'</h3>';
      echo '<input type="hidden" name="id_ogloszenia" value='.$pojazd["Id"].'>';
      echo '<p class="myoffer__offer-price">Cena: '.$pojazd["Cena"].'zł</p>';
      echo '</div>';
      echo '<button class="myoffer__delete" type="submit" name="usun_dost">Usuń</button>';
      echo '</form>';
      }

      foreach($motocykle as $pojazd)
      {
      echo '<form class="myoffer__offer" method="POST" action="#">';
      echo '<img src="assets/img/1/1.jpg" alt="Zdjecie ogłosznia">';
      echo '<div>';
      echo '<a href="offerbike.php?id='.$pojazd["Id"].'" class="link-reset">';
      echo '<h2 class="myoffer__offer-title">'.$pojazd["Marka"].' '.$pojazd["Model"].'</h2>';
      echo '</a>';
      echo '<p class="myoffer__offer-year">Rok: '.$pojazd["Rok_produkcji"].'</p>';
      echo '<p class="myoffer__offer-odo">Przebieg: '.$pojazd["Przebieg"].'</p>';
      echo '<h3 class="myoffer__offer-location">Id: '.$pojazd["Id"].'</h3>';
      echo '<input type="hidden" name="id_ogloszenia" value='.$pojazd["Id"].'>';
      echo '<p class="myoffer__offer-price">Cena: '.$pojazd["Cena"].'zł</p>';
      echo '</div>';
      echo '<button class="myoffer__delete" type="submit" name="usun_moto">Usuń</button>';
      echo '</form>';
      }

      if(isset($_POST['usun_osob'])) {
        $usun = $baza -> prepare("DELETE FROM osobowe WHERE id=:id");
        $usun -> bindValue(':id', $_POST['id_ogloszenia'], PDO::PARAM_INT);
        $usun -> execute();
        header("Location: profile.php");
      }

      if(isset($_POST['usun_dost'])) {
        echo "cos";
        $usun = $baza -> prepare("DELETE FROM dostawcze WHERE id=:id");
        $usun -> bindValue(':id', $_POST['id_ogloszenia'], PDO::PARAM_INT);
        $usun -> execute();
        header("Location: profile.php");
      }

      if(isset($_POST['usun_moto'])) {
        $usun = $baza -> prepare("DELETE FROM motocykle WHERE id=:id");
        $usun -> bindValue(':id', $_POST['id_ogloszenia'], PDO::PARAM_INT);
        $usun -> execute();
        header("Location: profile.php");
      }
      ?>
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