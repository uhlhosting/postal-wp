<?php
/**
 * Plugin Name: Postal Mail
 * Plugin URI: https://uhlhosting.ch/postal-mail
 * Description: A plugin to send emails through Postal Mail API
 * Version: 1.0.0
 * Author: Viorel-Cosmin Miron
 * Author URI: https://uhlhosting.ch
 * Text Domain: postal-mail
 */

namespace PostalWp;

use AtelliTech\Postal\Client;
use AtelliTech\Postal\SendMessage;
use AtelliTech\Postal\Exception\PostalException;

// Exit if accessed directly
if (!defined('ABSPATH'))
{
    exit;
}

define('PostalWp\FILE', __FILE__);

// Require the Postal API library
require_once 'vendor/autoload.php';

// Check if the library is installed
if (!class_exists('AtelliTech\Postal\Client'))
{
    // Show an error message in the admin panel if the library is not installed
    add_action('admin_notices', function ()
    {
        echo '<div class="error"><p>The Postal API library is not installed. Please install it by running composer install in the plugin directory.</p></div>';
    });
    return;
}

// Create a new Postal client using the server key and URL from the options
$host = get_option('postal_wp_host');
$secretKey = get_option('postal_wp_secret_key');
$client = new Client($host, $secretKey);

/**
 * Send an email using the Postal API
 *
 * @param string|array $to Recipient email address(es)
 * @param string $subject Email subject
 * @param string $message Email message
 * @param string $headers Email headers
 * @param array $attachments Email attachments
 * @return bool Whether the email was sent successfully
 */
function postal_mail($to, $subject, $message, $headers = '', $attachments = [])
{
    global $client;

    // Check if email sending is enabled or disabled
    $postal_wp_switch = get_option('postal_wp_switch');
    if (!$postal_wp_switch)
    {
        // If it's disabled, use the default WordPress email function
        return wp_mail($to, $subject, $message, $headers, $attachments);
    }

    // Create the email parameters
    $params = ['subject' => $subject, 'to' => is_array($to) ? $to : [$to], 'from' => get_option('postal_wp_from_address') , 'html_body' => $message, ];

    // Check if there are any headers and add them to the parameters
    if (!empty($headers))
    {
        $headers = explode("\n", $headers);
        foreach ($headers as $header)
        {
            if (strpos($header, ':') !== false)
            {
                list($key, $value) = explode(':', $header);
                $key = trim($key);
                $value = trim($value);
                switch ($key)
                {
                    case 'From':
                        $params['from'] = $value;
                    break;
                    case 'Reply-To':
                        $params['reply_to'] = $value;
                    break;
                    case 'CC':
                        $params['cc'] = explode(',', $value);
                    break;
                    case 'BCC':
                        $params['bcc'] = explode(',', $value);
                    break;
                }
            }
        }
    }

    // Check if there are any attachments and add them to the parameters
    if (!empty($attachments))
    {
        $params['attachments'] = [];
        foreach ($attachments as $attachment)
        {
            if (file_exists($attachment))
            {
                $params['attachments'][] = ['name' => basename($attachment) , 'data' => base64_encode(file_get_contents($attachment)) , ];
            }
        }
    }

    try
    {
        // Send the email using the Postal API
        $sendMessage = new SendMessage($params);
        $result = $client->sendMessage($sendMessage);
        return true;
    }
    catch(PostalException $e)
    {
        // If there was an error, log it and return false
        error_log($e->getMessage());
        return false;
    }
}

// Override the default WordPress email function
add_filter('wp_mail', function ($args)
{
    return postal_mail($args['to'], $args['subject'], $args['message'], $args['headers'], $args['attachments']);
});

// Add a setting page for the plugin in the WordPress admin panel
add_action('admin_menu', function ()
{
    add_options_page('Postal Mail', 'Postal Mail', 'manage_options', 'postal_mail', function ()
    {
        // Check if the user has the correct permissions
        if (!current_user_can('manage_options'))
        {
            wp_die('You do not have sufficient permissions to access this page.');
        }

        // Show the settings form
        require_once 'settings-form.php';
    });
});

// Register the plugin options in the WordPress database
add_action('admin_init', function ()
{
    register_setting('postal_mail', 'postal_wp_host');
    register_setting('postal_mail', 'postal_wp_secret_key');
    register_setting('postal_mail', 'postal_wp_from_address');
    register_setting('postal_mail', 'postal_wp_switch');
});

// Load the plugin text domain for translation
add_action('plugins_loaded', function ()
{
    load_plugin_textdomain('postal-mail', false, dirname(plugin_basename(FILE)) . '/languages/');
});

// Add a plugin action link to the plugin page
add_filter('plugin_action_links_' . plugin_basename(FILE) , function ($links)
{
    $links[] = '<a href="' . admin_url('options-general.php?page=postal_mail') . '">' . __('Settings', 'postal-mail') . '</a>';
    return $links;
});

