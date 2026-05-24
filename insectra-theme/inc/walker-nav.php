<?php
/**
 * Custom nav walker that adds caret to dropdowns.
 *
 * @package Insectra
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class Insectra_Walker_Nav extends Walker_Nav_Menu {
    function start_lvl( &$output, $depth = 0, $args = null ) {
        $output .= '<ul class="sub-menu">';
    }
    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $has_children = in_array( 'menu-item-has-children', $classes, true );
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $output .= '<li class="' . esc_attr( $class_names ) . ( $has_children ? ' has-children' : '' ) . '">';

        $atts = array(
            'href'   => ! empty( $item->url ) ? $item->url : '#',
            'target' => $item->target,
            'rel'    => $item->xfn,
        );
        $attr_str = '';
        foreach ( $atts as $k => $v ) {
            if ( $v ) { $attr_str .= ' ' . $k . '="' . esc_attr( $v ) . '"'; }
        }
        $title = apply_filters( 'the_title', $item->title, $item->ID );
        $output .= '<a' . $attr_str . '>' . $title;
        if ( $has_children && $depth === 0 ) {
            $output .= ' <i class="fa-solid fa-chevron-down caret"></i>';
        }
        $output .= '</a>';
    }
}
