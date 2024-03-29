<?php
session_start();
require 'secret_mdp.php';


function connexionBD()
{
    $mabd = null;
    try {
        $mabd = new PDO('mysql:host=localhost;port=3306;dbname=imovix;charset=UTF8;', LUTILISATEUR, LEMOTDEPASSE);
        $mabd->query('SET NAMES utf8;');
    } catch (PDOException $e) {
        print 'Erreur : ' . $e->getMessage() . '<br />';
        die();
    }
    return $mabd;
}
function deconnexionBD(&$mabd)
{
    return $mabd = null;
}

function inscription($co, $prenom, $nom, $email, $mdp)
{
    $req = 'INSERT INTO imovix_users (user_code, user_photo, user_prenom, user_nom, user_email, user_mdp, user_list1, user_list2, user_list3, user_list4, user_list5) 
    VALUES (NULL, "default.svg","' . $prenom . '","' . $nom . '","' . $email . '","' . $mdp . '", "0", "0", "0", "0", "0")';
    echo $req;
    try {
        $resultat = $co->query($req);
    } catch (PDOException $e) {
        echo '<p>Erreur : ' . $e->getMessage() . '</p>';
        die();
    }
    if ($resultat->rowCount() == 1) {
        $_SESSION['prenom_user'] = $prenom;
        $_SESSION['photo_user'] = 'default.svg';
        header('location:bienvenue.php');
    } else {
        echo "<h1>Une erreur est survenue</h1>";
        header('location:inscription.php');
        die();
    }
}

function afficherContainerPP($co)
{
    $req = "SELECT * FROM bdd_pp";

    $resultat = $co->query($req);
    foreach ($resultat as $value) {
        echo  '<a href="profil_modif.php?user_photo=' . $value['user_photo'] . '" class="img_pp" type="button" onclick="" >';
        echo '<img src="./img/pp/' . $value['user_photo'] . '" />;';
        echo '<div class="hoverPP">';
        echo '<svg clip-rule="evenodd" fill="white" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m2.164 10.201c.055-.298.393-.734.934-.59.377.102.612.476.543.86-.077.529-.141.853-.141 1.529 0 4.47 3.601 8.495 8.502 8.495 2.173 0 4.241-.84 5.792-2.284l-1.251-.341c-.399-.107-.636-.519-.53-.919.108-.4.52-.637.919-.53l3.225.864c.399.108.637.519.53.919l-.875 3.241c-.107.399-.519.636-.919.53-.399-.107-.638-.518-.53-.918l.477-1.77c-1.829 1.711-4.27 2.708-6.838 2.708-5.849 0-9.968-4.8-10.002-9.93-.003-.473.027-1.119.164-1.864zm5.396 2.857 2.924 2.503c.142.128.321.19.499.19.202 0 .405-.081.552-.242l4.953-5.509c.131-.143.196-.323.196-.502 0-.41-.331-.747-.748-.747-.204 0-.405.082-.554.243l-4.453 4.962-2.371-2.011c-.144-.127-.321-.19-.499-.19-.415 0-.748.335-.748.746 0 .205.084.409.249.557zm14.276.743c-.054.298-.392.734-.933.59-.378-.102-.614-.476-.543-.86.068-.48.139-.848.139-1.53 0-4.479-3.609-8.495-8.5-8.495-2.173 0-4.241.841-5.794 2.285l1.251.341c.4.107.638.518.531.918-.108.4-.519.637-.919.53l-3.225-.864c-.4-.107-.637-.518-.53-.918l.875-3.241c.107-.4.518-.638.918-.531.4.108.638.518.531.919l-.478 1.769c1.83-1.711 4.272-2.708 6.839-2.708 5.865 0 10.002 4.83 10.002 9.995 0 .724-.081 1.356-.164 1.8z" fill-rule="nonzero"/></svg>';
        echo '</div>';
        echo  ' </a>';
    }
}
function afficherModifProfil($co)

{
    $userPhoto = $_GET['user_photo'];
    $_SESSION['photo'] = $userPhoto;

    // echo $_SESSION['photo'];
    // echo $_SESSION['user_photo'];

    $req = 'SELECT * FROM imovix_users WHERE user_code=  ' . $_SESSION["user_numero"] . ' ';
    $resultat = $co->query($req);
    foreach ($resultat as $values) {

        if (!empty($_SESSION['photo'])) {

            echo '<form class="changement_profil" action="profil_modif_verif.php" method="post">';
            echo     '<button type="button" class="changement_profil_pp" onclick="afficherPP()">';
            echo      ' <img class="pp_img" src="./img/pp/' . $userPhoto . '" alt="">';

            echo     '</button>';

            echo     '<input type="text" name="prenom" value="' . $values['user_prenom'] . '">';
            echo     '<input type="text" name="nom" value="' . $values['user_nom'] . '">';
            echo     '<input type="email" name="email" value="' . $values['user_email'] . '">';
            echo     '<input class="enregistrer" type="submit" value="Enregistrer">';
            echo '</form>';
        } else {

            echo '<form class="changement_profil" action="profil_modif_verif.php" method="post">';
            echo     '<button type="button" class="changement_profil_pp" onclick="afficherPP()">';
            echo      ' <img class="pp_img" src="./img/pp/' . $values['user_photo'] . '" alt="">';
            echo     '</button>';

            echo     '<input type="text" name="prenom" value="' . $values['user_prenom'] . '">';
            echo     '<input type="text" name="nom" value="' . $values['user_nom'] . '">';
            echo     '<input type="email" name="email" value="' . $values['user_email'] . '">';
            echo     '<input class="enregistrer" type="submit" value="Enregistrer">';
            echo '</form>';
        }
    }
}


function modifierProfil($co, $photo, $prenom, $nom, $email, $id)
{
    $req = "UPDATE imovix_users SET user_photo='$photo', user_prenom='$prenom', user_nom='$nom', user_email='$email' WHERE user_code='$id' ";
    try {
        $resultat = $co->query($req);
    } catch (PDOException $e) {
        print 'Erreur : ' . $e->getMessage() . '<br />';
    }

    if ($resultat->rowCount() == 1) {
        echo '<h1>Votre profil à bien été modifié</h1>';
        header('Refresh:0.8 ; URL=profil.php');
    } else {
        echo '<h1>Une erreur est survenu lors de la modification</h1>';
    }
}

function afficherProfil($co)
{
    $id = $_SESSION["user_numero"];

    if (!empty($_SESSION['user_prenom'])) {
        $req = "SELECT * FROM imovix_users WHERE user_code=$id";
        // echo $req;
        $resultat = $co->query($req);
        foreach ($resultat as $value) {
            echo    '<h1>' . $value['user_prenom'] . '</h1>';
            echo    '<div class="pp">';
            echo       '<img class="pp_img" src="./img/pp/' . $value['user_photo'] . '" alt="">';
            echo  ' </div>';
            echo   '<h2 class="name">' . $value['user_nom'] . '</h2>';
            echo   '<h3>' . $value['user_email'] . '</h3>';
            echo   '<a href="profil_modif.php" class="profil_container modif">';
            echo       '<h4>Modifier mon profil</h4>';
            echo   '</a>';
        }
    } else {
        echo "<h1 class='h1_erreur_profil'>Vous devez vous connectez pour acceder a votre profil</h1>";
        echo "<div class='log'>";
        echo "<a class='btnConnexion' href='connexion.php'>Se connecter</a>";
        echo "<a class='btnInscription' href='inscription.php'>S'inscrire</a>";
        echo "</div>";
    }
}


function afficherProfilNav($co)
{


    if (!empty($_SESSION['user_numero'])) {

        $req = 'SELECT * FROM imovix_users WHERE user_code=  ' . $_SESSION["user_numero"] . ' ';
        $resultat = $co->query($req);
        foreach ($resultat as $values) {
            echo '<ul class="log">';
            echo '<div class="container_link">';
            echo '<li class="link first_link"><a href="profil.php">' . $values['user_prenom'] . '</a></li>';
            echo '<li class="link second_link"><a href="profil.php">' . $values['user_prenom'] . '</a></li>';
            echo  '</div>';
            echo  '<div class="container_link">';
            echo '<li class="link first_link"><a href="deconnexion.php">Deconnexion</a></li>';
            echo '<li class="link second_link_log"><a href="deconnexion.php">Deconnexion</a></li>';
            echo '</div>';
        }
    } else {
        echo '<ul class="log">';
        echo '<div class="container_link">';
        echo '<li class="link first_link"><a href="connexion.php">Connexion</a></li>';
        echo '<li class="link second_link"><a href="connexion.php">Connexion</a></li>';
        echo  '</div>';
        echo  '<div class="container_link">';
        echo '<li class="link first_link"><a href="inscription.php">Inscription</a></li>';
        echo '<li class="link second_link_log"><a href="inscription.php">Inscription</a></li>';
        echo '</div>';
    }



    if (!empty($_SESSION['user_numero'])) {
        $req = 'SELECT * FROM imovix_users WHERE user_code=  ' . $_SESSION["user_numero"] . ' ';
        $resultat = $co->query($req);
        foreach ($resultat as $values) {
            echo '<a href="profil.php" class="photo_profil">';
            echo '<img class="photo_profil_img" src="./img/pp/' . $values['user_photo'] . '" alt="">';
            echo '</a>';
            echo '</ul>';
        }
    } else {
        echo '<a href="profil.php" class="photo_profil">';
        echo '<img class="photo_profil_img" src="./img/pp/default.svg" alt="">';
        echo '</a>';
        echo '</ul>';
    }
}




function couvertureMovie($co)
{
    $req = 'SELECT * FROM imovix_movies ORDER BY RAND() LIMIT 1;';
    $resultatCouverture = $co->query($req);
    foreach ($resultatCouverture as $valueCouverture) {

        echo '<iframe class="interstellar_ba" width="560" height="315" src="https://www.youtube.com/embed/' . $valueCouverture['movie_ba'] . '?rel=0&autoplay=1&mute=1&loop=1" title="YouTube video player" frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

        echo '<img class="illu_conv" src="./img/illustration/' . $valueCouverture['movie_illu'] . '">';
        echo '<div class="couverture_info">';
        echo '<div class="description">';
        echo ' <h1>' . $valueCouverture['movie_titre'] . '</h1>';
        echo ' <h2>' . $valueCouverture['movie_titre_2'] . '</h2>';
        echo '<div class="interaction">';
        echo ' <a class="play" href="">PLAY</a>';
        echo ' <a href="teaser.php">TEASER</a>';
        echo '<a href="list_ajout.php?id=' . $valueCouverture['id'] . '">+ ADD LIST</a>';
        echo '</div>';
        echo '<h3>Genre : ' . $valueCouverture['movie_genre'] . '</h3>';
        echo '<p>' . $valueCouverture['movie_descr'] . '</p>';
        echo '</div>';
        echo ' <div class="container_timeline_sound">';
        echo ' <div class="timeline"></div>';
        echo '<a class="muteBtn">';
        echo '<img class="noMuted" src="./img/picto/noMuted.svg" alt="">';
        echo '<img class="muted" src="./img/picto/Muted.svg" alt="">';
        echo '</a>';
        echo '</div>';
        echo ' </div>';
    }
}









function afficherMoviesTendance($co)
{
    $req = 'SELECT * FROM imovix_movies WHERE movie_tendance=1';

    $resultat = $co->query($req);
    foreach ($resultat as $value) {

        echo '<div class="carrousel_genre_container">';

        echo '<div class="carrousel_affiche">';
        echo '<img class="carrousel_affiche_img" src="./img/affiche/' . $value['movie_affiche'] . '" alt="">';

        echo '<span class="carrousel_affiche_hover">';

        echo '<a class="play" href=""><img class="arrow" src="./img/picto/playArrow.svg" alt=""></a>';

        echo '<div class="interaction">';
        echo '<a class="more" href="list_ajout.php?id=' . $value['id'] . '"> <img class="plus" src="./img/picto/+.svg" alt=""> <img class="plus_black" src="./img/picto/+_black.svg" alt=""></a>';
        echo '</div>';

        echo '</span>';

        echo '</div>';




        echo '<div class="hoverInfo">';

        echo '<div class="hoverInfo_descr">';
        echo '<h1>' . $value['movie_titre'] . ' : ' . $value['movie_titre_2'] . '</h1>';
        echo '<h2><span>Genre : </span>' . $value['movie_genre'] . '</h2>';
        echo '<h2><span>Distribution : </span>' . $value['movie_distrib'] . '</h2>';
        echo '<p>' . $value['movie_descr'] . '</p>';
        echo '</div>';

        echo '<div class="hoverInfo_video">';
        echo '<a class="muteBtn">';
        echo '<img class="noMuted" src="./img/picto/noMuted.svg" alt="">';
        echo '<img class="muted" src="./img/picto/Muted.svg" alt="">';
        echo '</a>';
        // echo '<video class="avengersEndgame_ba" autoplay loop muted>';
        // echo '<source src="./video/' . $value['movie_ba'] . '" type="video/mp4">';
        // echo '</video>';
        echo '<iframe class="iframe_ba" width="560" height="315" src="https://www.youtube.com/embed/' . $value['movie_ba'] . '?rel=0&autoplay=1&mute=1&loop=1" title="YouTube video player" frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        echo '</div>';

        echo '</div>';
        echo '</div>';
    }
}
function afficherMoviesAction($co)
{
    $req = 'SELECT * FROM imovix_movies WHERE movie_action=1 ORDER BY RAND();';

    $resultat = $co->query($req);
    foreach ($resultat as $value) {

        echo '<div class="carrousel_genre_container">';

        echo '<div class="carrousel_affiche">';
        echo '<img class="carrousel_affiche_img" src="./img/affiche/' . $value['movie_affiche'] . '" alt="">';

        echo '<span class="carrousel_affiche_hover">';

        echo '<a class="play" href=""><img class="arrow" src="./img/picto/playArrow.svg" alt=""></a>';

        echo '<div class="interaction">';
        echo '<a class="more" href="list_ajout.php?id=' . $value['id'] . '"> <img class="plus" src="./img/picto/+.svg" alt=""> <img class="plus_black" src="./img/picto/+_black.svg" alt=""></a>';
        echo '</div>';

        echo '</span>';

        echo '</div>';




        echo '<div class="hoverInfo">';

        echo '<div class="hoverInfo_descr">';
        echo '<h1>' . $value['movie_titre'] . ' : ' . $value['movie_titre_2'] . '</h1>';
        echo '<h2><span>Genre : </span>' . $value['movie_genre'] . '</h2>';
        echo '<h2><span>Distribution : </span>' . $value['movie_distrib'] . '</h2>';
        echo '<p>' . $value['movie_descr'] . '</p>';
        echo '</div>';

        echo '<div class="hoverInfo_video">';
        echo '<a class="muteBtn">';
        echo '<img class="noMuted" src="./img/picto/noMuted.svg" alt="">';
        echo '<img class="muted" src="./img/picto/Muted.svg" alt="">';
        echo '</a>';
        echo '<video class="avengersEndgame_ba" autoplay loop muted>';
        echo '<source src="./video/' . $value['movie_ba'] . '" type="video/mp4">';
        echo '</video>';
        echo '</div>';

        echo '</div>';
        echo '</div>';
    }
}



function afficherMoviesAventure($co)
{
    $req = 'SELECT * FROM imovix_movies WHERE movie_aventure=1 ORDER BY RAND();';

    $resultat = $co->query($req);
    foreach ($resultat as $value) {

        echo '<div class="carrousel_genre_container">';

        echo '<div class="carrousel_affiche">';
        echo '<img class="carrousel_affiche_img" src="./img/affiche/' . $value['movie_affiche'] . '" alt="">';

        echo '<span class="carrousel_affiche_hover">';

        echo '<a class="play" href=""><img class="arrow" src="./img/picto/playArrow.svg" alt=""></a>';

        echo '<div class="interaction">';
        echo '<a class="more" href="list_ajout.php?id=' . $value['id'] . '"> <img class="plus" src="./img/picto/+.svg" alt=""> <img class="plus_black" src="./img/picto/+_black.svg" alt=""></a>';
        echo '</div>';

        echo '</span>';

        echo '</div>';




        echo '<div class="hoverInfo">';

        echo '<div class="hoverInfo_descr">';
        echo '<h1>' . $value['movie_titre'] . ' : ' . $value['movie_titre_2'] . '</h1>';
        echo '<h2><span>Genre : </span>' . $value['movie_genre'] . '</h2>';
        echo '<h2><span>Distribution : </span>' . $value['movie_distrib'] . '</h2>';
        echo '<p>' . $value['movie_descr'] . '</p>';
        echo '</div>';

        echo '<div class="hoverInfo_video">';
        echo '<a class="muteBtn">';
        echo '<img class="noMuted" src="./img/picto/noMuted.svg" alt="">';
        echo '<img class="muted" src="./img/picto/Muted.svg" alt="">';
        echo '</a>';
        echo '<video class="avengersEndgame_ba" autoplay loop muted>';
        echo '<source src="./video/' . $value['movie_ba'] . '" type="video/mp4">';
        echo '</video>';
        echo '</div>';

        echo '</div>';
        echo '</div>';
    }
}

function afficherMoviesThriller($co)
{
    $req = 'SELECT * FROM imovix_movies WHERE movie_thriller=1 ORDER BY RAND();';

    $resultat = $co->query($req);
    foreach ($resultat as $value) {

        echo '<div class="carrousel_genre_container">';

        echo '<div class="carrousel_affiche">';
        echo '<img class="carrousel_affiche_img" src="./img/affiche/' . $value['movie_affiche'] . '" alt="">';

        echo '<span class="carrousel_affiche_hover">';

        echo '<a class="play" href=""><img class="arrow" src="./img/picto/playArrow.svg" alt=""></a>';

        echo '<div class="interaction">';
        echo '<a class="more" href="list_ajout.php?id=' . $value['id'] . '"> <img class="plus" src="./img/picto/+.svg" alt=""> <img class="plus_black" src="./img/picto/+_black.svg" alt=""></a>';
        echo '</div>';

        echo '</span>';

        echo '</div>';




        echo '<div class="hoverInfo">';

        echo '<div class="hoverInfo_descr">';
        echo '<h1>' . $value['movie_titre'] . ' : ' . $value['movie_titre_2'] . '</h1>';
        echo '<h2><span>Genre : </span>' . $value['movie_genre'] . '</h2>';
        echo '<h2><span>Distribution : </span>' . $value['movie_distrib'] . '</h2>';
        echo '<p>' . $value['movie_descr'] . '</p>';
        echo '</div>';

        echo '<div class="hoverInfo_video">';
        echo '<a class="muteBtn">';
        echo '<img class="noMuted" src="./img/picto/noMuted.svg" alt="">';
        echo '<img class="muted" src="./img/picto/Muted.svg" alt="">';
        echo '</a>';
        echo '<video class="avengersEndgame_ba" autoplay loop muted>';
        echo '<source src="./video/' . $value['movie_ba'] . '" type="video/mp4">';
        echo '</video>';
        echo '</div>';

        echo '</div>';
        echo '</div>';
    }
}

function afficherMoviesRomance($co)
{
    $req = 'SELECT * FROM imovix_movies WHERE movie_romance=1 ORDER BY RAND();';

    $resultat = $co->query($req);
    foreach ($resultat as $value) {

        echo '<div class="carrousel_genre_container">';

        echo '<div class="carrousel_affiche">';
        echo '<img class="carrousel_affiche_img" src="./img/affiche/' . $value['movie_affiche'] . '" alt="">';

        echo '<span class="carrousel_affiche_hover">';

        echo '<a class="play" href=""><img class="arrow" src="./img/picto/playArrow.svg" alt=""></a>';

        echo '<div class="interaction">';
        echo '<a class="more" href="list_ajout.php?id=' . $value['id'] . '"> <img class="plus" src="./img/picto/+.svg" alt=""> <img class="plus_black" src="./img/picto/+_black.svg" alt=""></a>';
        echo '</div>';

        echo '</span>';

        echo '</div>';




        echo '<div class="hoverInfo">';

        echo '<div class="hoverInfo_descr">';
        echo '<h1>' . $value['movie_titre'] . ' : ' . $value['movie_titre_2'] . '</h1>';
        echo '<h2><span>Genre : </span>' . $value['movie_genre'] . '</h2>';
        echo '<h2><span>Distribution : </span>' . $value['movie_distrib'] . '</h2>';
        echo '<p>' . $value['movie_descr'] . '</p>';
        echo '</div>';

        echo '<div class="hoverInfo_video">';
        echo '<a class="muteBtn">';
        echo '<img class="noMuted" src="./img/picto/noMuted.svg" alt="">';
        echo '<img class="muted" src="./img/picto/Muted.svg" alt="">';
        echo '</a>';
        echo '<video class="avengersEndgame_ba" autoplay loop muted>';
        echo '<source src="./video/' . $value['movie_ba'] . '" type="video/mp4">';
        echo '</video>';
        echo '</div>';

        echo '</div>';
        echo '</div>';
    }
}



function ajoutMovieList($co)
{
    $id = $_GET['id'];
    $code = $_SESSION['user_numero'];
    $req = 'SELECT * FROM imovix_users WHERE user_code= ' . $_SESSION["user_numero"] . ' ';
    $resultat_user_list = $co->query($req);
    foreach ($resultat_user_list as $values) {
        if ($id == $values['user_list1'] || $id == $values['user_list2'] || $id == $values['user_list3'] || $id == $values['user_list4'] || $id == $values['user_list5']) {
            echo '<h1>Ce film est deja dans votre liste</h1>';
            header('Refresh:2 ; URL=movies.php');
        } else {
            if ($values['user_list1'] == 0) {
                $req = "UPDATE imovix_users SET user_list1=$id WHERE user_code= $code ";
                try {
                    $resultat = $co->query($req);
                } catch (PDOException $e) {
                    print 'Erreur : ' . $e->getMessage() . '<br />';
                }

                if ($resultat->rowCount() == 1) {
                    $req = 'SELECT * FROM imovix_movies WHERE id= ' . $id . ' ';
                    $resultat_affiche = $co->query($req);

                    foreach ($resultat_affiche as $value) {
                        echo '<img class="bg" src="./img/bg/' . $value['movie_bg'] . '">';
                        echo '<div class="content">';
                        echo '<img class="affiche" src="./img/affiche/' . $value['movie_affiche'] . '">';
                    }


                    echo '<h1><span>' . $value['movie_titre'] . '</span></h1>';
                    echo "<h2 class='validation'>";
                    echo "<div class='word'>";

                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>b</div>";
                    echo "<div class='letter'>i</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>n</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>t</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>j</div>";
                    echo "<div class='letter'>o</div>";
                    echo "<div class='letter'>u</div>";
                    echo "<div class='letter'>t</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>l</div>";
                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>l</div>";
                    echo "<div class='letter'>i</div>";
                    echo "<div class='letter'>s</div>";
                    echo "<div class='letter'>t</div>";
                    echo "<div class='letter'>e</div>";

                    echo "</div>";
                    echo "</h2>";
                    echo '</div>';
                    header('Refresh:3.1 ; URL=movies.php');
                } else {
                    echo '<lottie-player class="anim_warning" src="https://assets4.lottiefiles.com/packages/lf20_Tkwjw8.json" background="transparent" speed="1" loop autoplay></lottie-player>';
                    echo '<h1>Une erreur est survenu lors de la modification</h1>';
                }
            } elseif ($values['user_list2'] == 0) {
                $req = "UPDATE imovix_users SET user_list2=$id WHERE user_code= $code ";
                try {
                    $resultat = $co->query($req);
                } catch (PDOException $e) {
                    print 'Erreur : ' . $e->getMessage() . '<br />';
                }

                if ($resultat->rowCount() == 1) {
                    $req = 'SELECT * FROM imovix_movies WHERE id= ' . $id . ' ';
                    $resultat_affiche = $co->query($req);

                    foreach ($resultat_affiche as $value) {
                        echo '<img class="bg" src="./img/bg/' . $value['movie_bg'] . '">';
                        echo '<div class="content">';
                        echo '<img class="affiche" src="./img/affiche/' . $value['movie_affiche'] . '">';
                    }


                    echo '<h1><span>' . $value['movie_titre'] . '</span></h1>';
                    echo "<h2 class='validation'>";
                    echo "<div class='word'>";

                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>b</div>";
                    echo "<div class='letter'>i</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>n</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>t</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>j</div>";
                    echo "<div class='letter'>o</div>";
                    echo "<div class='letter'>u</div>";
                    echo "<div class='letter'>t</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>l</div>";
                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>l</div>";
                    echo "<div class='letter'>i</div>";
                    echo "<div class='letter'>s</div>";
                    echo "<div class='letter'>t</div>";
                    echo "<div class='letter'>e</div>";

                    echo "</div>";
                    echo "</h2>";
                    echo '</div>';
                    header('Refresh:3.1 ; URL=movies.php');
                } else {
                    echo '<lottie-player class="anim_warning" src="https://assets4.lottiefiles.com/packages/lf20_Tkwjw8.json" background="transparent" speed="1" loop autoplay></lottie-player>';
                    echo '<h1>Une erreur est survenu lors de la modification</h1>';
                }
            } elseif ($values['user_list3'] == 0) {
                $req = "UPDATE imovix_users SET user_list3=$id WHERE user_code= $code ";
                try {
                    $resultat = $co->query($req);
                } catch (PDOException $e) {
                    print 'Erreur : ' . $e->getMessage() . '<br />';
                }

                if ($resultat->rowCount() == 1) {
                    $req = 'SELECT * FROM imovix_movies WHERE id= ' . $id . ' ';
                    $resultat_affiche = $co->query($req);

                    foreach ($resultat_affiche as $value) {
                        echo '<img class="bg" src="./img/bg/' . $value['movie_bg'] . '">';
                        echo '<div class="content">';
                        echo '<img class="affiche" src="./img/affiche/' . $value['movie_affiche'] . '">';
                    }


                    echo '<h1><span>' . $value['movie_titre'] . '</span></h1>';
                    echo "<h2 class='validation'>";
                    echo "<div class='word'>";

                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>b</div>";
                    echo "<div class='letter'>i</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>n</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>t</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>j</div>";
                    echo "<div class='letter'>o</div>";
                    echo "<div class='letter'>u</div>";
                    echo "<div class='letter'>t</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>l</div>";
                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>l</div>";
                    echo "<div class='letter'>i</div>";
                    echo "<div class='letter'>s</div>";
                    echo "<div class='letter'>t</div>";
                    echo "<div class='letter'>e</div>";

                    echo "</div>";
                    echo "</h2>";
                    echo '</div>';
                    header('Refresh:3.1 ; URL=movies.php');
                } else {
                    echo '<lottie-player class="anim_warning" src="https://assets4.lottiefiles.com/packages/lf20_Tkwjw8.json" background="transparent" speed="1" loop autoplay></lottie-player>';
                    echo '<h1>Une erreur est survenu lors de la modification</h1>';
                }
            } elseif ($values['user_list4'] == 0) {
                $req = "UPDATE imovix_users SET user_list4=$id WHERE user_code= $code ";
                try {
                    $resultat = $co->query($req);
                } catch (PDOException $e) {
                    print 'Erreur : ' . $e->getMessage() . '<br />';
                }

                if ($resultat->rowCount() == 1) {
                    $req = 'SELECT * FROM imovix_movies WHERE id= ' . $id . ' ';
                    $resultat_affiche = $co->query($req);

                    foreach ($resultat_affiche as $value) {
                        echo '<img class="bg" src="./img/bg/' . $value['movie_bg'] . '">';
                        echo '<div class="content">';
                        echo '<img class="affiche" src="./img/affiche/' . $value['movie_affiche'] . '">';
                    }


                    echo '<h1><span>' . $value['movie_titre'] . '</span></h1>';
                    echo "<h2 class='validation'>";
                    echo "<div class='word'>";

                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>b</div>";
                    echo "<div class='letter'>i</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>n</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>t</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>j</div>";
                    echo "<div class='letter'>o</div>";
                    echo "<div class='letter'>u</div>";
                    echo "<div class='letter'>t</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>l</div>";
                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>l</div>";
                    echo "<div class='letter'>i</div>";
                    echo "<div class='letter'>s</div>";
                    echo "<div class='letter'>t</div>";
                    echo "<div class='letter'>e</div>";

                    echo "</div>";
                    echo "</h2>";
                    echo '</div>';
                    header('Refresh:3.1 ; URL=movies.php');
                } else {
                    echo '<lottie-player class="anim_warning" src="https://assets4.lottiefiles.com/packages/lf20_Tkwjw8.json" background="transparent" speed="1" loop autoplay></lottie-player>';
                    echo '<h1>Une erreur est survenu lors de la modification</h1>';
                }
            } elseif ($values['user_list5'] == 0) {
                $req = "UPDATE imovix_users SET user_list5=$id WHERE user_code= $code ";
                try {
                    $resultat = $co->query($req);
                } catch (PDOException $e) {
                    print 'Erreur : ' . $e->getMessage() . '<br />';
                }

                if ($resultat->rowCount() == 1) {
                    $req = 'SELECT * FROM imovix_movies WHERE id= ' . $id . ' ';
                    $resultat_affiche = $co->query($req);

                    foreach ($resultat_affiche as $value) {
                        echo '<img class="bg" src="./img/bg/' . $value['movie_bg'] . '">';
                        echo '<div class="content">';
                        echo '<img class="affiche" src="./img/affiche/' . $value['movie_affiche'] . '">';
                    }


                    echo '<h1><span>' . $value['movie_titre'] . '</span></h1>';
                    echo "<h2 class='validation'>";
                    echo "<div class='word'>";

                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>b</div>";
                    echo "<div class='letter'>i</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>n</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>t</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>j</div>";
                    echo "<div class='letter'>o</div>";
                    echo "<div class='letter'>u</div>";
                    echo "<div class='letter'>t</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>l</div>";
                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>l</div>";
                    echo "<div class='letter'>i</div>";
                    echo "<div class='letter'>s</div>";
                    echo "<div class='letter'>t</div>";
                    echo "<div class='letter'>e</div>";

                    echo "</div>";
                    echo "</h2>";
                    echo '</div>';
                    header('Refresh:3.1 ; URL=movies.php');
                } else {
                    echo '<lottie-player class="anim_warning" src="https://assets4.lottiefiles.com/packages/lf20_Tkwjw8.json" background="transparent" speed="1" loop autoplay></lottie-player>';
                    echo '<h1>Une erreur est survenu lors de la modification</h1>';
                }
            } else {

                echo '<lottie-player class="anim_warning" src="https://assets4.lottiefiles.com/packages/lf20_Tkwjw8.json" background="transparent" speed="1" loop autoplay></lottie-player>';
                echo '<h1>Vous avez atteind le nombre <span>maximum</span> de film dans votre liste.</h1>';
                echo '<p class="goback">Redirection dans un instant...</p>';
                header('Refresh:3 ; URL=movies.php');
            }
        }
    }
}


function supprList($co)
{
    $id = $_GET['id'];
    $code = $_SESSION['user_numero'];

    $req = 'SELECT * FROM imovix_users WHERE user_code= ' . $_SESSION["user_numero"] . ' ';
    $resultat_suppr = $co->query($req);
    foreach ($resultat_suppr as $values) {
        if ($id == $values['user_list1']) {
            $req2 = "UPDATE imovix_users SET user_list1=0 WHERE user_code=$code ";
            try {
                $co->query($req2);

                $reqMovie = "SELECT * FROM imovix_movies WHERE id=$id";
                $resultatMovie = $co->query($reqMovie);

                foreach ($resultatMovie as $valueIdMovie) {
                    echo '<lottie-player class="deleteAnimation" src="https://assets1.lottiefiles.com/packages/lf20_VmD8Sl.json"  background="transparent"  speed="1"  style="width: 300px; height: 300px;"  loop  autoplay></lottie-player>';
                    echo '<h1><span>' . $valueIdMovie['movie_titre'] . '</span></h1>';
                    echo "<h2 class='validation'>";
                    echo "<div class='word'>";

                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>b</div>";
                    echo "<div class='letter'>i</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>n</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>t</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>s</div>";
                    echo "<div class='letter'>u</div>";
                    echo "<div class='letter'>p</div>";
                    echo "<div class='letter'>p</div>";
                    echo "<div class='letter'>r</div>";
                    echo "<div class='letter'>i</div>";
                    echo "<div class='letter'>m</div>";
                    echo "<div class='letter'>e</div>";
                    echo "</div>";
                    echo "</h2>";
                }
            } catch (PDOException $e) {
                print 'Erreur : ' . $e->getMessage() . '<br />';
            }
            header('Refresh:3 ; URL=list.php');
        } elseif ($id == $values['user_list2']) {
            $req2 = "UPDATE imovix_users SET user_list2=0 WHERE user_code=$code ";
            try {
                $co->query($req2);

                $reqMovie = "SELECT * FROM imovix_movies WHERE id=$id";
                $resultatMovie = $co->query($reqMovie);

                foreach ($resultatMovie as $valueIdMovie) {
                    echo '<lottie-player class="deleteAnimation" src="https://assets1.lottiefiles.com/packages/lf20_VmD8Sl.json"  background="transparent"  speed="1"  style="width: 300px; height: 300px;"  loop  autoplay></lottie-player>';
                    echo '<h1><span>' . $valueIdMovie['movie_titre'] . '</span></h1>';
                    echo "<h2 class='validation'>";
                    echo "<div class='word'>";

                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>b</div>";
                    echo "<div class='letter'>i</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>n</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>t</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>s</div>";
                    echo "<div class='letter'>u</div>";
                    echo "<div class='letter'>p</div>";
                    echo "<div class='letter'>p</div>";
                    echo "<div class='letter'>r</div>";
                    echo "<div class='letter'>i</div>";
                    echo "<div class='letter'>m</div>";
                    echo "<div class='letter'>e</div>";
                    echo "</div>";
                    echo "</h2>";
                }
            } catch (PDOException $e) {
                print 'Erreur : ' . $e->getMessage() . '<br />';
            }
            header('Refresh:3 ; URL=list.php');
        } elseif ($id == $values['user_list3']) {
            $req2 = "UPDATE imovix_users SET user_list3=0 WHERE user_code=$code ";
            try {
                $co->query($req2);

                $reqMovie = "SELECT * FROM imovix_movies WHERE id=$id";
                $resultatMovie = $co->query($reqMovie);

                foreach ($resultatMovie as $valueIdMovie) {
                    echo '<lottie-player class="deleteAnimation" src="https://assets1.lottiefiles.com/packages/lf20_VmD8Sl.json"  background="transparent"  speed="1"  style="width: 300px; height: 300px;"  loop  autoplay></lottie-player>';
                    echo '<h1><span>' . $valueIdMovie['movie_titre'] . '</span></h1>';
                    echo "<h2 class='validation'>";
                    echo "<div class='word'>";

                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>b</div>";
                    echo "<div class='letter'>i</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>n</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>t</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>s</div>";
                    echo "<div class='letter'>u</div>";
                    echo "<div class='letter'>p</div>";
                    echo "<div class='letter'>p</div>";
                    echo "<div class='letter'>r</div>";
                    echo "<div class='letter'>i</div>";
                    echo "<div class='letter'>m</div>";
                    echo "<div class='letter'>e</div>";
                    echo "</div>";
                    echo "</h2>";
                }
            } catch (PDOException $e) {
                print 'Erreur : ' . $e->getMessage() . '<br />';
            }
            header('Refresh:3 ; URL=list.php');
        } elseif ($id == $values['user_list4']) {
            $req2 = "UPDATE imovix_users SET user_list4=0 WHERE user_code=$code ";
            try {
                $co->query($req2);

                $reqMovie = "SELECT * FROM imovix_movies WHERE id=$id";
                $resultatMovie = $co->query($reqMovie);

                foreach ($resultatMovie as $valueIdMovie) {
                    echo '<lottie-player class="deleteAnimation" src="https://assets1.lottiefiles.com/packages/lf20_VmD8Sl.json"  background="transparent"  speed="1"  style="width: 300px; height: 300px;"  loop  autoplay></lottie-player>';
                    echo '<h1><span>' . $valueIdMovie['movie_titre'] . '</span></h1>';
                    echo "<h2 class='validation'>";
                    echo "<div class='word'>";

                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>b</div>";
                    echo "<div class='letter'>i</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>n</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>t</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>s</div>";
                    echo "<div class='letter'>u</div>";
                    echo "<div class='letter'>p</div>";
                    echo "<div class='letter'>p</div>";
                    echo "<div class='letter'>r</div>";
                    echo "<div class='letter'>i</div>";
                    echo "<div class='letter'>m</div>";
                    echo "<div class='letter'>e</div>";
                    echo "</div>";
                    echo "</h2>";
                }
            } catch (PDOException $e) {
                print 'Erreur : ' . $e->getMessage() . '<br />';
            }
            header('Refresh:3 ; URL=list.php');
        } elseif ($id == $values['user_list5']) {
            $req2 = "UPDATE imovix_users SET user_list5=0 WHERE user_code=$code ";
            try {
                $co->query($req2);

                $reqMovie = "SELECT * FROM imovix_movies WHERE id=$id";
                $resultatMovie = $co->query($reqMovie);

                foreach ($resultatMovie as $valueIdMovie) {
                    echo '<lottie-player class="deleteAnimation" src="https://assets1.lottiefiles.com/packages/lf20_VmD8Sl.json"  background="transparent"  speed="1"  style="width: 300px; height: 300px;"  loop  autoplay></lottie-player>';
                    echo '<h1><span>' . $valueIdMovie['movie_titre'] . '</span></h1>';
                    echo "<h2 class='validation'>";
                    echo "<div class='word'>";

                    echo "<div class='letter'>a</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>b</div>";
                    echo "<div class='letter'>i</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>n</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>t</div>";
                    echo "<div class='letter'>e</div>";
                    echo "<div class='letter'>&nbsp</div>";
                    echo "<div class='letter'>s</div>";
                    echo "<div class='letter'>u</div>";
                    echo "<div class='letter'>p</div>";
                    echo "<div class='letter'>p</div>";
                    echo "<div class='letter'>r</div>";
                    echo "<div class='letter'>i</div>";
                    echo "<div class='letter'>m</div>";
                    echo "<div class='letter'>e</div>";
                    echo "</div>";
                    echo "</h2>";
                }
            } catch (PDOException $e) {
                print 'Erreur : ' . $e->getMessage() . '<br />';
            }
            header('Refresh:3 ; URL=list.php');
        }
    }
}







function myList($co)
{
    $userId = $_SESSION["user_numero"];
    $req = "SELECT * FROM `imovix_movies` WHERE id=( SELECT user_list1 FROM imovix_users WHERE user_code=$userId ) OR id=( SELECT user_list2 FROM imovix_users WHERE user_code=$userId) OR id=( SELECT user_list3 FROM imovix_users WHERE user_code=$userId) OR id=( SELECT user_list4 FROM imovix_users WHERE user_code=$userId) OR id=( SELECT user_list5 FROM imovix_users WHERE user_code=$userId);";
    // echo $req;
    try {
        $resultat = $co->query($req);
    } catch (PDOException $e) {
        print 'Erreur : ' . $e->getMessage() . '<br />';
    }

    foreach ($resultat as $values) {
        echo '<div class="container_movie_list">';
        echo '<video class="bg_ba" autoplay loop muted>';
        echo '<source src="./video/' . $values['movie_ba'] . '" type="video/mp4">';
        echo '</video>';
        echo '<div class="info">';
        echo '<div class="container_illu">';
        echo '<img class="illustration" src="./img/illustration/' . $values['movie_illu'] . '" alt="">';
        echo '</div>';
        echo '<div class="title_affiche">';
        echo '<h1 class="title">' . $values['movie_titre'] . '</h1>';
        echo '<div class="container_affiche card is-out">';
        echo '<img class="affiche" src="./img/affiche/' . $values['movie_affiche'] . '" alt="">';
        echo '<span class="carrousel_affiche_hover">';
        echo '<a class="play" href=""><img class="arrow" src="./img/picto/playArrow.svg" alt=""></a>';
        echo '</span>';
        echo '</div>';
        echo '</div>';
        echo '<h2>' . $values['movie_titre'] . ' ' . $values['movie_titre_2'] . '</h2>';
        echo '<p class="age">Age : <span>' . $values['movie_age'] . '</span></p>';
        echo '<div class="interaction">';
        echo '<button class="btnIteraction play">PLAY</button>';
        echo '<a href="teaser.php?id=' . $values['id'] . '" class="btnIteraction teaser">TEASER</a>';
        echo '</div>';
        echo '<h3 class="genre">Genre : ' . $values['movie_genre'] . '</h3>';
        echo '<p class="descr">' . $values['movie_descr'] . '</p>';
        echo '<a class="removeList" href="list_suppr.php?id=' . $values['id'] . '">Remove from the list</a>';
        echo '</div>';
        echo '</div>';
    }
}


function teaser($co)
{
    $id = $_GET['id'];
    $req = "SELECT * FROM `imovix_movies` WHERE id=$id;";
    try {
        $resultat = $co->query($req);
    } catch (PDOException $e) {
        print 'Erreur : ' . $e->getMessage() . '<br />';
    }

    foreach ($resultat as $values) {
        echo '<div class="container_video">';
        echo '<h1 class="container_video_titre"><span>' . $values['movie_titre'] . '</span>' . $values['movie_titre_2'] . '</h1>';
        echo '<video class="teaser_ba" autoplay loop controls muted>';
        echo '<source src="./video/' . $values['movie_ba'] . '" type="video/mp4">';
        echo '</video>';
        echo '</div>';

        echo '<div class="interaction">';
        echo '<a href="">RETOUR</a>';
        echo '<a class="play" href="">PLAY</a>';
        echo '<a href="">+ ADD LIST</a>';
        echo '</div>';
    }
}


function recherche($co)
{

    $search = $_SESSION['search'];

    if (!isset($_SESSION['search']) && empty($_SESSION['search'])) {
        $req = 'SELECT * FROM imovix_movies ORDER BY RAND();';

        $resultat = $co->query($req);
        foreach ($resultat as $value) {
            echo '<div class="carrousel_genre_container">';

            echo '<div class="carrousel_affiche">';
            echo '<img class="carrousel_affiche_img" src="./img/affiche/' . $value['movie_affiche'] . '" alt="">';

            echo '<span class="carrousel_affiche_hover">';

            echo '<a class="play" href=""><img class="arrow" src="./img/picto/playArrow.svg" alt=""></a>';

            echo '<div class="interaction">';
            echo '<a class="more" href="list_ajout.php?id=' . $value['id'] . '"> <img class="plus" src="./img/picto/+.svg" alt=""> <img class="plus_black" src="./img/picto/+_black.svg" alt=""></a>';
            echo '</div>';

            echo '</span>';

            echo '</div>';




            echo '<div class="hoverInfo">';

            echo '<div class="hoverInfo_descr">';
            echo '<h1>' . $value['movie_titre'] . ' : ' . $value['movie_titre_2'] . '</h1>';
            echo '<h2><span>Genre : </span>' . $value['movie_genre'] . '</h2>';
            echo '<h2><span>Distribution : </span>' . $value['movie_distrib'] . '</h2>';
            echo '<p>' . $value['movie_descr'] . '</p>';
            echo '</div>';

            echo '<div class="hoverInfo_video">';
            echo '<a class="muteBtn">';
            echo '<img class="noMuted" src="./img/picto/noMuted.svg" alt="">';
            echo '<img class="muted" src="./img/picto/Muted.svg" alt="">';
            echo '</a>';
            echo '<video class="avengersEndgame_ba" autoplay loop muted>';
            echo '<source src="./video/' . $value['movie_ba'] . '" type="video/mp4">';
            echo '</video>';
            echo '</div>';

            echo '</div>';
            echo '</div>';
        }
    } else {

        $req2 = "SELECT * FROM imovix_movies WHERE movie_titre LIKE '%$search%'";

        $resultat2 = $co->query($req2);
        foreach ($resultat2 as $value2) {
            echo '<div class="carrousel_genre_container">';

            echo '<div class="carrousel_affiche">';
            echo '<img class="carrousel_affiche_img" src="./img/affiche/' . $value2['movie_affiche'] . '" alt="">';

            echo '<span class="carrousel_affiche_hover">';

            echo '<a class="play" href=""><img class="arrow" src="./img/picto/playArrow.svg" alt=""></a>';

            echo '<div class="interaction">';
            echo '<a class="more" href="list_ajout.php?id=' . $value2['id'] . '"> <img class="plus" src="./img/picto/+.svg" alt=""> <img class="plus_black" src="./img/picto/+_black.svg" alt=""></a>';
            echo '</div>';

            echo '</span>';

            echo '</div>';




            echo '<div class="hoverInfo">';

            echo '<div class="hoverInfo_descr">';
            echo '<h1>' . $value2['movie_titre'] . ' : ' . $value2['movie_titre_2'] . '</h1>';
            echo '<h2><span>Genre : </span>' . $value2['movie_genre'] . '</h2>';
            echo '<h2><span>Distribution : </span>' . $value2['movie_distrib'] . '</h2>';
            echo '<p>' . $value2['movie_descr'] . '</p>';
            echo '</div>';

            echo '<div class="hoverInfo_video">';
            echo '<a class="muteBtn">';
            echo '<img class="noMuted" src="./img/picto/noMuted.svg" alt="">';
            echo '<img class="muted" src="./img/picto/Muted.svg" alt="">';
            echo '</a>';
            echo '<video class="avengersEndgame_ba" autoplay loop muted>';
            echo '<source src="./video/' . $value2['movie_ba'] . '" type="video/mp4">';
            echo '</video>';
            echo '</div>';

            echo '</div>';
            echo '</div>';
        }
    }
}



function genererDatalistAuteurs($co)
{
    // on sélectionne le nom et prénom de tous les auteurs de la table auteurs
    $req = "SELECT movie_titre FROM imovix_movies";
    echo $req;
    try {
        $resultat = $co->query($req);
    } catch (PDOException $e) {
        // s'il y a une erreur, on l'affiche
        echo '<p>Erreur : ' . $e->getMessage() . '</p>';
        die();
    }
    // pour chaque auteur, on met son nom et prénom dans une balise <option>
    foreach ($resultat as $value) {
        echo '<option value="' . $value['movie_titre'] . '">';
    }
}
