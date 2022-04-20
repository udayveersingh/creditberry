<div class="wrap">
    <?php
    add_thickbox();
    echo sprintf("<h1>%s</h1>", __("Email templates"));
    ?>
    <div class="cb_email_list">
        <div>
            <input type="hidden" name="page" value="cb-emails" />
            <form name="frm_cb_email_list" id="frm_cb_email_list" action="" method="post">
                <?php
                $data->display();
                ?>
            </form>
        </div>
        <div id="emailtest" style="display:none">
            <h2>Example Pop-up Window 1</h2>
            <div style="float:left;padding:10px;">
                <img src="http://shibashake.com/wordpress-theme/wp-content/uploads/2010/03/bio1.jpg" width="150" height="168" />
            </div>
            I was born at DAZ Studio. They created me with utmost care and that is why I am the hottie that you see today. My interests include posing, trying out cute outfits, and more posing.

            <strong>Just click outside the pop-up to close it.</strong>
        </div>
    </div>
</div>