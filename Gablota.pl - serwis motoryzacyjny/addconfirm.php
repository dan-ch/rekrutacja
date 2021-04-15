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
    <section class="singup">
    <h2 class="singin__logo">Gablota.pl</h2>
    <?php
    try {
      $baza = new PDO("mysql:host=localhost;dbname=Gablota", "root");
    } catch (PDOException $e) {
      echo 'Błąd połączenia' . $e->getMessage();
    }

    $sql1 = "INSERT INTO ".$_POST["typogl"]." (id_uzytkownika, Marka, Model, Rok_produkcji, Przebieg ";
    $sql2 = " VALUES ( ".$_SESSION["user"].", '".$_POST["marka"]."', '".$_POST['model']."', ".$_POST['prod'].", ".$_POST['przeb'];

   if(!empty($_POST["stan"]))
   {
    $sql1 .= ", Stan";
    $sql2 .= ", :stan";
   }
   if(!empty($_POST["skrzynia"]))
   {
    $sql1 .= ", Skrzynia";
    $sql2 .= ", :skrzynia";
   }
   if(!empty($_POST["moc"]))
   {
    $sql1 .= ", Moc";
    $sql2 .= ", :moc";
   }
   if(!empty($_POST["paliwo"]))
   {
    $sql1 .= ", Paliwo";
    $sql2 .= ", :paliwo";
   }
   if(!empty($_POST["kraj"]))
   {
    $sql1 .= ",Kraj";
    $sql2 .= ", :kraj";
   }
   if(!empty($_POST["lokalizacja"]))
   {
    $sql1 .= ", Lokalizacja";
    $sql2 .= ", :lokalizacja";
   }
   if(!empty($_POST["cena"]))
   {
    $sql1 .= ", Cena";
    $sql2 .= ", :cena";
   }
   if(!empty($_POST["opis"]))
   {
    $sql1 .= ", Opis";
    $sql2 .= ", :opis";
   }

   if($_POST['typogl'] == "osobowe")
   {
    if(!empty($_POST["nadwozie"]))
    {
     $sql1 .= ", Nadwozie";
     $sql2 .= ", :nadwozie";
    }

    if(!empty($_POST["miejsca"]))
    {
     $sql1 .= ", Miejsca";
     $sql2 .= ", :miejsca";
    }

    if(!empty($_POST["naped"]))
    {
     $sql1 .= ", Naped";
     $sql2 .= ", :naped";
    }
   }

   if($_POST['typogl'] == "motocykle")
   {
    if(!empty($_POST["typmoto"]))
    {
     $sql1 .= ", Typ";
     $sql2 .= ", :typ";
    }

    if(!empty($_POST["miejsca"]))
    {
     $sql1 .= ", Miejsca";
     $sql2 .= ", :miejsca";
    }

    if(!empty($_POST["prawko"]))
    {
     $sql1 .= ", Prawo_jazdy";
     $sql2 .= ", :prawko";
    }
   }

   if($_POST['typogl'] == "dostawcze")
   {
    if(!empty($_POST["typdost"]))
    {
     $sql1 .= ", Typ";
     $sql2 .= ", :typ";
    }

    if(!empty($_POST["ladownosc"]))
    {
     $sql1 .= ", Ladownosc";
     $sql2 .= ", :ladownosc";
    }

    if(!empty($_POST["naped"]))
    {
     $sql1 .= ", Naped";
     $sql2 .= ", :naped";
    }
   }

    $sql1 .= ")";
    $sql2 .= ")";
   $dodaj = $baza -> prepare($sql1.$sql2);

   if(!empty($_POST["stan"]))
   {
    $dodaj -> bindValue(':stan',$_POST['stan'] , PDO::PARAM_STR);
   }
   if(!empty($_POST["skrzynia"]))
   {
    $dodaj -> bindValue(':skrzynia',$_POST['skrzynia'] , PDO::PARAM_STR);
   }
   if(!empty($_POST["moc"]))
   {
    $dodaj -> bindValue(':moc',$_POST['moc'] , PDO::PARAM_INT);
   }
   if(!empty($_POST["paliwo"]))
   {
    $dodaj -> bindValue(':paliwo',$_POST['paliwo'] , PDO::PARAM_STR);
   }
   if(!empty($_POST["kraj"]))
   {
    $dodaj -> bindValue(':kraj',$_POST['kraj'] , PDO::PARAM_STR);
   }
   if(!empty($_POST["lokalizacja"]))
   {
    $dodaj -> bindValue(':lokalizacja',$_POST['lokalizacja'] , PDO::PARAM_STR);
   }
   if(!empty($_POST["cena"]))
   {
    $dodaj -> bindValue(':cena',$_POST['cena'] , PDO::PARAM_INT);
   }
   if(!empty($_POST["opis"]))
   {
    $dodaj -> bindValue(':opis',$_POST['opis'] , PDO::PARAM_STR);
   }

   if($_POST['typogl'] == "osobowe")
   {
    if(!empty($_POST["nadwozie"]))
    {
      $dodaj -> bindValue(':nadwozie',$_POST['nadwozie'] , PDO::PARAM_STR);
    }

    if(!empty($_POST["miejsca"]))
    {
      $dodaj -> bindValue(':miejsca',$_POST['miejsca'] , PDO::PARAM_INT);
    }

    if(!empty($_POST["naped"]))
    {
      $dodaj -> bindValue(':naped',$_POST['naped'] , PDO::PARAM_STR);
    }
   }

   if($_POST['typogl'] == "motocykle")
   {
    if(!empty($_POST["typmoto"]))
    {
      $dodaj -> bindValue(':typ',$_POST['typ'] , PDO::PARAM_STR);
    }

    if(!empty($_POST["miejsca"]))
    {
      $dodaj -> bindValue(':miejsca',$_POST['miejsca'] , PDO::PARAM_INT);
    }

    if(!empty($_POST["prawko"]))
    {
      $dodaj -> bindValue(':prawko',$_POST['prawko'] , PDO::PARAM_STR);
    }
   }

   if($_POST['typogl'] == "dostawcze")
   {
    if(!empty($_POST["typdost"]))
    {
      $dodaj -> bindValue(':typ',$_POST['typdost'] , PDO::PARAM_STR);
    }

    if(!empty($_POST["ladownosc"]))
    {
      $dodaj -> bindValue(':ladownosc',$_POST['ladownosc'] , PDO::PARAM_INT);
    }

    if(!empty($_POST["naped"]))
    {
      $dodaj -> bindValue(':naped',$_POST['naped'] , PDO::PARAM_STR);
    }
   }

   $id;
try{
   $baza -> beginTransaction();
   $cos = $dodaj -> execute();
   $id = $baza -> lastInsertId();
   $baza -> commit();
   if($cos) echo "Dodano ogłosznie";
   else echo "Wystąpił błąd podczas dodawania ogłoszenia";
}
 catch (Exception $e) {
  $baza->rollBack();
  echo "Błąd dodawania do bazy: " . $e->getMessage();
}

if ($_FILES['zdjglowne']['error'] > 0)
  {
    echo "Wystąpił błąd podczas wywyłania zdjęc";
  }
else 
{
  $flag = true;
$lokalizacja = "./assets/img/".$_POST['typogl']."/".$id."/";
if(is_uploaded_file($_FILES['zdjglowne']['tmp_name']))
{
  $ext = pathinfo($_FILES['zdjglowne']['name'], PATHINFO_EXTENSION);
  mkdir($lokalizacja, 0777, true);
  if(!move_uploaded_file($_FILES['zdjglowne']['tmp_name'], $lokalizacja."/0.".$ext)) $flag = false;;
  
    $ile  = count($_FILES['zdjdod']['name']);
    for($i=1; $i<=$ile; $i++)
    {
      $min = $i-1;
      if(is_uploaded_file($_FILES['zdjdod']['tmp_name'][$min]))
      {
        $ext = pathinfo($_FILES['zdjdod']['name'][$min], PATHINFO_EXTENSION);
        if(!move_uploaded_file($_FILES['zdjdod']['tmp_name'][$min], $lokalizacja."/".$i.".".$ext)) $flag = false;
      }
    }

}
else
{
  echo "Błąd ładowania dodatkowych zdjęć";
}
if($flag) echo "<br>Dodano zdjęcia";
else echo "Wystąpił błąd przy dodawaniu zdjęć";
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
  <script src="js/change.js"></script>
</body>

</html>