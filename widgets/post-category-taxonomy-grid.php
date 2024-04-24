<?php
use Elementor\Controls_Manager;
use Elementor\Widget_Base;

class CAWP_Post_Category_Taxonomy_Grid extends Widget_Base {

    public function get_name() {
        return 'cawp_post_category_taxonomy_grid';
    }

    public function get_title() {
        return 'Post Category Taxonomy Grid';
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    public function get_style_depends() {
        return [ 'cawp-category-grid' ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Content', 'post-category-taxonomy-grid' ),
            ]
        );

        $this->add_control(
            'taxonomy',
            [
                'label' => __( 'Choose Taxonomy', 'post-category-taxonomy-grid' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'category'  =>  __( 'Post Categories', 'post-category-taxonomy-grid' ),
                    'post_tag'  =>  __( 'Post Tags', 'post-category-taxonomy-grid' ),
                )
            ]
        );

        $this->add_control(
            'exclude_terms',
            [
                'label' => __( 'Exclude Terms', 'post-category-taxonomy-grid' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'Enter term IDs separated by commas', 'post-category-taxonomy-grid' ),
            ]
        );

        $this->add_control(
            'order_by',
            [
                'label' => __( 'Order By', 'post-category-taxonomy-grid' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'name' => __( 'Name', 'post-category-taxonomy-grid' ),
                    'count' => __( 'Count', 'post-category-taxonomy-grid' ),
                    // Add more orderby options here if needed
                ],
                'default' => 'name',
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __( 'Order', 'post-category-taxonomy-grid' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'ASC' => __( 'Ascending', 'post-category-taxonomy-grid' ),
                    'DESC' => __( 'Descending', 'post-category-taxonomy-grid' ),
                ],
                'default' => 'ASC',
            ]
        );

        $this->add_control(
            'grid_columns',
            [
                'label' => __( 'Grid Columns', 'post-category-taxonomy-grid' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12',
                ],
                'default' => '3',
            ]
        );

        $this->end_controls_section();

    }

    function cawp_get_taxonomies_list() {
        $taxonomies = get_taxonomies( [
            'public'   => true,
            '_builtin' => true,
        ], 'objects', 'or' );

        $taxonomy_options = [];

        foreach ( $taxonomies as $taxonomy ) {
            $taxonomy_options[ $taxonomy->name ] = $taxonomy->label;
        }

        return $taxonomy_options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $query_args = [
            'taxonomy' => $settings['taxonomy'],
            'exclude' => $settings['exclude_terms'] ? explode( ',', $settings['exclude_terms'] ) : [],
            'orderby' => $settings['order_by'],
            'order' => $settings['order'],
            'hide_empty' => false,
        ];

        $terms = get_terms( $query_args );

        if ( ! empty( $terms ) ) {
            echo '<div class="cawp-post-category-wrapper">';
            $column_class =  $settings['grid_columns'];

            foreach ( $terms as $term ) {
                $thumbnail_id = get_term_meta( $term->term_id, 'pcge_thumbnail_id', true );
                $thumbnail_url = $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'thumbnail' ) : '';
                $term_link = get_term_link( $term );
                if ( ! is_wp_error( $term_link ) ) {
                    echo '<div class="cawp-col-' . esc_attr( $column_class ) . '">';
                        echo '<div class="pcge-wrapper pcge-style-1">';
                            if ( $thumbnail_url ) {
                                echo '<img src="' . esc_url( $thumbnail_url ) . '" alt="' . esc_attr( $term->name ) . '">';
                            }
                            echo '<div class="caption"><h3>' . esc_html( $term->name ) . '</h3></div>';
                            echo '<a href="' . esc_url( $term_link ) . '"></a>';
                        echo '</div>';
                    echo '</div>';
                }
            }
            echo '</div>';
        }
    }
}
?>