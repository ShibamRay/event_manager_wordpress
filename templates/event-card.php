<div id="events-list">
    <?php
  $query = new WP_Query(['post_type' => 'event', 'posts_per_page' => -1]);
  if ($query->have_posts()) {
    while ($query->have_posts()) {
      $query->the_post();

      // Event thumbnail
      $image_url = get_the_post_thumbnail_url(get_the_ID(), 'medium') ?: 'https://via.placeholder.com/300';

      // Taxonomy term
      $industry = get_the_terms(get_the_ID(), 'industry')[0]->name ?? 'Industry';

      // Links
      $enquire_link = get_option('em_enquire_link', 'https://mysarathi.in/contact/');
      $read_more_link = get_permalink();

      // Custom Meta Fields: Start Date and End Date
      $start_date = get_post_meta(get_the_ID(), '_em_event_date', true);
      $end_date = get_post_meta(get_the_ID(), '_em_end_date', true);

      // Fallback display if dates are missing
      $start_date_display = $start_date ? esc_html($start_date) : 'Start Date not set';
      $end_date_display = $end_date ? esc_html($end_date) : 'End Date not set';
  ?>
    <div class="event-card">
        <div class="event-image">
            <img src="<?php echo esc_url($image_url); ?>" alt="<?php the_title(); ?>">
        </div>
        <div class="event-content">
            <span class="event-industry"><?php echo esc_html($industry); ?></span>
            <h3><?php the_title(); ?></h3>
            <p class="event-date">
                Event Start Date: <?php echo $start_date_display; ?><br>
                Event End Date: <?php echo $end_date_display; ?>
            </p>
            <div class="event-buttons">
                <a href="<?php echo esc_url($enquire_link); ?>" class="event-button enquire-button"
                    target="_blank">Enquire Now</a>
                <a href="<?php echo esc_url($read_more_link); ?>" class="event-button read-more-button">Read More</a>
            </div>
        </div>
    </div>
    <?php
    }
  } else {
    echo '<p>No events found.</p>';
  }
  wp_reset_postdata();
  ?>
</div>