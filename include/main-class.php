<?php
/**
 * @link              https://pokamedia.com
 * @since             1.0.0
 * @package           Poka_PFXML
 * @author Thien Tran <thientran2359@gmail.com>
 * 
 */
// Block direct access to file
defined('ABSPATH') or die('Not Authorized!');

class Poka_Product_Feed_XML {

    protected static $instance;

    public static function init() {
        is_null(self::$instance) AND self::$instance = new self;
        return self::$instance;
    }

    public function __construct() {
        // Plugin uninstall hook
        register_uninstall_hook(POKA_PFX_FILE, array('Poka_Product_Feed_XML', 'plugin_uninstall'));

        // Plugin activation/deactivation hooks
        register_activation_hook(POKA_PFX_FILE, array($this, 'plugin_activate'));
        register_deactivation_hook(POKA_PFX_FILE, array($this, 'plugin_deactivate'));

        // Plugin Actions
        add_action('plugins_loaded', array($this, 'plugins_loaded'));
        add_action('after_setup_theme', array($this, 'after_setup_theme'), 999);

        add_action('init', array($this, 'plugin_product_feed_xml'));
        add_action('template_redirect', array($this, 'plugin_product_feed_xml'));
        add_filter('request', array($this, 'plugin_product_feed_xml'));
    }

    public static function plugin_uninstall() {
        flush_rewrite_rules();
    }

    /**
     * Plugin activation function
     * called when the plugin is activated
     * @method plugin_activate
     */
    public function plugin_activate() {
        flush_rewrite_rules();
    }

    /**
     * Plugin deactivate function
     * is called during plugin deactivation
     * @method plugin_deactivate
     */
    public function plugin_deactivate() {
        flush_rewrite_rules();
    }

    public function plugins_loaded($param) {
        load_plugin_textdomain("poka-product-feed-xml", false, dirname(POKA_PFX_DIRECTORY_BASENAME) . '/languages/');
    }

    public function plugin_product_feed_xml($vars = '') {
        $hook = current_filter();
        
        if($hook ==='template_redirect' && get_query_var('product-feed-xml')) {
            require_once( POKA_PFX_DIRECTORY . '/include/product-feed-xml.php' );
            exit;
        } elseif($hook ==='init') {
            add_rewrite_endpoint('product-feed-xml', EP_ROOT);
        } elseif($hook ==='request' && isset($vars['product-feed-xml']) && empty($vars['product-feed-xml'])) {
            $vars['product-feed-xml'] = 'default';
        }

        return $vars;
    }

    public function after_setup_theme() {
        add_action('product_cat_add_form_fields',  array($this, 'taxonomy_add_new_meta_field'), 10, 1);
        add_action('product_cat_edit_form_fields', array($this, 'taxonomy_edit_meta_field'), 10, 1);
        add_action('edited_product_cat',           array($this, 'save_taxonomy_custom_meta'), 10, 1);
        add_action('create_product_cat',           array($this, 'save_taxonomy_custom_meta'), 10, 1);
    }

    //Product Cat Create page
    function taxonomy_add_new_meta_field() {
        ?>
        <div class="form-field">
            <label for="poka_product_feed_xml_check"><?php _e('Meta TitlePoka Product Feed XML', 'poka-product-feed-xml'); ?></label>
            <input type="text" name="poka_product_feed_xml_check" id="poka_product_feed_xml_check">
            <p class="description"><?php _e('Show on Product Feed XML or not, Default: Uncheck - Not show', 'poka-product-feed-xml'); ?></p>
        </div>
        <?php
    }

    //Product Cat Edit page
    function taxonomy_edit_meta_field($term) {

        //getting term ID
        $term_id = $term->term_id;

        // retrieve the existing value(s) for this meta field.
        $poka_product_feed_xml_check = get_term_meta($term_id, 'poka_product_feed_xml_check', true);
        ?>
        <tr class="form-field">
            <th scope="row" valign="top"><strong><?php _e('Poka Product Feed XML', 'poka-product-feed-xml'); ?></strong></th>
            <td>
                <input type="checkbox" name="poka_product_feed_xml_check" id="poka_product_feed_xml_check" value="1" <?php echo esc_attr($poka_product_feed_xml_check) ? 'checked' : ''; ?>><label for="vehicle1"> <?php _e('Show on Product Feed XML', 'poka-product-feed-xml'); ?></label>
                <p class="description"><?php _e('Show on Product Feed XML or not, Default: Uncheck - Not show', 'poka-product-feed-xml'); ?></p>
            </td>
        </tr>
        <?php
    }

    // Save extra taxonomy fields callback function.
    function save_taxonomy_custom_meta($term_id) {
        $poka_product_feed_xml_check = $_REQUEST['poka_product_feed_xml_check'];
        $update = update_term_meta($term_id, 'poka_product_feed_xml_check', $poka_product_feed_xml_check);
    }

}

$poka_product_feed_xml = new Poka_Product_Feed_XML();
