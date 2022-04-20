<div class="wrap">
    <?php
    echo sprintf("<h1>%s</h1>", __("Edit email template"));

    ?>

    <form action="" method="post">
        <input type="hidden" name="templateId" value="<?php echo isset($data->id) ? $data->id : "" ?>">
        <table class="form-table">
            <tr>
                <td><?php _e("Template Name:") ?></td>
                <td><input type="text" name="templateName" value="<?php echo isset($data->title) ? $data->title : "" ?>" class="regular-text"></td>
            </tr>
            <tr>
                <td><?php _e("Subject:") ?></td>
                <td><input type="text" name="subject" value="<?php echo isset($data->subject) ? $data->subject : "" ?>" class="regular-text"></td>
            </tr>
            <tr>
                <td><?php _e("Tags:") ?></td>
                <td>
                    <?php echo isset($data->tags) ? $data->tags : "" ?>
                </td>
            </tr>
            <tr>
                <td><?php _e("Body:") ?></td>
                <td>
                    <?php

                    $content = isset($data->content) ? $data->content : "";
                    $editor_settings = array('wpautop' => false, 'textarea_name' => 'templateContent', 'quicktags' => true, 'tinymce' => true, 'quicktags' => array('buttons' => 'em,strong,link,block,ul,ol,li,p,close,spell,img'));
                    wp_editor($content, 'templateBody', $editor_settings);

                    ?>
                </td>
            </tr>

            <tr>
                <td><input type="submit" name="templateSave" value="<?php _e("Save") ?>" class="button button-primary" /></td>
            </tr>

        </table>
    </form>
</div>