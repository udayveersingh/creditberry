<?php

if (isset($_SESSION['cb_success']) && !empty($_SESSION['cb_success'])) {

    ?>

    <div class="col-md-12">
        <div class="alert alert-success">
            <?php

                echo $_SESSION['cb_success'];
                unset($_SESSION['cb_success']);

                ?>
        </div>
    </div>

<?php

}
?>