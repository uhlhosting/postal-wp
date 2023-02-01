<?php
/**
 * Settings Form for the Postal Mail plugin
 */

// Exit if accessed directly
if (!defined('ABSPATH'))
{
    exit;
}

/**
 * Render the settings page for the plugin
 */
function postal_wp_settings_page()
{
    // Check if the user has the necessary permissions
    if (!current_user_can('manage_options'))
    {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    // Get the current values for the options
    $postal_wp_switch = get_option('postal_wp_switch', '1');
    $postal_wp_host = get_option('postal_wp_host', '');
    $postal_wp_secret_key = get_option('postal_wp_secret_key', '');
    $postal_wp_from_address = get_option('postal_wp_from_address', '');

    // Check if the form has been submitted
    if (isset($_POST['postal_wp_submit']))
    {
        // Validate the nonce
        check_admin_referer('postal_wp_options');

        // Get the new values from the form
        $postal_wp_switch = isset($_POST['postal_wp_switch']) ? 1 : 0;
        $postal_wp_host = sanitize_text_field($_POST['postal_wp_host']);
        $postal_wp_secret_key = sanitize_text_field($_POST['postal_wp_secret_key']);
        $postal_wp_from_address = sanitize_email($_POST['postal_wp_from_address']);

        // Update the options in the database
        update_option('postal_wp_switch', $postal_wp_switch);
        update_option('postal_wp_host', $postal_wp_host);
        update_option('postal_wp_secret_key', $postal_wp_secret_key);
        update_option('postal_wp_from_address', $postal_wp_from_address);

        // Show a success message
        echo '<div class="updated"><p>Settings saved successfully.</p></div>';
    }

    // Render the form

    ?><div class="wrap">
    <h1>Postal Mail Settings</h1>
    <form method="post" action="">
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="postal_wp_switch">Enable Email Sending</label>
                </th>
                <td>
                    <input type="checkbox" name="postal_wp_switch" id="postal_wp_switch" value="1"
                        <?php checked($postal_wp_switch, 1); ?> />
                        <p class="description">Check this option to enable email sending through the Postal Mail API. If it's not checked, emails will be sent using the default WordPress email function.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="postal_wp_host">Postal Mail API Host</label>
                    </th>
                    <td>
                        <input type="text" id="postal_wp_host" name="postal_wp_host" value="
                            <?php echo esc_attr(get_option('postal_wp_host')); ?>">
                            <p class="description">The URL of the Postal Mail API host. Example: https://api.postalmail.io</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="postal_wp_secret_key">Postal Mail API Secret Key</label>
                        </th>
                        <td>
                            <input type="text" id="postal_wp_secret_key" name="postal_wp_secret_key" value="
                                <?php echo esc_attr(get_option('postal_wp_secret_key')); ?>">
                                <p class="description">The secret key used to authenticate with the Postal Mail API. You can find this in the API settings of your Postal Mail account.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="postal_wp_from_address">From Address</label>
                            </th>
                            <td>
                                <input type="text" id="postal_wp_from_address" name="postal_wp_from_address" value="
                                    <?php echo esc_attr(get_option('postal_wp_from_address')); ?>">
                                    <p class="description">The email address that will be used as the "From" address for emails sent through the Postal Mail API.</p>
                                </td>
                            </tr>
                        </table>
                        <?php submit_button(); ?>
                    </form>
                </div>
                <?php
}

