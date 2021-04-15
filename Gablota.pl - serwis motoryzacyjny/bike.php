<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gablota.pl</title>
    <link rel="stylesheet" href="css/style.css">
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
        <section class="search">
            <form class="search-wrap" method="GET">
            <div class="search__checkbox">
                    <a type="radio" href="index.php" class="link-reset search__checkbox-item car "></a>
                    <a type="radio" href="bike.php" style="border-bottom: none" class="link-reset search__checkbox-item bike"></a>
                    <a type="radio"  href="truck.php" class="link-reset search__checkbox-item truck"></a>
                </div>
                <div class="search__form">
                    <label>Podastawowe</label>
                    <div class="input">
                        <select class="input-item" name="make" id="make" required>
                            <option value="" disabled selected>Marka</option>
                            <?php
                            try {
                                $baza = new PDO("mysql:host=localhost;dbname=Gablota", "root");
                                $zwrot = "";
                              } catch (PDOException $e) {
                                echo 'Błąd połączenia' . $e->getMessage();
                              }
                                $marki = $baza->query("SELECT DISTINCT Marka FROM motocykle")->fetchAll();
                                foreach ($marki as $marka) {
                                    echo "<option value=".$marka['Marka'].">".$marka['Marka']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <input class="input" type="text" name="model" placeholder="Model">
                    <label id="prod">Rok produkcji</label>
                    <input class="input" name="prodOd" type="number" placeholder="Od">
                    <input class="input" name="prodDo" type="number" placeholder="Do">
                    <label id="year">Przebieg</label>
                    <input class="input" name="przebOd" type="number" placeholder="Od">
                    <input class="input" name="przebDo" type="number" placeholder="Do">
                    <label id="price">Cena</label>
                    <input class="input" name="priceOd" type="number" placeholder="Od">
                    <input class="input" name="priceDo" type="number" placeholder="Do">
                </div>
                <div class="search__atomic">
                    <label>Szczegółowe</label>
                    <input class="input" type="text" name="lokalizacja" placeholder="Lokalizacja">
                    <div class="input">
                        <select class="input-item" name="power" id="power">
                            <option value="">Moc</option>
                            <option value="1">0 - 49</option>
                            <option value="2">50 - 100</option>
                            <option value="3">100 - 200</option>
                            <option value="4">200 - 300</option>
                            <option value="5">300 - 400</option>
                            <option value="6">400 +</option>
                        </select>
                    </div>
                    <div class="input">
                        <select class="input-item" name="type" id="type">
                            <option value="">Nadwozie</option>
                            <option value="Kombi">Kombi</option>
                            <option value="Coupe">Coupe</option>
                            <option value="Sedan">Sedan</option>
                            <option value="SUV">SUV</option>
                            <option value="Kabriolet">Kabiolet</option>
                            <option value="Miejski">Miejski</option>
                        </select>
                    </div>
                    <input class="input" name="pojemnosc" type="number" placeholder="Pojemność w ccm">
                    <div class="input">
                        <select class="input-item" name="state" id="state">
                            <option value="">Stan</option>
                            <option value="Nowy">Nowy</option>
                            <option value="Uzywany">Używany</option>
                            <option value="Uszkodzony">Uszkodzony</option>
                        </select>
                    </div>
                    <div class="input">
                        <select class="input-item" name="gearbox" id="gearbox">
                            <option value="">Skrzynia</option>
                            <option value="Manual">Manual</option>
                            <option value="Automat">Automat</option>
                        </select>
                    </div>
                    <div class="input">
                        <select class="input-item" name="drive" id="drive">
                            <option value="">Napęd</option>
                            <option value="AWD">AWD</option>
                            <option value="RWD">RWD</option>
                            <option value="FWD">FWD</option>
                        </select>
                    </div>
                    <div class="input">
                        <select class="input-item" name="prawko" id="fuel">
                            <option value="">Prawo jazdy</option>
                            <option value="B">kat. B</option>
                            <option value="A2">kat. A2</option>
                            <option value="A1">kat. A1</option>
                            <option value="A">kat. A</option>
                        </select>
                    </div>
                    <input class="input" type="text" name="kraj" placeholder="Kraj pochodzenia">
                    <input class="input" type="number" name="miejsca" min="0" max="9" step="1" id="books"
                        placeholder="Ilosc miejsc">
                </div>
                <div class="search__confirm">
                    <img src="assets/icon/arrow-circle-down-solid.svg" alt="Down arrow">
                    <button class="button-confirm" type="submit" name="szukaj">Szukaj</button>
                </div>
            </form>
            <?php
            if(isset($_GET['szukaj'])) {
                $mysql1 = "SELECT Id, Marka, Model, Rok_produkcji, Przebieg, Lokalizacja, Cena FROM motocykle WHERE ";
                $mysql2 = "null";

                if(!empty($_GET["make"])) $mysql2 = "Marka = '".$_GET["make"]."'";
    
                if(!empty($_GET["model"])) $mysql2 .= " AND Model = '".$_GET["model"]."'";

                if(!empty($_GET["prodOd"] && !empty($_GET["prodOd"]))) $mysql2 .= " AND Rok_produkcji >= ".$_GET["prodOd"]." AND "."Rok_produkcji <= ".$_GET["prodDo"];
                else if(!empty($_GET["prodOd"])) $mysql2 .= " AND Rok_produkcji >= ".$_GET["prodOd"];
                else if(!empty($_GET["prodDo"])) $mysql2 .= " AND Rok_produkcji <= ".$_GET["prodDo"];

                if(!empty($_GET["przebOd"] && !empty($_GET["przebDo"]))) $mysql2 .= " AND Przebieg >= ".$_GET["przebOd"]." AND "."Przebieg <= ".$_GET["przebDo"];
                else if(!empty($_GET["przebOd"])) $mysql2 .= " AND Przebieg > ".$_GET["przebOd"];
                else if(!empty($_GET["przebDo"])) $mysql2 .= " AND Przebieg < ".$_GET["przebDo"];

                if(!empty($_GET["priceOd"] && !empty($_GET["priceDo"]))) $mysql2 .= " AND Cena >= ".$_GET["priceOd"]." AND "."Cena <= ".$_GET["priceDo"];
                else if(!empty($_GET["priceOd"])) $mysql2 .= " AND Cena > ".$_GET["priceOd"];
                else if(!empty($_GET["priceDo"])) $mysql2 .= " AND Cena < ".$_GET["priceDo"];

                if(!empty($_GET["power"]))
                {
                    if($_GET["power"] == 1) $mysql2 .= " AND Moc < 50 ";
                    else if($_GET["power"] == 2) $mysql2 .= " AND Moc >= 50 AND Moc < 100";
                    else if($_GET["power"] == 3) $mysql2 .= " AND Moc >= 100 AND Moc < 200";
                    else if($_GET["power"] == 4) $mysql2 .= " AND Moc >= 200 AND Moc < 300";
                    else if($_GET["power"] == 5) $mysql2 .= " AND Moc >= 300 AND Moc < 400";
                    else $mysql2 .= " AND Moc >= 400 ";
                }

                if(!empty($_GET["lokalizacja"])) $mysql2 .= " AND Lokalizacja = '".$_GET["lokalizacja"]."'";
                if(!empty($_GET["type"])) $mysql2 .= " AND Nadwozie = '".$_GET["type"]."'";
                if(!empty($_GET["pojemnosc"])) $mysql2 .= " AND Pojemnosc = '".$_GET["pojemnosc"]."'";
                if(!empty($_GET["state"])) $mysql2 .= " AND Stan = '".$_GET["state"]."'";
                if(!empty($_GET["gearbox"])) $mysql2 .= " AND Skrzynia = '".$_GET["gearbox"]."'";
                if(!empty($_GET["drive"])) $mysql2 .= " AND Naped = '".$_GET["drive"]."'";
                if(!empty($_GET["prawko"])) $mysql2 .= " AND Prawo_jazdy = '".$_GET["prawko"]."'";
                if(!empty($_GET["kraj"])) $mysql2 .= " AND Kraj = '".$_GET["kraj"]."'";
                if(!empty($_GET["miejsca"])) $mysql2 .= " AND Miejsca = '".$_GET["miejsca"]."'";

                echo $mysql1.$mysql2;
                $zwrot = $baza->query($mysql1.$mysql2)->fetchAll();
              }
            ?>
        </section>
        <section class="offer">
            <?php
                if(!empty($zwrot))
                {
                    foreach($zwrot as $ogl)
                    {
                        echo '<article class="offer__item">';
                        echo '<img src="assets/img/motocykle/'.$ogl["Id"].'/0.jpg" alt="">';
                        echo '<div>';
                        echo '<a href="offerbike.php?id='.$ogl["Id"].'" class="link-reset">';
                        echo '<h2 class="offer__item-title">'.$ogl['Marka'].' '.$ogl['Model'].'</h2>';
                        echo '</a>';
                        echo '<p class="offer__item-year">Rok: '.$ogl['Rok_produkcji'].'</p>';
                        echo '<p class="offer__item-odo">Przebieg: '.$ogl['Przebieg'].' km</p>';
                        echo '<h3 class="offer__item-location">'.$ogl['Lokalizacja'].'</h3>';
                        echo '<p class="offer__item-price">Cena: '.$ogl['Cena'].' zł</p>';
                        echo '</div>';
                        echo '</article>';
                    }
                }
                else if(!isset($_GET['szukaj'])) 
                {
                    $ile = $baza->query("SELECT count(*) FROM motocykle")->fetchColumn();
                    if($ile > 4) $ile = 4;
                    $ids = $baza->query("SELECT Id FROM motocykle")->fetchAll();
                    $losowe = $baza->query("SELECT Id, Marka, Model, Rok_produkcji, Przebieg, Lokalizacja, Cena FROM motocykle")->fetchAll();
                    for($i=0; $i<$ile; $i++)
                    {
                      if(count($ids) > 4) $numer = rand(0, count($ids)-1);
                        else $numer = $i;
                        $losowe = $baza->query("SELECT Id, Marka, Model, Rok_produkcji, Przebieg, Lokalizacja, Cena FROM motocykle WHERE Id = ".$ids[$numer]["Id"])->fetch();

                        echo '<article class="offer__item">';
                        echo '<img src="assets/img/motocykle/'.$losowe["Id"].'/0.jpg" alt="">';
                        echo '<div>';
                        echo '<a href="offerbike.php?id='.$losowe["Id"].'" class="link-reset">';
                        echo '<h2 class="offer__item-title">'.$losowe['Marka'].' '.$losowe['Model'].'</h2>';
                        echo '</a>';
                        echo '<p class="offer__item-year">Rok: '.$losowe['Rok_produkcji'].'</p>';
                        echo '<p class="offer__item-odo">Przebieg: '.$losowe['Przebieg'].' km</p>';
                        echo '<h3 class="offer__item-location">'.$losowe['Lokalizacja'].'</h3>';
                        echo '<p class="offer__item-price">Cena: '.$losowe['Cena'].' zł</p>';
                        echo '</div>';
                        echo '</article>';
                    }
                }

                else echo "<p>Brak wyników</p>";
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

    <script src="js/mobieatomic.js"></script>
    <script src="js/mobilemenu.js"></script>
</body>

</html>