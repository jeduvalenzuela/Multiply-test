<?php
/*
 *  Author: The Fuzzy Fish | @thefuzzyfish
 *  URL: thefuzzyfish.com | @thefuzzyfish
 *  Custom functions, support, custom post types and more.
 */

/*------------------------------------*\
	External Modules/Files
\*------------------------------------*/

// Load any external files you have here

/*------------------------------------*\
	Theme Support
\*------------------------------------*/


//funcion para la descripcion de los servicios, se carga dinamicante pero el template es la pagina "servicio"
function dinamic_url()
{
		global $wp;
		$wp->add_query_var('c');

		add_rewrite_rule('servicio/(.*)','index.php?page_id=2448&c=$matches[1]','top');
}
add_action('init', 'dinamic_url', 10, 0);

function dinamic_url_ficha()
{
		global $wp;
		$wp->add_query_var('tf');

		add_rewrite_rule('tecnicos/(.*)','index.php?page_id=2806&tf=$matches[1]','top');
}
add_action('init', 'dinamic_url_ficha', 10, 0);

/*------------------------------------*\
	Functions
\*------------------------------------*/

function menu_principal_desktop()
{
	wp_nav_menu(
	array(
		'theme_location'  => 'header-desktop-menu',
		'menu'            => '',
		'container'       => '',
		'container_class' => '',
		'container_id'    => '',
		'menu_class'      => '',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul class="d-flex justify-content-between">%3$s</ul>',
		'depth'           => 0,
		'walker'          => ''
		)
	);
}

function menu_principal_mobile()
{
	wp_nav_menu(
	array(
		'theme_location'  => 'header-mobile-menu',
		'menu'            => '',
		'container'       => '',
		'container_class' => '',
		'container_id'    => '',
		'menu_class'      => '',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul class="primary mt-5 d-block d-md-none">%3$s</ul>',
		'depth'           => 0,
		'walker'          => ''
		)
	);
}

// TheFuzzyFish navigation
function menu_animado()
{
	wp_nav_menu(
	array(
		'theme_location'  => 'menu-animado',
		'menu'            => '',
		'container'       => '',
		'container_class' => '',
		'container_id'    => '',
		'menu_class'      => '',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul class="secundary mt-3">%3$s</ul>',
		'depth'           => 0,
		'walker'          => ''
		)
	);
}

// Load TheFuzzyFish scripts (header.php)
function thefuzzyfish_header_scripts()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {

    	wp_register_script('conditionizr', get_template_directory_uri() . '/js/lib/conditionizr-4.3.0.min.js', array(), '4.3.0'); // Conditionizr
        wp_enqueue_script('conditionizr');

        wp_register_script('modernizr', get_template_directory_uri() . '/js/lib/modernizr-2.7.1.min.js', array(), '2.7.1'); // Modernizr
        wp_enqueue_script('modernizr');

    }
}

// Register TheFuzzyFish Navigation
function register_thefuzzyfish_menu()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-desktop-menu' => __('Header Desktop Menu', 'thefuzzyfish'),
				'header-mobile-menu' => __('Header Mobile Menu', 'thefuzzyfish'),
        'menu-animado' => __('Menu Animado', 'thefuzzyfish'),
        'extra-menu' => __('Extra Menu', 'thefuzzyfish') // Extra Navigation if needed (duplicate as many as you need!)
    ));
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function thefuzzyfish_wp_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}

// Custom Excerpts
function thefuzzyfish_wp_index($length) // Create 20 Word Callback for Index page Excerpts, call using thefuzzyfish_wp_excerpt('thefuzzyfish__index');
{
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using thefuzzyfish__excerpt('thefuzzyfish__custom_post');
function thefuzzyfish_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts callback
function thefuzzyfish_excerpt($length_callback = '', $more_callback = '')
{
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . $output . '</p>';
    echo $output;
}

// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

// Remove 'text/css' from our enqueued stylesheet
function thefuzzyfish_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html )
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Custom Gravatar in Settings > Discussion
function thefuzzyfish_gravatar ($avatar_defaults)
{
    $myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
    $avatar_defaults[$myavatar] = "Custom Gravatar";
    return $avatar_defaults;
}

// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Custom Comments Callback
function thefuzzyfish_comments($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
    <!-- heads up: starting < for the html tag (li or div) in the next line: -->
    <<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<div class="comment-author vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['180'] ); ?>
	<?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
	</div>
<?php if ($comment->comment_approved == '0') : ?>
	<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
	<br />
<?php endif; ?>

	<div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
		<?php
			printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','' );
		?>
	</div>

	<?php comment_text() ?>

	<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php }


/*------------------------------------*\
	Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('init', 'thefuzzyfish_header_scripts'); // Add Custom Scripts to wp_head
add_action('init', 'register_thefuzzyfish_menu'); // Add TheFuzzyFish Menu
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'thefuzzyfish_wp_pagination'); // Add our TheFuzzyFish Pagination

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
// Add Filters
add_filter('avatar_defaults', 'thefuzzyfish_gravatar'); // Custom Gravatar in Settings > Discussion
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
// add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
//add_filter('style_loader_tag', 'thefuzzyfish_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

// Shortcodes
add_shortcode('thefuzzyfish_shortcode_demo', 'thefuzzyfish_shortcode_demo'); // You can place [thefuzzyfish_shortcode_demo] in Pages, Posts now.
add_shortcode('thefuzzyfish_shortcode_demo_2', 'thefuzzyfish_shortcode_demo_2'); // Place [thefuzzyfish_shortcode_demo_2] in Pages, Posts now.

// Shortcodes above would be nested like this -
// [thefuzzyfish_shortcode_demo] [thefuzzyfish_shortcode_demo_2] Here's the page title! [/thefuzzyfish_shortcode_demo_2] [/thefuzzyfish_shortcode_demo]

/*------------------------------------*\
	Custom Post Types
\*------------------------------------*/


/**
 * Add custom taxonomies
 *
 * Additional custom taxonomies can be defined here
 * http://codex.wordpress.org/Function_Reference/register_taxonomy
 */
if (!function_exists('add_custom_taxonomies')) {

  function add_custom_taxonomies() {
    // Add new "Locations" taxonomy to Posts
      register_taxonomy('location', array('post') , array(
        // Hierarchical taxonomy (like categories)
        'hierarchical' => true,
        // This array of options controls the labels displayed in the WordPress Admin UI
        'labels' => array(
          'name' => _x( 'Zonas', 'taxonomy general name' ),
          'singular_name' => _x( 'zona', 'taxonomy singular name' ),
          'search_items' =>  __( 'Buscar Zonas' ),
          'all_items' => __( 'Todas las Zonas' ),
          'parent_item' => __( 'Zona superior' ),
          'parent_item_colon' => __( 'Zona superior:' ),
          'edit_item' => __( 'Editar Zona' ),
          'update_item' => __( 'Actualizar Zona' ),
          'add_new_item' => __( 'Añadir Zona' ),
          'new_item_name' => __( 'Nuevo nombre de la Zona' ),
          'menu_name' => __( 'Zonas' ),
        ),
        // Control the slugs used for this taxonomy
        'rewrite' => array(
          'slug' => 'zonas', // This controls the base slug that will display before each term
          'with_front' => false, // Don't display the category base before "/locations/"
          'hierarchical' => true // This will allow URL's like "/locations/cordoba/"
        ),
      ));
    }
    add_action( 'init', 'add_custom_taxonomies', 0 );
}
 

/**
 * Add custom taxonomies
 *
 * Additional custom taxonomies can be defined here
 * http://codex.wordpress.org/Function_Reference/register_taxonomy
 */
if (!function_exists('add_custom_taxonomies_services')) {

  function add_custom_taxonomies_services() {
      register_taxonomy('service', array('post'), array(
        // Hierarchical taxonomy (like categories)
        'hierarchical' => true,
        // This array of options controls the labels displayed in the WordPress Admin UI
        'labels' => array(
          'name' => _x( 'Rubro', 'taxonomy general name' ),
          'singular_name' => _x( 'servicio', 'taxonomy singular name' ),
          'search_items' =>  __( 'Buscar Rubros' ),
          'all_items' => __( 'Todos los Rubros' ),
          'parent_item' => __( 'Rubro superior' ),
          'parent_item_colon' => __( 'Rubro superior:' ),
          'edit_item' => __( 'Editar Rubro' ),
          'update_item' => __( 'Actualizar Rubro' ),
          'add_new_item' => __( 'Añadir Rubro' ),
          'new_item_name' => __( 'Nuevo nombre del Rubro' ),
          'menu_name' => __( 'Rubros' ),

        ),
        // Control the slugs used for this taxonomy
        'rewrite' => array(
          'slug' => 'servicios', // This controls the base slug that will display before each term
          'with_front' => false, // Don't display the category base before "/professions/"
          'hierarchical' => true // This will allow URL's like "/professions/plumber/"
        ),
      ));
    }
    add_action( 'init', 'add_custom_taxonomies_services', 0 );
}
/**
 * Add Taxonomy custom field
 *
 * Additional custom taxonomies can be defined here
 * http://codex.wordpress.org/Function_Reference/register_taxonomy
 */
// Add term page
function rapihogar_taxonomy_add_new_meta_field_services() {
  // this will add the custom meta field to the add new term page
  ?>
  <div class="form-field">
    <label for="term_meta[plural_name]"><?php _e( 'Nombre Plural', 'rapihogar' ); ?></label>
    <input type="text" name="term_meta[plural_name]" id="term_meta[plural_name]" value="">
    <p class="description"><?php _e( 'Nombre en plural del servicio','rapihogar' ); ?></p>
  </div>
  <div class="form-field">
    <label for="term_meta[profession_icon]"><?php _e( 'Icono', 'rapihogar' ); ?></label>
    <input type="text" name="term_meta[profession_icon]" id="term_meta[profession_icon]" value="">
    <p class="description"><?php _e( 'Icono del servicio','rapihogar' ); ?></p>
  </div>
<?php
}
add_action( 'service_add_form_fields', 'rapihogar_taxonomy_add_new_meta_field_services', 10, 2 );
// Edit term page
function rapihogar_taxonomy_edit_meta_field_services($term) {

  // put the term ID into a variable
  $t_id = $term->term_id;

  // retrieve the existing value(s) for this meta field. This returns an array
  $term_meta = get_option( "taxonomy_$t_id" ); ?>
  <tr class="form-field">
  <th scope="row" valign="top"><label for="term_meta[plural_name]"><?php _e( 'Nombre Plural', 'rapihogar' ); ?></label></th>
    <td>
      <input type="text" name="term_meta[plural_name]" id="term_meta[plural_name]" value="<?php echo esc_attr( $term_meta['plural_name'] ) ? esc_attr( $term_meta['plural_name'] ) : ''; ?>">
      <p class="description"><?php _e( 'Ingresa un valor para el campo','rapihogar' ); ?></p>
    </td>
  </tr>
  <tr class="form-field">
  <th scope="row" valign="top"><label for="term_meta[profession_icon]"><?php _e( 'Icono', 'rapihogar' ); ?></label></th>
    <td>
      <input type="text" name="term_meta[profession_icon]" id="term_meta[profession_icon]" value="<?php echo esc_attr( $term_meta['profession_icon'] ) ? esc_attr( $term_meta['profession_icon'] ) : ''; ?>">
      <p class="description"><?php _e( 'Ingresa un valor para el campo','rapihogar' ); ?></p>
    </td>
  </tr>
<?php
}
add_action( 'service_edit_form_fields', 'rapihogar_taxonomy_edit_meta_field_services', 10, 2 );

// Save extra taxonomy fields callback function.
function save_taxonomy_custom_meta_service( $term_id ) {
  if ( isset( $_POST['term_meta'] ) ) {
    $t_id = $term_id;
    $term_meta = get_option( "taxonomy_$t_id" );
    $cat_keys = array_keys( $_POST['term_meta'] );
    foreach ( $cat_keys as $key ) {
      if ( isset ( $_POST['term_meta'][$key] ) ) {
        $term_meta[$key] = $_POST['term_meta'][$key];
      }
    }
    // Save the option array.
    update_option( "taxonomy_$t_id", $term_meta );
  }
}
add_action( 'edited_service', 'save_taxonomy_custom_meta_service', 10, 2 );
add_action( 'create_service', 'save_taxonomy_custom_meta_service', 10, 2 );

/*------------------------------------*\
	ShortCode Functions
\*------------------------------------*/

// Shortcode Demo with Nested Capability
function thefuzzyfish_shortcode_demo($atts, $content = null)
{
    return '<div class="shortcode-demo">' . do_shortcode($content) . '</div>'; // do_shortcode allows for nested Shortcodes
}

// Shortcode Demo with simple <h2> tag
function thefuzzyfish_shortcode_demo_2($atts, $content = null) // Demo Heading H2 shortcode, allows for nesting within above element. Fully expandable.
{
    return '<h2>' . $content . '</h2>';
}

//agregar pagina de optiones PLUGIN ACF
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page();
}



if (!function_exists('get_telefone')){
    function get_telefone($template=false){

        //TODO Se debe buscar el numero en las paginas de WP <?php the_field('phone_number')
        $id_ciudad = array(
            'cordoba-capital'=>'0810-345-6969',//'(0351) 5696969',
            'nacional'=>'0810-345-6969',
            'rosario'=>'0810-345-6969',//'(0341) 5288080',
            'mendoza'=>'0810-345-6969',//'0261-155992944',
            'capital-federal'=>'0810-345-6969',//'011-1559963708',
            //'la-plata'=>'(0221) 5177722'
        );

        $numero = $id_ciudad['nacional'];

        // if(isset($_SESSION['location_slug'])
        //     and array_key_exists($_SESSION['location_slug'], $id_ciudad)){
        //     $numero = $id_ciudad[$_SESSION['location_slug']];
        // }else{
        //     if(preg_match('/cordoba/',$_SERVER['REQUEST_URI'])){
        //         $numero = $id_ciudad['cordoba-capital'];
        //     }elseif(preg_match('/rosario/',$_SERVER['REQUEST_URI'])){
        //         $numero = $id_ciudad['rosario'];
        //     }elseif(preg_match('/la-plata/',$_SERVER['REQUEST_URI'])){
        //         $numero = $id_ciudad['nacional'];
        //     }
        // }

        if($template=='h1'){
            return render_telefone_h1($numero);
        }elseif($template=='h2'){
            return render_telefone_h2($numero);
		}elseif($template=='h3'){
            return render_telefone_h3($numero);
		}elseif($template=='a_white'){
            return render_telefone_a_white($numero);
        }elseif ($template=='href') {
        	return render_telefone_href($numero);
        }elseif ($template=='clean') {
        	return render_telefone_clean($numero);
        }
        return render_telefone($numero);
    }
	function render_telefone_href($numero)
    {
        return "tel:".str_replace(array('-',' ','(',')'),'',$numero);
    }
    function render_telefone_clean($numero)
    {
        return $numero;
    }
    function render_telefone($numero)
    {
        return "<a href=\"tel:".str_replace(array('-',' ','(',')'),'',$numero)."\" class=\"text__blue\">$numero</a>";
    }
    function render_telefone_a_white($numero)
    {
        return "<a href=\"tel:".str_replace(array('-',' ','(',')'),'',$numero)."\" class=\"txt-white\">$numero</a>";
    }
    function render_telefone_h1($numero)
    {
        return "<h1 class=\"tel txt-white\"><a href=\"tel:".str_replace(array('-',' ','(',')'),'',$numero)."\">$numero</a></h1>";
    }
	function render_telefone_h2($numero)
    {
        return "<a href=\"tel:".str_replace(array('-',' ','(',')'),'',$numero)."\" class=\"txt-white\"><h2 class=\"tel\">$numero</h2></a>";
    }
	function render_telefone_h3($numero)
    {
        return "<a href=\"tel:".str_replace(array('-',' ','(',')'),'',$numero)."\" class=\"txt-white\"><h3 class=\"tel\">$numero</h3></a>";
    }
}

if(!function_exists('get_rubros')){
    function get_rubros(){
        if (get_current_ciudad()) {
            return get_rubros_api(get_current_ciudad()->location_id);
        }else{
            return get_rubros_wp();
        }
    }
}

/**
 * get rubros desde la API
 */
if(!function_exists('get_rubros_api')){

    function get_rubros_api($location_id){

        $rubros_core = ApiClient::getClient()->rubroPorCiudad($location_id);

        $rubros_list = array();

        $args = array(
                    'post_type' => 'page',
                    'posts_per_page' => -1,
                    'meta_query' => array(
                        'relation' => 'AND',
                        array(
                            'key' => '_wp_page_template',
                            'value' => 'template-rubro-amp.php'
                        ),array(
                            'key' => 'show_on_web',
                            'value' => 1,
                        )
                    ),
                );
        $the_pages = new WP_Query( $args );
        $rubros_core_list = array();

        foreach ($rubros_core as $key => $r) {
            $rubros_core_list[$r['rubro_id']] =$r;
        }

        foreach ($rubros_core_list as $key => $value) {

            $exists=false;

            while( $the_pages->have_posts() ){
                $the_pages->the_post();
                $_term_location = get_the_terms(get_the_ID(),'location');
                $_term_service = get_the_terms(get_the_ID() ,'service');
                if ($_term_location[0]->description == $location_id and $_term_service[0]->description == $value['rubro_id']) {
                    $exists = true;
                    $r = $rubros_core_list[$_term_service[0]->description];
                    $rubros_list[] = [
                        'core_id' => $r['rubro_id'],
                        'link' =>  get_permalink(),
                        'title' => $r['rubro_titulo'],
                        'name' => get_the_title(),
                        'term_order' => $_term_service[0]->term_order
                    ];
                } 
            }

            if( $exists == false ) {
                $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";
                $rubros_list[] = [
                    'core_id' => $value['rubro_id'],
                    'link' =>  $url.$value['url_slug'],
                    'title' => $value['rubro_titulo'],
                    'name' => $value['rubro_titulo'],
                    'term_order' => 'prueba'
                ];
            }

        }

        

        // while( $the_pages->have_posts() ){

        //     $the_pages->the_post();
        //     $_term_location = get_the_terms(get_the_ID(),'location');
        //     $_term_service = get_the_terms(get_the_ID() ,'service');

        //     if ($_term_location[0]->description == $location_id and array_key_exists($_term_service[0]->description, $rubros_core_list)) {
        //         $r = $rubros_core_list[$_term_service[0]->description];
        //         $rubros_list[] = [
        //             'core_id' => $r['id'],
        //             'link' =>  get_permalink(),
        //             'title' => $r['name'],
        //             'name' => get_the_title(),
		// 			'term_order' => $_term_service[0]->term_order
        //         ];
        //     } 
        // }

        //var_dump($rubros_list);


        usort($rubros_list, function($a, $b){
			return (int)$a['term_order'] >= (int)$b['term_order'];
			//return strcmp($a['term_order'], $b['term_order']);
        });

		Log::add($rubros_list);

		return $rubros_list;
    }
}

/**
 * get rubros desde las paginas de wordpress
 */
if(!function_exists('get_rubros_wp')){

    function get_rubros_wp()
    {
		Log::add('get_rubros_wp');
        $rubros_list = array();

        if (isset($_SESSION['location_slug'])) {

            $terms = get_terms('service', array(
                    'hide_empty' => false,
                )
            );


            foreach ($terms as $term) {
                $args = array(
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'orderby' => 'title',
                    'order' => 'ASC',
                    'nopaging' => true,
                    'posts_per_page' => 1,
                    'meta_query' => array(
                        array(
                            'key' => 'show_on_web',
                            'value' => 1,
                        )
                    ),
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'location',
                            'field' => 'slug',
                            'terms' => $_SESSION['location_slug'],
                        ),
                        array(
                            'taxonomy' => 'service',
                            'field' => 'slug',
                            'terms' => $term->slug,
                        ),
                    ),
                );

                query_posts($args);

                if (have_posts()) {

                    while (have_posts()) {
                        the_post();
                            $rubros_list[] = [
                                'link' => get_permalink(),
                                'name' => $term->name
                            ];
                    }
                }
                wp_reset_query();
            }

            //SI NO HAY CIUDAD EN SESION
        } else {

            $args = array(
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_parent' => 879,
                'nopaging' => true,
                'posts_per_page' => 100,
                'orderby' => 'title',
                'order' => 'ASC',
            );

            $query = new WP_Query($args);

            if ($query->have_posts()) {

                while ($query->have_posts()) {
                    $query->the_post();
                    $term = get_field('rubro');
                    $rubros_list[] = [
                        'link' => get_permalink(),
                        'name' => $term->name
                    ];
                    wp_reset_postdata();
                }
            }
        }

        return $rubros_list;
    }


}


/**
 * get rubros desde las paginas de wordpress
 */
if(!function_exists('get_current_ciudad')){

  function get_current_ciudad()
  {

    $ciudad = false;
    if(isset($_SESSION['location_id']) and isset($_SESSION['location_slug']) and isset($_SESSION['location_name'])){
      $ciudad = (object)[
        'location_id'   => $_SESSION['location_id'],
        'location_slug' => $_SESSION['location_slug'],
        'location_name' => $_SESSION['location_name']
      ];

    }
    return $ciudad;
  }

  function set_current_ciudad($location_id=false, $location_slug=false)
  {
    if($location_id and $location_slug){ // tengo todos los parametro
      $_SESSION['location_id'] = $location_id;
      $_SESSION['location_slug'] = $location_slug;
    }elseif ($location_slug) {
      $response = ApiClient::getClient()->cityWithType();
      //Log::add($response);
      foreach ($response['data'] as $r) {
        if ($r['url_slug'] == $location_slug) {
            //Log::add($location_slug);
            //Log::add($r);
            $_SESSION['location_slug'] = $r['url_slug'];
            $_SESSION['location_id']   = $r['id'];
            $_SESSION['location_name'] = $r['name'];
          }
        }
      }elseif($location_id){
        $response = ApiClient::getClient()->cityWithType();
        Log::add($response);
        foreach ($response['data'] as $r) {
          if ($r['id'] == $location_id) {
              Log::add($location_slug);
              Log::add($r);
              $_SESSION['location_slug'] = $r['url_slug'];
              $_SESSION['location_id']   = $r['id'];
              $_SESSION['location_name'] = $r['name'];

          }
      }
      add_action('wp_footer', 'add_set_current_ciudad_script');
    }

    return get_current_ciudad();

  }

  function add_set_current_ciudad_script(){ ?>
<script type="text/javascript">
    $.get('/cache.php',{set_ciudad: <?php echo get_current_ciudad()->location_id; ?>});
</script>
<?php }


}

if(!function_exists('add_zopim_scrript')){

  function add_zopim_scrript()
  {
/*
    ?>
<!--Start of Zendesk Chat Script-->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
$.src="https://v2.zopim.com/?5pUBKz4rWKCEEwKDXknRXtTQpliMPzcX";z.t=+new Date;$.
type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
</script>
<!--End of Zendesk Chat Script-->

    <?php */
  }
  //add_action('wp_footer', 'add_zopim_scrript');
}


function get_rating_html(){
  return '<div class="rating">
      <label>
        <input type="radio" name="stars" value="1" />
        <span class="icon">★</span>
      </label>
      <label>
        <input type="radio" name="stars" value="2" />
        <span class="icon">★</span>
        <span class="icon">★</span>
      </label>
      <label>
        <input type="radio" name="stars" value="3" />
        <span class="icon">★</span>
        <span class="icon">★</span>
        <span class="icon">★</span>
      </label>
      <label>
        <input type="radio" name="stars" value="4" />
        <span class="icon">★</span>
        <span class="icon">★</span>
        <span class="icon">★</span>
        <span class="icon">★</span>
      </label>
      <label>
        <input type="radio" name="stars" value="5" />
        <span class="icon">★</span>
        <span class="icon">★</span>
        <span class="icon">★</span>
        <span class="icon">★</span>
        <span class="icon">★</span>
        </label>';
}

function array_to_object($x) {
    if (!is_array($x)) {
        return $x;
    } elseif (is_numeric(key($x))) {
        return array_map(__FUNCTION__, $x);
    } else {
        return (object) array_map(__FUNCTION__, $x);
    }
}

function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
} 

function checkInWP($city_id, $type_id){
    $query="select wp_posts.ID, post_name
    from wp_posts 
    left join wp_term_relationships on wp_term_relationships.object_id = wp_posts.id
    where 
        wp_term_relationships.term_taxonomy_id IN (
        SELECT term_taxonomy_id FROM wp_term_taxonomy where (taxonomy='location' and description='".$city_id."') or (taxonomy='service' and description='".$type_id."')
    ) AND wp_posts.post_type = 'page' AND ((wp_posts.post_status = 'publish')) 
    
    GROUP BY wp_posts.ID 
    having count(*) = 2
    LIMIT 1";
    global $wpdb;
    $results = $wpdb->get_results($query);
    if(isset($results[0]->post_name)){
        return $results[0]->post_name;
    } else {
        return "";
    }    
}

function ampify($html='') {

    # Replace img, audio, and video elements with amp custom elements
    $html = str_ireplace(
        ['<img','<video','/video>','<audio','/audio>'],
        ['<amp-img','<amp-video','/amp-video>','<amp-audio','/amp-audio>'],
        $html
    );

    # Add closing tags to amp-img custom element
    $html = preg_replace('/<amp-img(.*?)\/?>/', '<amp-img$1></amp-img>',$html);

    # Whitelist of HTML tags allowed by AMP
    $html = strip_tags($html,'<h1><h2><h3><h4><h5><h6><a><p><ul><ol><li><blockquote><q><cite><ins><del><strong><em><code><pre><svg><table><thead><tbody><tfoot><th><tr><td><dl><dt><dd><article><section><header><footer><aside><figure><time><abbr><div><span><hr><small><br><amp-img><amp-audio><amp-video><amp-ad><amp-anim><amp-carousel><amp-fit-rext><amp-image-lightbox><amp-instagram><amp-lightbox><amp-twitter><amp-youtube>');

    return $html;

}


if(!function_exists('print_schema_corporation')){
	function print_schema_corporation(){
		return <<<EOF
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Corporation",
  "name": "Rapihogar",
  "alternateName": "Rapihogar",
  "url": "https://www.rapihogar.com.ar",
  "logo": "https://www.rapihogar.com.ar/wp-content/themes/rapihogarV3/img/svg/isorapihogar.svg",
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+54 0810 345 6969",
    "contactType": "sales",
    "contactOption": ["TollFree","HearingImpairedSupported"],
    "areaServed": "AR",
    "availableLanguage": "es"
  },
  "sameAs": [
    "https://www.facebook.com/RapihogarOficial",
    "https://www.instagram.com/rapihogar.expertos/",
    "https://www.linkedin.com/company/rapihogar/"
  ]
}
</script>
EOF;
	}
}

require_once(dirname(__file__).'/class-rapi_seo.php');

$GLOBALS['IS_AMP_PAGE'] = false;
if(!function_exists('init_amp_page')){
	function init_amp_page(){
		$GLOBALS['IS_AMP_PAGE'] = true;

		remove_action( 'wp_head', 'rest_output_link_wp_head', 10);
		remove_action( 'wp_head', 'wp_oembed_add_host_js', 10);
		remove_action( 'wp_head',  'wp_post_preview_js', 1);
		remove_action( 'wp_head', 'thefuzzyfish_header_scripts', 10);
		remove_action( 'wp_head', 'my_remove_recent_comments_style', 10);
		remove_action( 'wp_head', 'customize_preview_loading_style', 10);
		remove_action( 'wp_head', 'recent_comments_style', 10);
		remove_action( 'wp_head', 'wp_enqueue_scripts', 10);
		remove_action( 'wp_head', 'print_emoji_detection_script', 7);
		remove_action( 'wp_head', 'wp_print_head_scripts', 9);
		remove_action( 'wp_head', 'print_emoji_detection_script', 7);
		remove_action( 'wp_head', 'twentyseventeen_javascript_detection', 7);
		remove_action( 'wp_head', 'wpmu_signup_stylesheet');
		remove_action( 'admin_print_styles', 'print_emoji_styles', 7);

		remove_action( 'wp_head', 'add_inline_style');
		remove_action( 'option_home', '_config_wp_home');
		remove_action( 'wp_head', '_config_wp_home');
		remove_action( 'wp_head', 'yoast_wpseo_social_options');
		remove_action( 'wp_head', 'locale_stylesheet');
		remove_action( 'wp_head', 'wp_print_styles'); // Remove 'text/css' from enqueued stylesheet

		//remove_action( 'wp_head', array(new WPSEO_Option_Social(), 'get_option') );

		remove_all_filters('wp_print_scripts');
		remove_all_filters('wp_print_styles');
		remove_all_filters('style_loader_src');
		remove_all_filters('script_loader_src');
		remove_all_filters('wp_print_footer_scripts');
		remove_all_filters('shutdown');
		remove_all_filters('wp_default_styles');
		remove_all_filters('bodhi-svgs-attachment');

		remove_all_filters('wpseo_json_ld');

		remove_all_filters('wpseo_social');

		remove_all_filters('embed_head');
		remove_all_filters('custom-header');
		remove_all_filters('bodhi-svgs-attachment');
		remove_all_filters('bodhi-svgs-attachment');



	}

}

//** *Enable upload for webp image files.*/
function webp_upload_mimes($existing_mimes) {
    $existing_mimes['webp'] = 'image/webp';
    return $existing_mimes;
}
add_filter('mime_types', 'webp_upload_mimes');

//** * Enable preview / thumbnail for webp image files.*/
function webp_is_displayable($result, $path) {
    if ($result === false) {
        $displayable_image_types = array( IMAGETYPE_WEBP );
        $info = @getimagesize( $path );

        if (empty($info)) {
            $result = false;
        } elseif (!in_array($info[2], $displayable_image_types)) {
            $result = false;
        } else {
            $result = true;
        }
    }

    return $result;
}
add_filter('file_is_displayable_image', 'webp_is_displayable', 10, 2);


//Redirigir E404 a home
function redirect_404s() {
    if(is_404()) {
        wp_redirect(home_url(), '301');
    }
}
add_action('wp_enqueue_scripts', 'redirect_404s');
