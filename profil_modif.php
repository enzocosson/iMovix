<?php
require 'debut.php';
require 'menu.php';


?>
<section data-barba="container" class="profil">


    <?php
    // echo $_SESSION['user_photo'];
    $co = connexionBD();
    afficherModifProfil($co);
    ?>

    <div class="container_pp">
        <div class="container_img">
            <?php
            afficherContainerPP($co);
            ?>
        </div>
        <div class="container_fermer">
            <button type="button" onclick="fermerPP()">Fermer</button>
        </div>
    </div>

    <?php
    deconnexionBD($co);
    ?>

</section>
<script src="./js/afficherPP.js"></script>
<?php
require 'fin.php';
?>