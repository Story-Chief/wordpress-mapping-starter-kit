<?php
/**
 * Plugin Name: StoryChief Custom Field
 * Plugin URI: https://storychief.io
 * Description: Provide Custom Field Mapping.
 * Version: 1.0
 * Author: Gregory Claeyssens - StoryChief
 * Author URI: https://storychief.io
 * Guide: https://help.storychief.io/en/articles/3010736-wordpress-using-custom-fields#h_c99cff8a5b
 */

function storyChiefSetCustomField($payload)
{
    $STORYCHIEF_FIELD_KEY = 'INSERT STORYCHIEF FIELD KEY';
    $ACF_FIELD_NAME = 'INSERT ACF FIELD NAME';

    $value = storyChiefGetCustomFieldValue($payload, $STORYCHIEF_FIELD_KEY);
    $postId = $payload['external_id'];

    if (!is_null($value)) {
        // (Optional) Do any kind of additional work here

        // When using ACF plugin
        update_field($ACF_FIELD_NAME, $value, $postId);

        // When using non-ACF custom fields
        // update_post_meta($postId, $ACF_FIELD_NAME, $raw_value);
    } else {
        // When using ACF plugin
        delete_field($ACF_FIELD_NAME, $postId);

        // When using non-ACF custom fields
        // delete_post_meta($postId, $ACF_FIELD_NAME);
    }
}
add_action('storychief_after_publish_action', 'storyChiefSetCustomField');


/**
 * Helper function to get a custom field value by key
 */
function storyChiefGetCustomFieldValue($payload, $field_key)
{
    foreach ($payload['custom_fields'] as $customField) {
        if ($customField['key'] === $field_key && $customField['value']) {
            return $customField['value'];
        }
    }
    return null;
}