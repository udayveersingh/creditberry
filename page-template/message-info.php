<?php

if (isset($_SESSION['cb_info']) && !empty($_SESSION['cb_info'])) {
    ?>
    <div class="col-md-12">
        <div class="alert alert-info">
            <?php
                echo $_SESSION['cb_info'];
                unset($_SESSION['cb_info']);
                if (isset($_SESSION['cb_info_inprogress'])) {
                    unset($_SESSION['cb_info_inprogress']);
                }
                ?>
        </div>
    </div>
<?php
}
?>