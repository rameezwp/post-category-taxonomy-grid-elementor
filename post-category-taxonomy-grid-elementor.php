<?php
/**
 * Plugin Name: Post Category Taxonomy Grid for Elementor
 * Description: Display categories or taxonomies in a visually appealing grid layout using the Elementor page builder.
 * Version:     1.0.0
 * Author:      Classic Addons
 * Author URI:  https://classicaddons.com/
 * Text Domain: post-category-taxonomy-grid
 */

function cawp_post_category_taxonomy_grid_widget( $widgets_manager ) {

    require_once( __DIR__ . '/widgets/post-category-taxonomy-grid.php' );

    $widgets_manager->register( new \CAWP_Post_Category_Taxonomy_Grid() );

}
add_action( 'elementor/widgets/register', 'cawp_post_category_taxonomy_grid_widget' );

function cawpcategory_grid_enqueue_custom_styles() {
    wp_enqueue_style( 'cawp-category-grid', plugins_url( 'css/style.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'cawpcategory_grid_enqueue_custom_styles' );

function cawpcategory_grid_category_thumbnail_field( $term ) {
    $pcge_thumbnail_id = get_term_meta( $term->term_id, 'pcge_thumbnail_id', true );
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="pcge_thumbnail_id"><?php _e( 'Thumbnail', 'textdomain' ); ?></label></th>
        <td>
            <input type="hidden" id="pcge_thumbnail_id" name="pcge_thumbnail_id" value="<?php echo esc_attr( $pcge_thumbnail_id ); ?>">
            <div id="thumbnail_preview">
                <?php if ( $pcge_thumbnail_id ) : ?>
                    <?php echo wp_get_attachment_image( $pcge_thumbnail_id, 'thumbnail' ); ?>
                <?php endif; ?>
            </div>
            <p>
                <button type="button" id="upload_thumbnail_button" class="button"><?php _e( 'Upload/Add image', 'textdomain' ); ?></button>
                <button type="button" id="remove_thumbnail_button" class="button"><?php _e( 'Remove image', 'textdomain' ); ?></button>
            </p>
        </td>
    </tr>
    <?php
}
add_action( 'category_edit_form_fields', 'cawpcategory_grid_category_thumbnail_field', 10, 2 );

function cawpcategory_grid_save_custom_category_thumbnail_field( $term_id ) {
    if ( isset( $_POST['pcge_thumbnail_id'] ) ) {
        update_term_meta( $term_id, 'pcge_thumbnail_id', absint( $_POST['pcge_thumbnail_id'] ) );
    }
}
add_action( 'edited_category', 'cawpcategory_grid_save_custom_category_thumbnail_field', 10, 2 );

function cawpcategory_grid_enqueue_media_scripts( $hook_suffix ) {
    if ( 'edit-tags.php' == $hook_suffix || 'term.php' == $hook_suffix) {
        wp_enqueue_media();
        wp_enqueue_script( 'cawp-category-grid-thumbnail', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ) );
    }
}
add_action( 'admin_enqueue_scripts', 'cawpcategory_grid_enqueue_media_scripts' );


?>