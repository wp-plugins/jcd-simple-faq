<?php
   /*
   Plugin Name: JCD Simple FAQ
   Plugin URI: http:/www.jocoxdesign.co.uk/wordpress-plugins/jcd-simple-faq
   Description: Not everyone wants a fancy JQuery/Accordion FAQ system, so this simple plugin allows you to add as many questions and answers as you like and then display them straight on the page, via a short code, with minimal styling. Comes with 'Latest FAQ' widget.
   Version: 1.0
   Author: Jo Cox Design
   Author URI: http://www.jocoxdesign.co.uk
   License: GPL2
   */

add_action('init', function() {

	$labels = array(
		'name' => _x('FAQ', 'post type general name'),
		'singular_name' => _x('Item', 'post type singular name'),
		'add_new' => _x('Add New Item', 'Item'),
		'add_new_item' => __('Add New Item'),
		'edit_item' => __('Edit Item'),
		'new_item' => __('New Item'),
		'all_items' => __('All FAQ Items'),
		'view_item' => __('View Item'),
		'search_items' => __('Search FAQ'),
		'not_found' => __('No FAQ found'),
		'not_found_in_trash' => __('No FAQ found in Trash'),
		'parent_item_colon' => '',
		'menu_name' => 'FAQ'
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => true,
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title', 'editor', 'page-attributes', 'thumbnail')
	);
	register_post_type('FAQ', $args);
});

/* CREATE THE SHORTCODE */

add_shortcode('faq', function() {

	$posts = get_posts(array(
		'numberposts' => 9999,
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'post_type' => 'faq',
	));

	$faq  = '<div id="faq">';
	foreach ( $posts as $post ) { 
		$faq .= sprintf(('<h3 class="question">%1$s</h3><div class="answer">%2$s</div>'),
			$post->post_title,
			wpautop($post->post_content)
		);
	}
	$faq .= '</div>';

	return $faq;
});

/* CREATE THE WIDGET */

class LatestFAQ extends WP_Widget
{
  function LatestFAQ()
  {
    $widget_ops = array('classname' => 'LatestFAQ', 'description' => 'Display the latest FAQ from your JCD Simple FAQ plugin' );
    $this->WP_Widget('LatestFAQ', 'Latest FAQ', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
?>

<p>
  <label for="<?php echo $this->get_field_id('title'); ?>">Title:
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
  </label>
</p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
  
  function jcdfaq() {
		$posts = get_posts(array(
		'numberposts' => 1,
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'post_type' => 'faq',
	));
	$faq  = '<div id="faq">';
	foreach ( $posts as $post ) { 
		$faq .= sprintf(('<h4 class="question">%1$s</h3><div class="answer">%2$s</div>'),
			$post->post_title,
			wpautop($post->post_content)
		);
	}
	$faq .= '</div>';

echo $faq;
}
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;

    echo jcdfaq();
 
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("LatestFAQ");') );

function jcdfaq() {
		$posts = get_posts(array(
		'numberposts' => 1,
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'post_type' => 'faq',
	));
	$faq  = '<div id="faq">';
	foreach ( $posts as $post ) { 
		$faq .= sprintf(('<h4 class="question">%1$s</h3><div class="answer">%2$s</div>'),
			$post->post_title,
			wpautop($post->post_content)
		);
	}
	$faq .= '</div>';

echo $faq;
}

function widget_jcd_faq($args) {
extract($args);
echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
echo $before_title;?>
Latest FAQ<?php echo $after_title;
jcdfaq();
echo $after_widget;
}

?>