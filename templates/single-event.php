<?php get_header(); ?>
<style>
.event-container {
    max-width: 800px;
    margin: 30px auto;
    padding: 20px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.event-header {
    text-align: center;
    margin-bottom: 20px;
}

.event-header h1 {
    font-size: 32px;
    font-weight: bold;
    color: #333;
}

.event-header p {
    font-size: 16px;
    color: #555;
    margin-top: 5px;
}

.event-thumbnail {
    text-align: center;
    margin-bottom: 20px;
}

.event-thumbnail img {
    max-width: 100%;
    border-radius: 8px;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
}

.event-details {
    margin-bottom: 20px;
}

.event-details p {
    font-size: 16px;
    color: #444;
    line-height: 1.6;
}

.event-details a {
    color: #007bff;
    text-decoration: none;
    font-weight: bold;
}

.event-details a:hover {
    text-decoration: underline;
}

.event-description {
    font-size: 16px;
    color: #555;
    line-height: 1.8;
    margin-top: 20px;
}

.google-map-link {
    display: block;
    margin-top: 10px;
    color: #007bff;
    font-size: 16px;
    font-weight: bold;
    text-decoration: none;
}

.google-map-link:hover {
    color: #0056b3;
    text-decoration: underline;
}
</style>
<div class="event-container">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="event-header">
        <h1><?php the_title(); ?></h1>
        <?php
                // Fetch custom Start Date and End Date
                $start_date = get_post_meta(get_the_ID(), '_em_event_date', true);
                $end_date = get_post_meta(get_the_ID(), '_em_end_date', true);
                ?>
        <?php if ($start_date): ?>
        <p><strong>Start Date:</strong> <?php echo esc_html($start_date); ?></p>
        <?php endif; ?>
        <?php if ($end_date): ?>
        <p><strong>End Date:</strong> <?php echo esc_html($end_date); ?></p>
        <?php endif; ?>
    </div>
    <div class="event-thumbnail">
        <?php if (has_post_thumbnail()) : ?>
        <?php the_post_thumbnail('large'); ?>
        <?php endif; ?>
    </div>
    <div class="event-details">
        <?php
                $location = get_post_meta(get_the_ID(), '_em_location', true);
                $google_map_link = get_post_meta(get_the_ID(), '_em_google_map_link', true);
                $start_time = get_post_meta(get_the_ID(), '_em_start_time', true);
                $end_time = get_post_meta(get_the_ID(), '_em_end_time', true);
                ?>
        <?php if ($start_time || $end_time): ?>
        <p><strong>Time:</strong>
            <?php echo $start_time ? esc_html($start_time) : 'Start time not set'; ?>
            <?php if ($end_time): ?>
            - <?php echo esc_html($end_time); ?>
            <?php endif; ?>
        </p>
        <?php endif; ?>
        <?php if ($location): ?>
        <p><strong>Location:</strong> <?php echo esc_html($location); ?></p>
        <?php endif; ?>
        <?php if ($google_map_link): ?>
        <p><strong>Google Map:</strong>
            <a href="<?php echo esc_url($google_map_link); ?>" class="google-map-link" target="_blank">Show in Map</a>
        </p>
        <?php endif; ?>
    </div>
    <div class="event-description">
        <?php the_content(); ?>
    </div>
    <?php endwhile;
    endif; ?>
</div>
<?php get_footer(); ?>