<?php

function add_limbiate_footer_widget_area() {
    register_sidebar( array(
        'name'          => esc_html__( 'Limbiate Footer', 'text_domain' ),
        'id'            => 'limbiate-footer-area',
        'description'   => 'Aggiungi widgets che appariranno nel footer',
        'before_widget' => '<section id="%1$s" class="widget %2$s limbiate-footer-widget">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title limbiate-footer-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'add_limbiate_footer_widget_area' );