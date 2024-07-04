<?php
/**
 * Nav Menu API: Main_Menu_Walker class
 *
 * @package WordPress
 * @subpackage Nav_Menus
 * @since 4.6.0
 */

/**
 * Custom class used to implement an HTML list of nav menu items.
 *
 * @since 3.0.0
 *
 * @see Walker
 */

 class Main_Menu_Walker extends Walker_Nav_Menu {
    // Start level
    function start_lvl(&$output, $depth = 0, $args = array()) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);
        $classes = array('dropdown-menu');
        $class_names = join(' ', apply_filters('nav_menu_submenu_css_class', $classes, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        $output .= "{$n}{$indent}<ul$class_names>{$n}";
    }

    // Start element
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = ($depth) ? str_repeat($t, $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'nav-item';
        if (in_array('menu-item-has-children', $classes)) {
            $classes[] = 'dropdown';
        }

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names .'>';

        $atts = array();
        $atts['title'] = ! empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = ! empty($item->target) ? $item->target : '';
        $atts['rel'] = ! empty($item->xfn) ? $item->xfn : '';
        $atts['href'] = ! empty($item->url) ? $item->url : '';

        $group = $args->current_group == 'documenti-e-dati' ? 'amministrazione' : $args->current_group;
        $active_class = ($item->attr_title == $group) ? 'active' : '';

        $data_element = '';
        if ($item->title == 'Amministrazione') $data_element .= 'management'; 
        if ($item->title == 'Novità') $data_element .= 'news'; 
        if ($item->title == 'Servizi') $data_element .= 'all-services'; 
        if ($item->title == 'Vivere il Comune') $data_element .= 'live';

        if ($depth == 0 && in_array('menu-item-has-children', $classes)) {
            $atts['class'] = 'nav-link dropdown-toggle ' . $active_class;
            $atts['data-bs-toggle'] = 'dropdown';
            $atts['aria-haspopup'] = 'true';
            $atts['aria-expanded'] = 'false';
        } else {
            $atts['class'] = 'nav-link ' . $active_class;
        }
        
        $atts['data-element'] = $data_element;

        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $title = apply_filters('the_title', $item->title, $item->ID);
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . $title . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    // End element
    function end_el(&$output, $item, $depth = 0, $args = array()) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $output .= "</li>{$n}";
    }

    // End level
    function end_lvl(&$output, $depth = 0, $args = array()) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);
        $output .= "$indent</ul>{$n}";
    }
}

// class Main_Menu_Walker extends Walker_Nav_Menu {
// 	function start_el(&$output, $item, $depth=0, $args=[], $id=0) {
// 		$output .= "<li class='nav-item'>";
// 		// set active tab
// 		$group = $args->current_group == 'documenti-e-dati' ? 'amministrazione' : $args->current_group;
// 		$active_class = '';
// 		if ($item->attr_title == $group) {
// 			$active_class = 'active';
// 		}

// 		// set data-element for crawler
// 		$data_element = '';
// 		if ($item->title == 'Amministrazione') $data_element .= 'management'; 
// 		if ($item->title == 'Novità') $data_element .= 'news'; 
// 		if ($item->title == 'Servizi') $data_element .= 'all-services'; 
// 		if ($item->title == 'Vivere il Comune') $data_element .= 'live'; 
 
// 		if ($item->url && $item->url != '#') {
// 			$output .= '<a class="nav-link '.$active_class.'" href="' . $item->url . '" data-element="'.$data_element.'">';
// 		} else {
// 			$output .= '<span>';
// 		}
 
// 		$output .= $item->title;
 
// 		if ($item->url && $item->url != '#') {
// 			$output .= '</a>';
// 		} else {
// 			$output .= '</span>';
// 		}
// 	}
// }