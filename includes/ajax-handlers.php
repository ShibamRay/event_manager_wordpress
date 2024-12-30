<?php
add_action('wp_ajax_em_filter_events', 'em_filter_events');
add_action('wp_ajax_nopriv_em_filter_events', 'em_filter_events');

function em_filter_events()
{
  $filters = $_POST['filters'];

  $args = [
    'post_type' => 'event',
    'posts_per_page' => -1,
    'post_status' => 'publish',
  ];

  if (!empty($filters['industry'])) {
    $args['tax_query'][] = [
      'taxonomy' => 'industry',
      'field'    => 'slug',
      'terms'    => $filters['industry'],
    ];
  }

  if (!empty($filters['specialty'])) {
    $args['tax_query'][] = [
      'taxonomy' => 'specialty',
      'field'    => 'slug',
      'terms'    => $filters['specialty'],
    ];
  }

  if (!empty($filters['country'])) {
    $args['tax_query'][] = [
      'taxonomy' => 'country',
      'field'    => 'slug',
      'terms'    => $filters['country'],
    ];
  }

  $query = new WP_Query($args);

  if ($query->have_posts()) {
    while ($query->have_posts()) {
      $query->the_post();

      // Ensure featured image fallback
      $image_url = get_the_post_thumbnail_url(get_the_ID(), 'medium') ?: 'https://via.placeholder.com/300';
      $industry = get_the_terms(get_the_ID(), 'industry')[0]->name ?? 'Industry';
      $enquire_link = get_post_meta(get_the_ID(), '_em_enquire_link', true) ?: '#';
      $read_more_link = get_permalink();

      // Retrieve the custom Start Date and End Date
      $event_start_date = get_post_meta(get_the_ID(), '_em_event_date', true);
      $event_end_date = get_post_meta(get_the_ID(), '_em_end_date', true);
      $event_date_display = $event_start_date ? esc_html($event_start_date) : 'Start Date not set';
      $event_end_date_display = $event_end_date ? esc_html($event_end_date) : 'End Date not set';
?>
<div class="event-card">
    <div class="event-image">
        <img src="<?php echo esc_url($image_url); ?>" alt="<?php the_title(); ?>">
    </div>
    <div class="event-content">
        <span class="event-industry"><?php echo esc_html($industry); ?></span>
        <h3><?php the_title(); ?></h3>
        <p class="event-date">
            Event Start Date: <?php echo $event_date_display; ?><br>
            Event End Date: <?php echo $event_end_date_display; ?>
        </p>
        <p><?php the_excerpt(); ?></p>
        <div class="event-buttons">
            <a href="<?php echo esc_url($enquire_link); ?>" class="event-button enquire-button" target="_blank">Enquire
                Now</a>
            <a href="<?php echo esc_url($read_more_link); ?>" class="event-button read-more-button">Read More</a>
        </div>
    </div>
</div>
<?php
    }
  } else {
    echo '<p>No events found matching your criteria.</p>';
  }

  wp_reset_postdata();
  wp_die();
}