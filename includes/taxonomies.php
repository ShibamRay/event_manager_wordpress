<?php
// Register Taxonomies
function em_register_taxonomies()
{
  // Industry
  register_taxonomy('industry', 'event', [
    'label' => 'Industry',
    'hierarchical' => true,
  ]);

  // Specialty
  register_taxonomy('specialty', 'event', [
    'label' => 'Specialty',
    'hierarchical' => true,
  ]);

  // Country
  register_taxonomy('country', 'event', [
    'label' => 'Country',
    'hierarchical' => true,
  ]);
}
add_action('init', 'em_register_taxonomies');
