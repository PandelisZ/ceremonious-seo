<div class="wrap">
<h2>Ceremonious Settings</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'my-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'my-plugin-settings-group' ); ?>

    <h3> Majestic Configuration:</h3>
    <p>Please enter your API key as supplied by Majestic. To get an API key create an account at <a href="https://majestic.com/" target="_blank">majestic.com</a></p>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">API_KEY</th>
        <td><input type="text" name="MAJESTIC_API_KEY" value="<?php echo esc_attr( get_option('MAJESTIC_API_KEY') ); ?>" /></td>
        </tr>
    </table>

    <?php submit_button(); ?>

</form>
</div>
