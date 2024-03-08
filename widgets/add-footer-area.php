<?php

function add_limbiate_footer_widgets()
{
    register_sidebar([
        'name'          => esc_html__('Limbiate Footer COL 1', 'text_domain'),
        'id'            => 'limbiate-footer-area-1',
        'description'   => 'Aggiungi widgets che appariranno nella colonna 1 del footer',
        'before_widget' => '<div id="%1$s" class="widget %2$s limbiate-footer-widget limbiate-footer-col1">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="footer-heading-title">',
        'after_title'   => '</h3>',
    ]);
    register_sidebar([
        'name'          => esc_html__('Limbiate Footer COL 2', 'text_domain'),
        'id'            => 'limbiate-footer-area-2',
        'description'   => 'Aggiungi widgets che appariranno nella colonna 2 del footer',
        'before_widget' => '<div id="%1$s" class="widget %2$s limbiate-footer-widget limbiate-footer-col2">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="footer-heading-title">',
        'after_title'   => '</h3>',
    ]);
    register_sidebar([
        'name'          => esc_html__('Limbiate Footer COL 3', 'text_domain'),
        'id'            => 'limbiate-footer-area-3',
        'description'   => 'Aggiungi widgets che appariranno nella colonna 3 del footer',
        'before_widget' => '<div id="%1$s" class="widget %2$s limbiate-footer-widget limbiate-footer-col3">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="footer-heading-title">',
        'after_title'   => '</h3>',
    ]);
}
add_action('widgets_init', 'add_limbiate_footer_widgets');
