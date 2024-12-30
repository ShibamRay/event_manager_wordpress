<?php
function em_settings_menu()
{
  add_options_page('Event Management Settings', 'Event Management', 'manage_options', 'em-settings', 'em_settings_page');
}
add_action('admin_menu', 'em_settings_menu');

function em_settings_page()
{
?>
  <div class="wrap">
    <h1>Event Management Settings</h1>
    <form method="post" action="options.php">
      <?php
      settings_fields('em-settings-group');
      do_settings_sections('em-settings');
      submit_button();
      ?>
    </form>
  </div>
<?php
}

function em_register_settings()
{
  register_setting('em-settings-group', 'em_enquire_link');
  add_settings_section('em_general_settings', 'General Settings', null, 'em-settings');
  add_settings_field('em_enquire_link', 'Enquire Now Link', 'em_enquire_link_field', 'em-settings', 'em_general_settings');
}
add_action('admin_init', 'em_register_settings');

function em_enquire_link_field()
{
  $value = get_option('em_enquire_link', '');
  echo '<input type="url" name="em_enquire_link" value="' . esc_attr($value) . '" class="regular-text" />';
}
