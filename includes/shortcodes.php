<?php
// Shortcode to Display Events in Horizontal Cards
function em_event_shortcode()
{
    ob_start();
?>
<div id="event-filters">
    <select id="filter-industry">
        <option value="">Select Industry</option>
        <?php foreach (get_terms('industry') as $term): ?>
        <option value="<?= esc_attr($term->slug); ?>"><?= esc_html($term->name); ?></option>
        <?php endforeach; ?>
    </select>

    <select id="filter-specialty">
        <option value="">Select Specialty</option>
        <?php foreach (get_terms('specialty') as $term): ?>
        <option value="<?= esc_attr($term->slug); ?>"><?= esc_html($term->name); ?></option>
        <?php endforeach; ?>
    </select>

    <select id="filter-country">
        <option value="">Select Country</option>
        <?php foreach (get_terms('country') as $term): ?>
        <option value="<?= esc_attr($term->slug); ?>"><?= esc_html($term->name); ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div id="events-list">
    <?php
        $query = new WP_Query(['post_type' => 'event', 'posts_per_page' => -1]);
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                // Use featured image or fallback to placeholder
                $image_url = get_the_post_thumbnail_url(get_the_ID(), 'medium') ?: 'https://via.placeholder.com/300';
                $industry = get_the_terms(get_the_ID(), 'industry')[0]->name ?? 'Industry';
                $enquire_link = get_post_meta(get_the_ID(), '_em_enquire_link', true) ?: '#';
                $read_more_link = get_permalink();

                // Fetch the custom Start Date and End Date
                $event_start_date = get_post_meta(get_the_ID(), '_em_event_date', true);
                $event_end_date = get_post_meta(get_the_ID(), '_em_end_date', true);
                $event_start_date_display = $event_start_date ? esc_html($event_start_date) : 'Start date not set';
                $event_end_date_display = $event_end_date ? esc_html($event_end_date) : 'End date not set';
        ?>
    <div class="event-card">
        <div class="event-image">
            <img src="<?php echo esc_url($image_url); ?>" alt="<?php the_title(); ?>">
        </div>
        <div class="event-content">
            <span class="event-industry"><?php echo esc_html($industry); ?></span>
            <h3><?php the_title(); ?></h3>
            <p class="event-date">
                Event Start Date: <?php echo $event_start_date_display; ?><br>
                Event End Date: <?php echo $event_end_date_display; ?>
            </p>
            <p><?php the_excerpt(); ?></p>
            <div class="event-buttons">
                <a href="https://mysarathi.in/contact/" class="event-button enquire-button" target="_blank">Enquire
                    Now</a>
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

<script>
jQuery(document).ready(function($) {
    $('#event-filters select').on('change', function() {
        const filters = {
            industry: $('#filter-industry').val(),
            specialty: $('#filter-specialty').val(),
            country: $('#filter-country').val(),
        };
        $.post(em_ajax.url, {
            action: 'em_filter_events',
            filters: filters,
        }, function(response) {
            $('#events-list').html(response);
        });
    });
});
</script>
<?php
    return ob_get_clean();
}
add_shortcode('em_events', 'em_event_shortcode');