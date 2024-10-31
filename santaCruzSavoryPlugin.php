<?php

/*
 * Plugin Name:       Shop Ingredients Button
 * Plugin URI:        https://santacruzsavory.com/
 * Description:       Inserts a "Shop Ingredients" button on your website next to each recipe's ingredient list.
 * Version:           0.5.1
 * Requires at least: 4.4
 * Requires PHP:      5.4
 * Author:            Santa Cruz Savory
 * Author URI:        https://santacruzsavory.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */


// Load jQuery
wp_enqueue_script('jquery');


// Load our CSS 
$cssUrl = 'https://assets.santacruzsavory.com/style.css?t=' . date('mdH');
wp_enqueue_style('scs-style', $cssUrl, array(), null);

function add_options_to_css() {

    $scs_color_scheme = get_option('santacruzsavory_color_scheme');
    $scs_font_family = get_option('santacruzsavory_font_family');

    $scs_custom_css = ':root { ';
    if(!empty( $scs_color_scheme )) {
        $scs_custom_css.= '--scsColorScheme: ';
        $scs_custom_css.= $scs_color_scheme;
        $scs_custom_css.= '; ';
    }
    if(!empty( $scs_font_family )) {
        $scs_custom_css.= '--scsFontFamily: ';
        $scs_custom_css.= $scs_font_family;
        $scs_custom_css.= '; ';
    }
    $scs_custom_css.= '}';
    wp_add_inline_style('scs-style', $scs_custom_css);
}

add_action('wp_enqueue_scripts', 'add_options_to_css');



// Load our Javascript
$jsUrl = 'https://assets.santacruzsavory.com/script.js?t=' . date('mdH');
wp_enqueue_script('scs-script', $jsUrl, array(), null, true);

//Get any wordpress settings to load to JS
$scs_params = array(
    'affiliateId' => get_option('santacruzsavory_instacart_affiliate_id')
);
wp_localize_script('scs-script', 'scsParams', $scs_params );


// Register plugin settings options
function santacruzsavory_register_settings() {
    // color scheme
    register_setting(
        'santacruzsavory_settings',
        'santacruzsavory_color_scheme',
        array(
            'type' => 'string',
            'default' => '#e16653',
        )
    );
    // font family
    register_setting(
        'santacruzsavory_settings',
        'santacruzsavory_font_family',
        array(
            'type' => 'string',
            'default' => 'Josefin Sans, sans-serif',
        )
    );
    //affiliate id
    register_setting(
        'santacruzsavory_settings',
        'santacruzsavory_instacart_affiliate_id',
        array(
            'type' => 'string',
            'default' => '',
        )
    );
}
add_action('admin_init', 'santacruzsavory_register_settings');


// Add settings page
function santacruzsavory_settings_page() {
    add_submenu_page(
        'options-general.php',
        'SantaCruzSavory Settings',
        'SantaCruzSavory',
        'manage_options',
        'SantaCruzSavory',
        'santacruzsavory_render_settings_page_html' // callback function to render HTML
    );
}
add_action('admin_menu', 'santacruzsavory_settings_page');


// Render HTML for settings page
function santacruzsavory_render_settings_page_html() {
    ?>
    <div class="wrap">
        <h1>SantaCruzSavory Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('santacruzsavory_settings'); ?>
            <p>
                Here you can configure the <i>Shop Ingredients</i> button to match the look and feel of your website.
            </p>
            <table class="form-table">
                <tr>
                    <th>
                        <label for="santacruzsavory_color_scheme">Color scheme</label>
                    </th>
                    <td>
                        <input type='text' class="regular-text" id="santacruzsavory_color_scheme" name="santacruzsavory_color_scheme" value="<?php echo get_option('santacruzsavory_color_scheme'); ?>">
                        <p class="description" id="tagline-description">The color of the button. Enter a hexadecimal value such "#e16653".</p>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="santacruzsavory_font_family">Font family</label>
                    </th>
                    <td>
                        <input type='text' class="regular-text" id="santacruzsavory_font_family" name="santacruzsavory_font_family" value="<?php echo get_option('santacruzsavory_font_family'); ?>">
                        <p class="description" id="tagline-description">The font-family for the button and the modal. Enter a font-family such as "Arial".</p>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="santacruzsavory_instacart_affiliate_id">Instacart Affiliate ID</label>
                    </th>
                    <td>
                        <input type='text' class="regular-text" id="santacruzsavory_instacart_affiliate_id" name="santacruzsavory_instacart_affiliate_id" value="<?php echo get_option('santacruzsavory_instacart_affiliate_id'); ?>">
                        <p class="description" id="tagline-description">Enter your Instacart affiliate ID here.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php 
}