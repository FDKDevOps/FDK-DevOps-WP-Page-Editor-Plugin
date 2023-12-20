<?php
/*
Plugin Name: FDKDEVOPS WP Page Editor Plugin
Description: A simple plugin to add an editor for updating page content.
Version: 1.0
Author: Faisal Dad Khan
*/

// Add meta box to page editing screen
function page_editor_meta_box() {
    add_meta_box(
        'page-editor-meta-box',
        'Page Editor',
        'page_editor_meta_box_callback',
        'page',
        'normal',
        'high'
    );
}

function page_editor_meta_box_callback($post) {
    wp_nonce_field('page_editor_nonce', 'page_editor_nonce');
    $content = get_post_meta($post->ID, '_page_editor_content', true);
    ?>
    <label for="page_editor_content">New Content:</label>
    <textarea id="page_editor_content" name="page_editor_content"><?php echo esc_textarea($content); ?></textarea>
    <?php
}

add_action('add_meta_boxes_page', 'page_editor_meta_box');

// Save meta box content
function save_page_editor_meta_box($post_id) {
    if (!isset($_POST['page_editor_nonce']) || !wp_verify_nonce($_POST['page_editor_nonce'], 'page_editor_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['page_editor_content'])) {
        update_post_meta($post_id, '_page_editor_content', sanitize_textarea_field($_POST['page_editor_content']));
    }
}

add_action('save_post', 'save_page_editor_meta_box');
