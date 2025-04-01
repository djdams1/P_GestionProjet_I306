<?php

/**
 * file for holding dashboard welcome page for theme
 */
if (!function_exists('lavisto_is_plugin_installed')) {
    function lavisto_is_plugin_installed($plugin_slug)
    {
        $plugin_path = WP_PLUGIN_DIR . '/' . $plugin_slug;
        return file_exists($plugin_path);
    }
}
if (!function_exists('lavisto_is_plugin_activated')) {
    function lavisto_is_plugin_activated($plugin_slug)
    {
        return is_plugin_active($plugin_slug);
    }
}
if (!function_exists('lavisto_welcome_notice')) :
    function lavisto_welcome_notice()
    {
        if (get_option('lavisto_dismissed_custom_notice')) {
            return;
        }
        global $pagenow;
        $current_screen  = get_current_screen();

        if (is_admin()) {
            if ($current_screen->id !== 'dashboard' && $current_screen->id !== 'themes') {
                return;
            }
            if (is_network_admin()) {
                return;
            }
            if (!current_user_can('manage_options')) {
                return;
            }
            $theme = wp_get_theme();

            if (is_child_theme()) {
                $theme = wp_get_theme()->parent();
            }
            $lavisto_version = $theme->get('Version');


?>
            <div class="lavisto-admin-notice notice notice-info is-dismissible content-install-plugin theme-info-notice" id="lavisto-dismiss-notice">
                <div class="info-content">
                    <div class="notice-holder">
                        <h5><span class="theme-name"><span><?php echo __('Welcome to Lavisto', 'lavisto'); ?></span></h5>
                        <h1><?php echo __('Take your website building to next level with Cozy Blocks!', 'lavisto'); ?></h1>
                        </h3>
                        <div class="notice-text">
                            <p class="cozy-blocks-text"><?php echo __('Build website for any niche effortlessly with Cozy Blocks! Just install and activate to access over 30 advanced blocks, 200+ ready to use patterns, and a comprehensive library of 10+ starter templates. Start crafting your perfect website today!', 'lavisto') ?></p>
                            <p><?php echo __('Please install and activate all recommended to use blcoks and demo importer features- Cozy Addons, Cozy Essential Addons, Advanced Import.', 'lavisto') ?></p>
                        </div>
                        <a href="#" id="install-activate-button" class="button admin-button info-button"><?php echo __('Getting started with a single click', 'lavisto'); ?></a>
                        <a href="<?php echo admin_url(); ?>themes.php?page=about-lavisto" class="button admin-button info-button"><?php echo __('Explore Lavisto', 'lavisto'); ?></a>

                    </div>
                </div>
                <div class="theme-hero-screens">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/inc/admin/images/screen_plugin_ct.png'); ?>" />
                </div>

            </div>
    <?php
        }
    }
endif;
add_action('admin_notices', 'lavisto_welcome_notice');
function lavisto_dismissble_notice()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'lavisto_admin_nonce')) {
        wp_send_json_error(array('message' => 'Nonce verification failed.'));
        return;
    }

    $result = update_option('lavisto_dismissed_custom_notice', 1);

    if ($result) {
        wp_send_json_success();
    } else {
        wp_send_json_error(array('message' => 'Failed to update option'));
    }
}
add_action('wp_ajax_lavisto_dismissble_notice', 'lavisto_dismissble_notice');
// Hook into a custom action when the button is clicked
add_action('wp_ajax_lavisto_install_and_activate_plugins', 'lavisto_install_and_activate_plugins');
add_action('wp_ajax_nopriv_lavisto_install_and_activate_plugins', 'lavisto_install_and_activate_plugins');
add_action('wp_ajax_lavisto_rplugin_activation', 'lavisto_rplugin_activation');
add_action('wp_ajax_nopriv_lavisto_rplugin_activation', 'lavisto_rplugin_activation');

// Function to install and activate the plugins



function lavisto_check_plugin_installed_status($pugin_slug, $plugin_file)
{
    return file_exists(ABSPATH . 'wp-content/plugins/' . $pugin_slug . '/' . $plugin_file) ? true : false;
}

/* Check if plugin is activated */


function lavisto_check_plugin_active_status($pugin_slug, $plugin_file)
{
    return is_plugin_active($pugin_slug . '/' . $plugin_file) ? true : false;
}

require_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/misc.php');
require_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
function lavisto_install_and_activate_plugins()
{
    check_ajax_referer('lavisto_welcome_nonce', 'nonce');
    // Define the plugins to be installed and activated
    $recommended_plugins = array(
        array(
            'slug' => 'cozy-addons',
            'file' => 'cozy-addons.php',
            'name' => __('Cozy Addons', 'lavisto')
        ),
        array(
            'slug' => 'advanced-import',
            'file' => 'advanced-import.php',
            'name' => __('Advanced Imporrt', 'lavisto')
        ),
        array(
            'slug' => 'cozy-essential-addons',
            'file' => 'cozy-essential-addons.php',
            'name' => __('Cozy Essential Addons', 'lavisto')
        ),
        // Add more plugins here as needed
    );

    // Include the necessary WordPress functions


    // Set up a transient to store the installation progress
    set_transient('install_and_activate_progress', array(), MINUTE_IN_SECONDS * 10);

    // Loop through each plugin
    foreach ($recommended_plugins as $plugin) {
        $plugin_slug = $plugin['slug'];
        $plugin_file = $plugin['file'];
        $plugin_name = $plugin['name'];

        // Check if the plugin is active
        if (is_plugin_active($plugin_slug . '/' . $plugin_file)) {
            lavisto_update_install_and_activate_progress($plugin_name, 'Already Active');
            continue; // Skip to the next plugin
        }

        // Check if the plugin is installed but not active
        if (is_lavisto_plugin_installed($plugin_slug . '/' . $plugin_file)) {
            $activate = activate_plugin($plugin_slug . '/' . $plugin_file);
            if (is_wp_error($activate)) {
                lavisto_update_install_and_activate_progress($plugin_name, 'Error');
                continue; // Skip to the next plugin
            }
            lavisto_update_install_and_activate_progress($plugin_name, 'Activated');
            continue; // Skip to the next plugin
        }

        // Plugin is not installed or activated, proceed with installation
        lavisto_update_install_and_activate_progress($plugin_name, 'Installing');

        // Fetch plugin information
        $api = plugins_api('plugin_information', array(
            'slug' => $plugin_slug,
            'fields' => array('sections' => false),
        ));

        // Check if plugin information is fetched successfully
        if (is_wp_error($api)) {
            lavisto_update_install_and_activate_progress($plugin_name, 'Error');
            continue; // Skip to the next plugin
        }

        // Set up the plugin upgrader
        $upgrader = new Plugin_Upgrader();
        $install = $upgrader->install($api->download_link);

        // Check if installation is successful
        if ($install) {
            // Activate the plugin
            $activate = activate_plugin($plugin_slug . '/' . $plugin_file);

            // Check if activation is successful
            if (is_wp_error($activate)) {
                lavisto_update_install_and_activate_progress($plugin_name, 'Error');
                continue; // Skip to the next plugin
            }
            lavisto_update_install_and_activate_progress($plugin_name, 'Activated');
        } else {
            lavisto_update_install_and_activate_progress($plugin_name, 'Error');
        }
    }

    // Delete the progress transient
    $redirect_url = admin_url('themes.php?page=advanced-import');

    // Delete the progress transient
    delete_transient('install_and_activate_progress');
    // Return JSON response
    wp_send_json_success(array('redirect_url' => $redirect_url));
}

// Function to check if a plugin is installed but not active
function is_lavisto_plugin_installed($plugin_slug)
{
    $plugins = get_plugins();
    return isset($plugins[$plugin_slug]);
}

// Function to update the installation and activation progress
function lavisto_update_install_and_activate_progress($plugin_name, $status)
{
    $progress = get_transient('install_and_activate_progress');
    $progress[] = array(
        'plugin' => $plugin_name,
        'status' => $status,
    );
    set_transient('install_and_activate_progress', $progress, MINUTE_IN_SECONDS * 10);
}


function lavisto_dashboard_menu()
{
    add_theme_page(esc_html__('About Lavisto', 'lavisto'), esc_html__('About Lavisto', 'lavisto'), 'edit_theme_options', 'about-lavisto', 'lavisto_theme_info_display');
}
add_action('admin_menu', 'lavisto_dashboard_menu');
function lavisto_theme_info_display()
{ ?>
    <div class="dashboard-about-lavisto">
        <div class="lavisto-dashboard">
            <ul id="lavisto-dashboard-tabs-nav">
                <li><a href="#lavisto-welcome"><?php echo __('Get Started', 'lavisto') ?></a></li>
                <li><a href="#lavisto-setup"><?php echo __('Setup Instruction', 'lavisto') ?></a></li>
                <li><a href="#lavisto-comparision"><?php echo __('FREE VS PRO', 'lavisto') ?></a></li>
            </ul> <!-- END tabs-nav -->
            <div id="tabs-content">
                <div id="lavisto-welcome" class="tab-content">
                    <h1> <?php echo __('Welcome to the Lavisto - WordPress theme for Hotels and Hospitality', 'lavisto') ?></h1>
                    <p><?php echo __('Introducing "Lavisto": the ultimate WordPress theme for hotels and hospitality, perfect for hotels, resorts, and cafes. With full site editing capabilities, Lavisto offers limitless possibilities for creating stunning websites. It features 20+ pre-built sections and ready-to-use starter site demos, allowing you to launch your site in minutes and elevate your online presence effortlessly. Choose Lavisto for an exceptional website that captures the essence of hospitality and sophistication. Explore demos and more details about Mighty Builders - at https://cozythemes.com/lavisto-luxury-hotel-wordpress-theme/', 'lavisto') ?></p>
                    <h3><?php echo __('Required Plugins', 'lavisto'); ?></h3>
                    <p class="notice-text"><?php echo __('Please install and activate all recommended pluign to import the demo with "one click demo import" feature and unlock premium features!(for pro version)', 'lavisto') ?></p>
                    <ul class="lavisto-required-plugin">
                        <li>

                            <h4><?php echo __('1. Cozy Addons', 'lavisto'); ?>
                                <?php
                                if (lavisto_is_plugin_activated('cozy-addons/cozy-addons.php')) {
                                    echo __(': Plugin has been already activated!', 'lavisto');
                                } elseif (lavisto_is_plugin_installed('cozy-addons/cozy-addons.php')) {
                                    echo __(': Plugin does not activated, Activate the plugin to use one click demo import and unlock premium features.', 'lavisto');
                                } else {
                                    echo ': <a href="' . get_admin_url() . 'plugin-install.php?tab=plugin-information&plugin=cozy-addons&TB_iframe=true&width=600&height=550">' . esc_html__('Install and Activate', 'lavisto') . '</a>';
                                }
                                ?>
                            </h4>
                        </li>
                        <li>

                            <h4><?php echo __('2. Cozy Essential Addons', 'lavisto'); ?>
                                <?php
                                if (lavisto_is_plugin_activated('cozy-essential-addons/cozy-essential-addons.php')) {
                                    echo __(': Plugin has been already activated!', 'lavisto');
                                } elseif (lavisto_is_plugin_installed('cozy-essential-addons/cozy-essential-addons.php')) {
                                    echo __(': Plugin does not activated, Activate the plugin to use one click demo import and unlock premium features.', 'lavisto');
                                } else {
                                    echo ': <a href="' . get_admin_url() . 'plugin-install.php?tab=plugin-information&plugin=cozy-essential-addons&TB_iframe=true&width=600&height=550">' . esc_html__('Install and Activate', 'lavisto') . '</a>';
                                }
                                ?>
                            </h4>
                        </li>
                        <li>
                            <h4><?php echo __('3. Advanced Import - Need only to use "one click demo import" features', 'lavisto'); ?>
                                <?php
                                if (lavisto_is_plugin_activated('advanced-import/advanced-import.php')) {
                                    echo __(': Plugin has been already activated!', 'lavisto');
                                } elseif (lavisto_is_plugin_installed('advanced-import/advanced-import.php')) {
                                    echo __(': Plugin does not activated, Activate the plugin to use one click demo import feature.', 'lavisto');
                                } else {
                                    echo ': <a href="' . get_admin_url() . 'plugin-install.php?tab=plugin-information&plugin=advanced-import&TB_iframe=true&width=600&height=550">' . esc_html__('Install and Activate', 'lavisto') . '</a>';
                                }
                                ?>
                            </h4>
                        </li>
                    </ul>
                    <a href="#" id="install-activate-button" class="button admin-button info-button"><?php echo __('Getting started with a single click', 'lavisto'); ?></a>
                </div>
                <div id="lavisto-setup" class="tab-content">
                    <h3 class="lavisto-baisc-guideline-header"><?php echo __('Basic Theme Setup', 'lavisto') ?></h3>
                    <div class="lavisto-baisc-guideline">
                        <div class="featured-box">
                            <ul>
                                <li><strong><?php echo __('Setup Header Layout:', 'lavisto') ?></strong>
                                    <ul>
                                        <li> - <?php echo __('Go to Appearance -> Editor -> Patterns -> Template Parts -> Header:', 'lavisto') ?></li>
                                        <li> - <?php echo __('click on Header > Click on Edit (Icon) -> Add or Remove Requirend block/content as your requirement.:', 'lavisto') ?></li>
                                        <li> - <?php echo __('Click on save to update your layout', 'lavisto') ?></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="featured-box">
                            <ul>
                                <li><strong><?php echo __('Setup Footer Layout:', 'lavisto') ?></strong>
                                    <ul>
                                        <li> - <?php echo __('Go to Appearance -> Editor -> Patterns -> Template Parts -> Footer:', 'lavisto') ?></li>
                                        <li> - <?php echo __('click on Footer > Click on Edit (Icon) > Add or Remove Requirend block/content as your requirement.:', 'lavisto') ?></li>
                                        <li> - <?php echo __('Click on save to update your layout', 'lavisto') ?></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="featured-box">
                            <ul>
                                <li><strong><?php echo __('Setup Templates like Homepage/404/Search/Page/Single and more templates Layout:', 'lavisto') ?></strong>
                                    <ul>
                                        <li> - <?php echo __('Go to Appearance -> Editor -> Templates:', 'lavisto') ?></li>
                                        <li> - <?php echo __('click on Template(You need to edit/update) > Click on Edit (Icon) > Add or Remove Requirend block/content as your requirement.:', 'lavisto') ?></li>
                                        <li> - <?php echo __('Click on save to update your layout', 'lavisto') ?></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="featured-box">
                            <ul>
                                <li><strong><?php echo __('Restore/Reset Default Content layout of Template(Like: Frontpage/Blog/Archive etc.)', 'lavisto') ?></strong>
                                    <ul>
                                        <li> - <?php echo __('Go to Appearance -> Editor -> Templates:', 'lavisto') ?></li>
                                        <li> - <?php echo __('Click on Manage all Templates', 'lavisto') ?></li>
                                        <li> - <?php echo __('Click on 3 Dots icon at right side of respective Template', 'lavisto') ?></li>
                                        <li> - <?php echo __('Click on Clear Customization', 'lavisto') ?></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="featured-box">
                            <ul>
                                <li><strong><?php echo __('Restore/Reset Default Content layout of Template Parts(Header/Footer/Sidebar)', 'lavisto') ?></strong>
                                    <ul>
                                        <li> - <?php echo __('Go to Appearance -> Editor -> Patterns:', 'lavisto') ?></li>
                                        <li> - <?php echo __('Click on Manage All Template Parts', 'lavisto') ?></li>
                                        <li> - <?php echo __('Click on 3 Dots icon at right side of respective Template parts', 'lavisto') ?></li>
                                        <li> - <?php echo __('Click on Clear Customization', 'lavisto') ?></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="lavisto-comparision" class="tab-content">
                    <div class="featured-list">
                        <div class="half-col free-features">
                            <h3><?php echo __('Lavisto Features (Free)', 'lavisto') ?></h3>
                            <ul>
                                <li><strong> - <?php echo __('Lavisto offer wide range of  ready to use Home Sections Patterns', 'lavisto') ?></strong>
                                    <ul>
                                        <li><?php echo __('Hero/Banner Section', 'lavisto') ?></li>
                                        <li><?php echo __('About Us Section - 2', 'lavisto') ?></li>
                                        <li><?php echo __('Counter Section', 'lavisto') ?></li>
                                        <li><?php echo __('Enquiry Form Section', 'lavisto') ?></li>
                                        <li><?php echo __('FAQ Section', 'lavisto') ?></li>
                                        <li><?php echo __('Latest Post Section', 'lavisto') ?></li>
                                        <li><?php echo __('Photo Gallery Section', 'lavisto') ?></li>
                                        <li><?php echo __('Room Showcase Section', 'lavisto') ?></li>
                                        <li><?php echo __('Service/Amentities Section', 'lavisto') ?></li>
                                        <li><?php echo __('Local Tour & Activities Section', 'lavisto') ?></li>
                                        <li><?php echo __('Team Section', 'lavisto') ?></li>
                                        <li><?php echo __('Testimonial Section -2 ', 'lavisto') ?></li>
                                        <li><?php echo __('FAQ Section', 'lavisto') ?></li>
                                        <li><?php echo __('Featured Content Block Section', 'lavisto') ?></li>
                                        <li><?php echo __('About Us Page Layout', 'lavisto') ?></li>
                                        <li><?php echo __('Contact Us Page Section', 'lavisto') ?></li>
                                        <li><?php echo __('Services Page Section', 'lavisto') ?></li>
                                    </ul>
                                </li>

                                <li> <strong>- <?php echo __('FSE Templates Ready', 'lavisto') ?></strong>
                                    <ul>
                                        <li> <?php echo __('404 Template', 'lavisto') ?></li>
                                        <li> <?php echo __('Archive Template', 'lavisto') ?></li>
                                        <li> <?php echo __('Product Catalog Template', 'lavisto') ?></li>
                                        <li> <?php echo __('Blank Template', 'lavisto') ?></li>
                                        <li> <?php echo __('Front Page Template', 'lavisto') ?></li>
                                        <li> <?php echo __('Blog Home Template', 'lavisto') ?></li>
                                        <li> <?php echo __('Index Page Template', 'lavisto') ?></li>
                                        <li> <?php echo __('Search Template', 'lavisto') ?></li>
                                        <li> <?php echo __('Sitemap Template', 'lavisto') ?></li>
                                        <li> <?php echo __('Page Template', 'lavisto') ?></li>
                                        <li> <?php echo __('Left Sidebar Page Template', 'lavisto') ?></li>
                                        <li> <?php echo __('Full Width Page  Template', 'lavisto') ?></li>
                                        <li> <?php echo __('Single Template', 'lavisto') ?></li>
                                        <li> <?php echo __('Single Product Template', 'lavisto') ?></li>
                                        <li> <?php echo __('Cart Page Template', 'lavisto') ?></li>
                                        <li> <?php echo __('Checkout Page Template', 'lavisto') ?></li>
                                        <li> <?php echo __('Left Sidebar Single Template', 'lavisto') ?></li>
                                        <li> <?php echo __('Full Width Single Template', 'lavisto') ?></li>

                                    </ul>
                                <li>
                                <li><strong> - <?php echo __('Ready to import pre-build demo -1 ', 'lavisto') ?></strong></li>
                                <li><strong> - <?php echo __('Fully Customizable Header Layout - 2', 'lavisto') ?></strong></li>
                                <li> <strong>- <?php echo __('Fully Customizable Footer Layout - 4 ', 'lavisto') ?></strong></li>
                                <li><strong> - <?php echo __('12 Beautiful Fonts Option', 'lavisto') ?></strong></li>
                                <li> <strong>- <?php echo __('Innper Page Templates', 'lavisto') ?></strong></li>
                                <li> <strong>- <?php echo __('On Scroll Animation option', 'lavisto') ?></strong></li>
                                <li> <strong>- <?php echo __('Access Cozy Blocks with upto 22 Advanced Blocks for FSE and Gutenberg Editor', 'lavisto') ?></strong></li>
                            </ul>
                        </div>
                        <div class="half-col pro-features">
                            <h3><?php echo __('Premium Features', 'lavisto') ?></h3>
                            <p><?php echo __('Including all free features and comes with 30+ advanced blocks that enhance and power up the ecommerce website, here are some blocks that add the powerful features for your ecommerce/shopping website. By seamlessly integrating these blocks with our ready-to-use patterns, you have the flexibility to craft a wide selection of captivating online store ease', 'lavisto') ?></p>
                            <h4><?php echo __('Additional Advanced Pre-build Section', 'lavisto') ?></h4>
                            <ul>
                                <li><strong> <?php echo __('Ready to import pre-build demo - 3 (Total 4) ', 'lavisto') ?></strong></li>
                                <li><?php echo __('Banner Slider', 'lavisto') ?></li>
                                <li><?php echo __('Banner with Booking Form', 'lavisto') ?></li>
                                <li><?php echo __('Banner with Video Background', 'lavisto') ?></li>
                                <li><?php echo __('Banner Video Slider', 'lavisto') ?></li>
                                <li><?php echo __('Testimonial Carousel', 'lavisto') ?></li>
                                <li><?php echo __('Room Showcase Carousel', 'lavisto') ?></li>
                                <li><?php echo __('Local Tour and Activities Carousel', 'lavisto') ?></li>
                                <li><?php echo __('About Us Section - 3', 'lavisto') ?></li>
                                <li><?php echo __('Latest Post Carousel', 'lavisto') ?></li>
                                <li><?php echo __('Offer Section', 'lavisto') ?></li>
                                <li><?php echo __('Partners Logo Showcase Carousel', 'lavisto') ?></li>
                                <li><?php echo __('Partners Logo Showcase Grid', 'lavisto') ?></li>
                                <li><?php echo __('Featured Content Carousel', 'lavisto') ?></li>
                                <li><?php echo __('Starts Counter with Video Background', 'lavisto') ?></li>
                                <li><?php echo __('Booking Enquiry Form Section', 'lavisto') ?></li>
                                <li><?php echo __('Benefits CTA Section', 'lavisto') ?></li>


                            </ul>

                            <h4><?php echo __('Additional Advanced Blocks with Cozy Blocks', 'lavisto') ?></h4>
                            <ul>
                                <li><?php echo __('Post Blocks', 'lavisto') ?></li>
                                <li><?php echo __('Slider Block', 'lavisto') ?></li>
                                <li><?php echo __('Counter Block', 'lavisto') ?></li>
                                <li><?php echo __('Team Block (With Carousel)', 'lavisto') ?></li>
                                <li><?php echo __('Testimonials Block (With Carousel)', 'lavisto') ?></li>
                                <li><?php echo __('Social Shares Blocks', 'lavisto') ?></li>
                                <li><?php echo __('Social Icons Blocks', 'lavisto') ?></li>
                                <li><?php echo __('Breadcrumbs Block', 'lavisto') ?></li>
                                <li><?php echo __('Portfolio Block (With Custom Post Type)', 'lavisto') ?></li>
                                <li><?php echo __('Popup buidler Block to display offer and flash sale', 'lavisto') ?>
                                    <?php echo __('and access', 'lavisto') ?> <a href="https://cozythemes.com/cozy-addons/" target="_blank"><?php echo __('Cozy Blocks with more than 30+ advanced block.', 'lavisto') ?></a>
                                </li>

                            </ul>
                            <br />

                            <a href="https://cozythemes.com/pricing-and-plans/" class="upgrade-btn button" target="_blank"><?php echo __('Upgrade to Pro', 'lavisto') ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
