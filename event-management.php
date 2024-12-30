<?php

/**
 * Plugin Name: Event Management
 * Description: A plugin to create, manage, and filter events with modern cards.
 * Version: 1.2
 * Author: Shibam Ray
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

// Define constants
define('EM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('EM_PLUGIN_URL', plugin_dir_url(__FILE__));

// Check if all required dependencies exist
if (!function_exists('add_action')) {
  exit("Error: WordPress functions not available.");
}

// Load translation text domain
add_action('init', function () {
  load_plugin_textdomain('event-management', false, dirname(plugin_basename(__FILE__)) . '/languages');
});

// Include required files
require_once EM_PLUGIN_DIR . 'includes/post-types.php';
require_once EM_PLUGIN_DIR . 'includes/taxonomies.php';
require_once EM_PLUGIN_DIR . 'includes/shortcodes.php';
require_once EM_PLUGIN_DIR . 'includes/settings.php';
require_once EM_PLUGIN_DIR . 'includes/ajax-handlers.php';

// Enqueue assets
function em_enqueue_assets()
{
  wp_enqueue_style('em-styles', EM_PLUGIN_URL . 'assets/css/styles.css');
  wp_enqueue_script('em-scripts', EM_PLUGIN_URL . 'assets/js/scripts.js', ['jquery'], null, true);
  wp_localize_script('em-scripts', 'em_ajax', ['url' => admin_url('admin-ajax.php')]);
}
add_action('wp_enqueue_scripts', 'em_enqueue_assets');

add_filter('template_include', 'em_single_event_template');

function em_single_event_template($template)
{
  if (is_singular('event')) {
    return plugin_dir_path(__FILE__) . 'templates/single-event.php';
  }
  return $template;
}