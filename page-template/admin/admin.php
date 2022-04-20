<?php
$pages = get_pages();
?>
<div class="wrap">
    <h2>Credit berry pages</h2>

    <form action="" method="post" name="frm-cb-pages">
        <table class="form-table wp-list-table widefat striped">
            <tr>
                <td> Register </td>
                <td>
                    <?php
                    $pages = get_pages();
                    echo "<select name='cb_register'>";
                    foreach ($pages as $page) {
                        $option = '<option value="' . $page->ID . '">';
                        $option .= $page->post_title;
                        $option .= '</option>';
                        echo $option;
                    }
                    echo "</select>";

                    ?>
                </td>
            </tr>
            <tr>
                <td> Question </td>
                <td>
                    <?php
                    $pages = get_pages();
                    echo "<select name='cb_question'>";
                    foreach ($pages as $page) {
                        $option = '<option value="' . $page->ID . '">';
                        $option .= $page->post_title;
                        $option .= '</option>';
                        echo $option;
                    }
                    echo "</select>";

                    ?>
                </td>
            </tr>
            <tr>
                <td> Complete process </td>
                <td>
                    <?php
                    $pages = get_pages();
                    echo "<select name='cb_complete'>";
                    foreach ($pages as $page) {
                        $option = '<option value="' . $page->ID . '">';
                        $option .= $page->post_title;
                        $option .= '</option>';
                        echo $option;
                    }
                    echo "</select>";

                    ?>
                </td>
            </tr>
            <tr>
                <td> Message </td>
                <td>
                    <?php
                    $pages = get_pages();
                    echo "<select name='cb_message'>";
                    foreach ($pages as $page) {
                        $option = '<option value="' . $page->ID . '">';
                        $option .= $page->post_title;
                        $option .= '</option>';
                        echo $option;
                    }
                    echo "</select>";

                    ?>
                </td>
            </tr>
            <tr>
                <td> Thank you </td>
                <td>
                    <?php
                    $pages = get_pages();
                    echo "<select name='cb_thankyou'>";
                    foreach ($pages as $page) {
                        $option = '<option value="' . $page->ID . '">';
                        $option .= $page->post_title;
                        $option .= '</option>';
                        echo $option;
                    }
                    echo "</select>";

                    ?>
                </td>
            </tr>

        </table>
        <br />
        <input type="submit" name="save_cb_pages" value="Save pages" class="button button-primary" />
    </form>

</div>