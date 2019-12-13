<?php
/**
 * Celtic Elements headless theme
 * Based on: https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

/**
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 */
$composer_autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($composer_autoload)) {
    require_once $composer_autoload;
    $timber = new Timber\Timber();
}

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if (!class_exists('Timber')) {

    add_action(
        'admin_notices',
        function () {
            echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url(admin_url('plugins.php#timber')) . '">' . esc_url(admin_url('plugins.php')) . '</a></p></div>';
        }
    );

    add_filter(
        'template_include',
        function ($template) {
            return get_stylesheet_directory() . '/static/no-timber.html';
        }
    );
    return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array('templates', 'views');

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;

/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site
{
    /** Add timber support. */
    public function __construct()
    {
        add_action('after_setup_theme', array($this, 'theme_supports'));
        add_filter('graphql_jwt_auth_secret_key', array($this, 'graphql_jwt'));
        add_filter('graphql_connection_max_query_amount', array($this, 'graphql_limit'), 10, 5);
        add_action('init', array($this, 'register_menus'));
        add_action('init', array($this, 'register_post_types')); #
        add_action('init', array($this, 'register_taxonomies'));
        parent::__construct();
    }
    /** This is where you set your JWT secret. */
    public function graphql_jwt()
    {
        return '5|=z5}]h*reW)yT&qNhT[C(>vTH[Q[Jx:K+:L2pILP-cxvhD@>A8:]l@s,je9yPM';
    }
    /** Increase the maximum number of results from 100 to 1000 */
    public function graphql_limit($amount, $source, $args, $context, $info)
    {
        $amount = 1000;
        return $amount;
    }
    public function register_menus()
    {
        register_nav_menus(array(
            'header' => 'Header Menu',
            'footer_one' => '1. Footer Menu',
            'footer_two' => '2. Footer Menu',
            'footer_three' => '3. Footer Menu',
        ));
    }
    /** This is where you can register custom post types. */
    public function register_post_types()
    {

    }
    /** This is where you can register custom taxonomies. */
    public function register_taxonomies()
    {

    }

    public function theme_supports()
    {
        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support(
            'html5',
            array(
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
            )
        );

        /*
         * Enable support for Post Formats.
         *
         * See: https://codex.wordpress.org/Post_Formats
         */
        add_theme_support(
            'post-formats',
            array(
                'aside',
                'image',
                'video',
                'quote',
                'link',
                'gallery',
                'audio',
            )
        );

        add_theme_support('menus');
    }
}

new StarterSite();
