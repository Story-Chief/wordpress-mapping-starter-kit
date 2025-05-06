<?php
/**
 * Plugin Name: StoryChief Elementor
 * Plugin URI: https://storychief.io
 * Description: Provide Elementor support.
 * Version: 1.0
 * Author: Gregory Claeyssens - StoryChief
 * Author URI: https://storychief.io
 * Inspiration: https://www.soliddigital.com/blog/how-to-programmatically-create-elementor-posts
 * Guide: https://help.storychief.io/en/articles/11203943-publish-articles-to-your-wordpress-elementor-template
 */
function storychief_handle_elementor_templating($payload) {
    // Enter the WordPress Elementor template ID created in step 1 of the article.
    $TEMPLATE_ID = "ENTER ID HERE";
    $postId = $payload['external_id'];

    // Boilerplate parameters to make the page work
    update_post_meta($postId, '_elementor_edit_mode', 'builder');
    update_post_meta($postId, '_elementor_template_type', 'wp-page');
    update_post_meta($postId, '_elementor_version', ELEMENTOR_VERSION);
    update_post_meta($postId, '_elementor_pro_version', ELEMENTOR_PRO_VERSION);
    update_post_meta($postId, '_elementor_css', '');

    // Retrieve the Elementor settings, data, assets, and controls from the template
    $settings = get_post_meta($TEMPLATE_ID, '_elementor_page_settings', true);
    $data = json_decode(get_post_meta($TEMPLATE_ID, '_elementor_data', true), true);
    $assets = get_post_meta($TEMPLATE_ID, '_elementor_page_assets', true);
    $controls = get_post_meta($TEMPLATE_ID, '_elementor_controls_usage', true);

    // Replace the content block
    StoryChiefElementorReplaceContentBlockRecursively($data, '%%REPLACE_ME%%', $payload['content']);

    // Save the Elementor setting, data, assets, and controls into the new page
    update_post_meta($postId, '_elementor_page_settings', $settings);
    update_post_meta($postId, '_elementor_data', $data);
    update_post_meta($postId, '_elementor_page_assets', $assets);
    update_post_meta($postId, '_elementor_controls_usage', $controls);
}
add_action('storychief_after_publish_action', 'storychief_handle_elementor_templating');

function StoryChiefElementorReplaceContentBlockRecursively(&$data, $search, $replacement): void
{
    foreach ($data as &$value) {
        if (is_array($value)) {
            // Recurse into nested arrays
            StoryChiefElementorReplaceContentBlockRecursively($value, $search, $replacement);
        } elseif (is_string($value) && str_contains($value, $search)) {
            // Replace the whole attribute's value
            $value = $replacement;
        }
    }
}