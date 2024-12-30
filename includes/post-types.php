<?php
// Register Custom Post Type: Event
function em_register_event_post_type()
{
    $labels = array(
        'name'                  => 'Events',
        'singular_name'         => 'Event',
        'menu_name'             => 'Events',
        'name_admin_bar'        => 'Event',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New Event',
        'new_item'              => 'New Event',
        'edit_item'             => 'Edit Event',
        'view_item'             => 'View Event',
        'all_items'             => 'All Events',
        'search_items'          => 'Search Events',
        'not_found'             => 'No events found.',
        'not_found_in_trash'    => 'No events found in Trash.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'event'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'supports'           => array('title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'),
    );

    register_post_type('event', $args);
}
add_action('init', 'em_register_event_post_type');

// Add Custom Meta Box for Event Details
function em_add_event_details_meta_boxes()
{
    add_meta_box(
        'em_event_details', // Unique ID
        'Event Details', // Box title
        'em_render_event_details_meta_box', // Callback function
        'event', // Post type
        'normal', // Context
        'default' // Priority
    );
}
add_action('add_meta_boxes', 'em_add_event_details_meta_boxes');

// Render the Meta Box Content
function em_render_event_details_meta_box($post)
{
    // Add a nonce field for security
    wp_nonce_field('em_save_event_details', 'em_event_details_nonce');

    // Retrieve existing values from the database
    $event_date = get_post_meta($post->ID, '_em_event_date', true);
    $end_date = get_post_meta($post->ID, '_em_end_date', true);
    $location = get_post_meta($post->ID, '_em_location', true);
    $google_map_link = get_post_meta($post->ID, '_em_google_map_link', true);

?>
<p>
    <label for="em_event_date">Start Date:</label>
    <input type="date" name="em_event_date" id="em_event_date" value="<?php echo esc_attr($event_date); ?>"
        class="widefat">
</p>
<p>
    <label for="em_end_date">End Date:</label>
    <input type="date" name="em_end_date" id="em_end_date" value="<?php echo esc_attr($end_date); ?>" class="widefat">
</p>
<p>
    <label for="em_location">Location:</label>
    <input type="text" name="em_location" id="em_location" value="<?php echo esc_attr($location); ?>" class="widefat"
        placeholder="Enter location">
</p>
<p>
    <label for="em_google_map_link">Google Map Link:</label>
    <input type="url" name="em_google_map_link" id="em_google_map_link"
        value="<?php echo esc_attr($google_map_link); ?>" class="widefat" placeholder="https://maps.google.com/">
</p>
<?php
}

// Save Meta Box Data
function em_save_event_details($post_id)
{
    // Check if nonce is set and valid
    if (!isset($_POST['em_event_details_nonce']) || !wp_verify_nonce($_POST['em_event_details_nonce'], 'em_save_event_details')) {
        return;
    }

    // Check if user has permission to edit
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Auto-save: don't do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Save Event Start Date
    if (isset($_POST['em_event_date'])) {
        $event_date = sanitize_text_field($_POST['em_event_date']);
        update_post_meta($post_id, '_em_event_date', $event_date);
    }

    // Save Event End Date
    if (isset($_POST['em_end_date'])) {
        $end_date = sanitize_text_field($_POST['em_end_date']);
        update_post_meta($post_id, '_em_end_date', $end_date);
    }

    // Save Location
    if (isset($_POST['em_location'])) {
        $location = sanitize_text_field($_POST['em_location']);
        update_post_meta($post_id, '_em_location', $location);
    }

    // Save Google Map Link
    if (isset($_POST['em_google_map_link'])) {
        $google_map_link = esc_url_raw($_POST['em_google_map_link']);
        update_post_meta($post_id, '_em_google_map_link', $google_map_link);
    }
}
add_action('save_post', 'em_save_event_details');
?>