<?php

if (isset($_SESSION['cb_error']) && !empty($_SESSION['cb_error'])) {

    ?>

    <div class="col-md-12">
        <div class="alert alert-danger">
            <?php

                echo $_SESSION['cb_error'];
                unset($_SESSION['cb_error']);

                ?>
        </div>
    </div>

<?php

}
?>