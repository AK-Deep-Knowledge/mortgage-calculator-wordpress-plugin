<?php

/**
 * Plugin Name: Aw Mortgage Calculators
 * Plugin URI: https://akdeepknowledge.com
 * Description: A simple mortgage calculators plugin.
 * Version: 1.0.0
 * Author: Arsalan Khatri
 * Author URI: https://akdeepknowledge.com
 * Company Name: AK Deep Knowledge
 * Company URL: https://akdeepknowledge.com
 */
if (!defined('ABSPATH')) exit;

// 🧩 Remove reset param when settings are saved (Calculator 1 + 2)
add_action('admin_init', function () {
    if (isset($_GET['settings-updated']) && $_GET['settings-updated'] === 'true' && isset($_GET['page'])) {
        $page = sanitize_text_field($_GET['page']);
        if ($page === 'calculator1' && isset($_GET['calculator1_reset_done'])) {
            wp_safe_redirect(remove_query_arg('calculator1_reset_done'));
            exit;
        }
        if ($page === 'calculator2' && isset($_GET['calculator2_reset_done'])) {
            wp_safe_redirect(remove_query_arg('calculator2_reset_done'));
            exit;
        }
    }
});

// 🧹 Reset All Calculator 1 Settings
add_action('admin_init', function () {
    if (isset($_POST['calculator1_reset_settings'])) {
        $defaults = calculator1_default_settings();
        foreach ($defaults as $key => $value) {
            update_option($key, $value);
        }
        wp_safe_redirect(add_query_arg('calculator1_reset_done', '1', wp_get_referer()));
        exit;
    }
});

// 🧹 Reset All Calculator 2 Settings
add_action('admin_init', function () {
    if (isset($_POST['calculator2_reset_settings'])) {
        $defaults = calculator2_default_settings();
        foreach ($defaults as $key => $value) {
            update_option($key, $value);
        }
        wp_safe_redirect(add_query_arg('calculator2_reset_done', '1', wp_get_referer()));
        exit;
    }
});

// ----------------- DEFAULT SETTINGS -----------------
function calculator1_default_settings()
{
    return [
        'calculator1_apply_href'         => '/',
        'calculator1_prequal_href'       => '/',
        'calculator1_bg_color'           => '#7bc246',
        'calculator1_slider_track_color' => '#88b2ac',
        'calculator1_slider_thumb_color' => '#5c5c5c',

        // 🟣 PDF Button Settings (Normal)
        'generetepdfbutton'              => '#7bc246', // Normal Bg
        'calculator1_pdf_text_color'     => '#ffffff', // Normal Text
        'calculator1_pdf_border_color'   => '#7bc246', // Normal Border
        'calculator1_pdf_border_width'   => '0',
        'calculator1_pdf_border_radius'  => '4',
        'calculator1_pdf_padding_top'    => '10',
        'calculator1_pdf_padding_lr'     => '20',

        // 🟣 PDF Button Settings (Hover & Margins - NEW)
        'calculator1_pdf_hover_bg_color'     => '#5a9632', // Darker Green
        'calculator1_pdf_hover_text_color'   => '#ffffff',
        'calculator1_pdf_hover_border_color' => '#5a9632',
        'calculator1_pdf_margin_top'         => '10',
        'calculator1_pdf_margin_bottom'      => '10',

        // 🟣 Graph Colors
        'calculator1_graph_color_1' => '#4CAF50',
        'calculator1_graph_color_2' => '#FF9800',
        'calculator1_graph_color_3' => '#2196F3',
        'calculator1_graph_color_4' => '#9C27B0',
        'calculator1_graph_color_5' => '#E91E63',
        'calculator1_graph_color_6' => '#607D8B',
    ];
}


function calculator2_default_settings()
{
    return [
        'calculator2_apply_href'         => '/',
        'calculator2_prequal_href'       => '/',
        
        // 🟣 Graph Colors (Existing)
        'calculator2_graph_color_primary'   => '#FF0000',
        'calculator2_graph_color_secondary' => '#00FF00',
        'calculator2_graph_color_dark'      => '#333333',
        'calculator2_graph_color_gray'      => '#888888',

        // 🟣 PDF Button Settings (Calculator 2 - NEW)
        'calculator2_pdf_bg_color'         => '#7bc246', // Normal Bg
        'calculator2_pdf_text_color'       => '#ffffff', // Normal Text
        'calculator2_pdf_border_color'     => '#7bc246', // Normal Border
        'calculator2_pdf_border_width'     => '0',
        'calculator2_pdf_border_radius'    => '4',
        'calculator2_pdf_padding_top'      => '10',
        'calculator2_pdf_padding_lr'       => '20',

        // 🟣 Hover & Margins (Calculator 2 - NEW)
        'calculator2_pdf_hover_bg_color'     => '#5a9632', 
        'calculator2_pdf_hover_text_color'   => '#ffffff',
        'calculator2_pdf_hover_border_color' => '#5a9632',
        'calculator2_pdf_margin_top'         => '10',
        'calculator2_pdf_margin_bottom'      => '10',
    ];
}

register_activation_hook(__FILE__, function () {
    $defaults = calculator1_default_settings();
    foreach ($defaults as $key => $value) {
        if (get_option($key) === false) {
            update_option($key, $value);
        }
    }
});



// global js variable calculater 1
add_action('wp_head', function () {
    $url = get_option('calculator1_apply_href', '/');
    echo '<script>window.applyHref1 = "' . esc_js($url) . '";</script>';
});

add_action('wp_head', function () {
    $prequal_url = get_option('calculator1_prequal_href', '/');
    echo '<script>window.prequalHref1 = "' . esc_js($prequal_url) . '";</script>';
});

// calculater 1 label background color 
add_action('wp_head', function () {
    $color = get_option('calculator1_bg_color', '#fa0000ff'); // Default color
    echo '<style>
        .calc-big-label {
            background-color: ' . esc_html($color) . ' !important;
        }
        .button{
            background-color: ' . esc_html($color) . ' !important;
        }
    </style>';
});
// calculater 1 slider track background color 
add_action('wp_head', function () {
    $slider_track_color = get_option('calculator1_slider_track_color', '#000000ff');

    echo '<style>
        /* 🎨 Force override gradient background */
        .calc-slider-track {
            background: ' . esc_html($slider_track_color) . ' !important;
            background-image: none !important;
            background-color: ' . esc_html($slider_track_color) . ' !important;
            transition: background-color 0.3s ease;
        }
    </style>';
});
// calculater 1 slider thumb background color 
add_action('wp_head', function () {
    $thumb_color = get_option('calculator1_slider_thumb_color', '#ff0000ff');

    echo '<style>
        /* 🎯 Slider Thumb Color */
        .calc-slider-thumb {
            background: ' . esc_html($thumb_color) . ' !important;
            background-color: ' . esc_html($thumb_color) . ' !important;
        }

        .calc-slider-thumb:hover {
            background: ' . esc_html($thumb_color) . ' !important;
        }
    </style>';
});

// calculater 1 pdfbutton COMPLETE Styling
add_action('wp_head', function () {
    // Normal Values
    $bg_color      = get_option('generetepdfbutton', '#7bc246');
    $text_color    = get_option('calculator1_pdf_text_color', '#ffffff');
    $border_color  = get_option('calculator1_pdf_border_color', '#7bc246');
    
    // Hover Values (New)
    $hover_bg      = get_option('calculator1_pdf_hover_bg_color', '#5a9632');
    $hover_text    = get_option('calculator1_pdf_hover_text_color', '#ffffff');
    $hover_border  = get_option('calculator1_pdf_hover_border_color', '#5a9632');
    
    // Dimensions
    $border_width  = get_option('calculator1_pdf_border_width', '0');
    $radius        = get_option('calculator1_pdf_border_radius', '4');
    $pad_tb        = get_option('calculator1_pdf_padding_top', '10'); 
    $pad_lr        = get_option('calculator1_pdf_padding_lr', '20');
    $margin_top    = get_option('calculator1_pdf_margin_top', '10');
    $margin_bot    = get_option('calculator1_pdf_margin_bottom', '10');

    echo '<style>
        /* 🎯 PDF Button Full Customization */
        #generatePDF, button#generatePDF, a#generatePDF, input#generatePDF {
            background: ' . esc_html($bg_color) . ' !important;
            background-color: ' . esc_html($bg_color) . ' !important;
            color: ' . esc_html($text_color) . ' !important;
            border: ' . esc_html($border_width) . 'px solid ' . esc_html($border_color) . ' !important;
            border-radius: ' . esc_html($radius) . 'px !important;
            padding: ' . esc_html($pad_tb) . 'px ' . esc_html($pad_lr) . 'px !important;
            margin-top: ' . esc_html($margin_top) . 'px !important;
            margin-bottom: ' . esc_html($margin_bot) . 'px !important;
            
            cursor: pointer;
            transition: all 0.3s ease; /* Smooth transition for color changes */
            background-image: none !important;
            box-shadow: none !important;
            display: inline-block; /* Helps with margins */
        }

        /* 🖱️ Hover State */
        #generatePDF:hover, button#generatePDF:hover, a#generatePDF:hover {
            background: ' . esc_html($hover_bg) . ' !important;
            background-color: ' . esc_html($hover_bg) . ' !important;
            color: ' . esc_html($hover_text) . ' !important;
            border-color: ' . esc_html($hover_border) . ' !important;
            opacity: 1 !important; /* Opacity hataya taake exact colors dikhen */
        }
    </style>';
});


// 🟣 Calculator 2 PDF Button Styling
add_action('wp_head', function () {
    // Normal Values
    $bg_color      = get_option('calculator2_pdf_bg_color', '#7bc246');
    $text_color    = get_option('calculator2_pdf_text_color', '#ffffff');
    $border_color  = get_option('calculator2_pdf_border_color', '#7bc246');
    
    // Hover Values
    $hover_bg      = get_option('calculator2_pdf_hover_bg_color', '#5a9632');
    $hover_text    = get_option('calculator2_pdf_hover_text_color', '#ffffff');
    $hover_border  = get_option('calculator2_pdf_hover_border_color', '#5a9632');
    
    // Dimensions
    $border_width  = get_option('calculator2_pdf_border_width', '0');
    $radius        = get_option('calculator2_pdf_border_radius', '4');
    $pad_tb        = get_option('calculator2_pdf_padding_top', '10'); 
    $pad_lr        = get_option('calculator2_pdf_padding_lr', '20');
    $margin_top    = get_option('calculator2_pdf_margin_top', '10');
    $margin_bot    = get_option('calculator2_pdf_margin_bottom', '10');

    echo '<style>
        /* 🎯 Calculator 2 PDF Button */
        #generatePDF2, button#generatePDF2, a#generatePDF2 {
            background: ' . esc_html($bg_color) . ' !important;
            background-color: ' . esc_html($bg_color) . ' !important;
            color: ' . esc_html($text_color) . ' !important;
            border: ' . esc_html($border_width) . 'px solid ' . esc_html($border_color) . ' !important;
            border-radius: ' . esc_html($radius) . 'px !important;
            padding: ' . esc_html($pad_tb) . 'px ' . esc_html($pad_lr) . 'px !important;
            margin-top: ' . esc_html($margin_top) . 'px !important;
            margin-bottom: ' . esc_html($margin_bot) . 'px !important;
            
            cursor: pointer;
            transition: all 0.3s ease;
            background-image: none !important;
            box-shadow: none !important;
            display: inline-block;
        }

        /* 🖱️ Hover State */
        #generatePDF2:hover, button#generatePDF2:hover, a#generatePDF2:hover {
            background: ' . esc_html($hover_bg) . ' !important;
            background-color: ' . esc_html($hover_bg) . ' !important;
            color: ' . esc_html($hover_text) . ' !important;
            border-color: ' . esc_html($hover_border) . ' !important;
            opacity: 1 !important;
        }
    </style>';
});

// 🟣 GRAPH COLORS SETTINGS — CALCULATOR 1
add_action('admin_init', function () {
    // Section add karo
    add_settings_section(
        'calculator1_graph_section',
        'Graph Colors Settings',
        function () {
            echo 'Change the 6 graph colors from here (Mortgage, Taxes, Expenses, Estates).';
        },
        'calculator1_settings'
    );

    // 6 alag color pickers
    for ($i = 1; $i <= 6; $i++) {
        register_setting('calculator1_options_group', "calculator1_graph_color_$i");
        add_settings_field(
            "graph_color_$i",
            "Graph Color $i",
            function () use ($i) {
                $default_colors = ['#4CAF50', '#FF9800', '#2196F3', '#9C27B0', '#E91E63', '#607D8B'];
                $value = get_option("calculator1_graph_color_$i", $default_colors[$i - 1]);
                echo '<input type="color" name="calculator1_graph_color_' . $i . '" value="' . esc_attr($value) . '" style="width:100px;height:40px; display:flex;">';
            },
            'calculator1_settings',
            'calculator1_graph_section'
        );
    }
});
// 🟢 Inject Graph Colors to Frontend
add_action('wp_head', function () {
    $colors = [];
    for ($i = 1; $i <= 6; $i++) {
        $colors[] = get_option("calculator1_graph_color_$i", '#4CAF50');
    }
    $colors_js = json_encode($colors);
    echo "<script>window.graphColors1 = $colors_js;</script>";
});





// global js variable calculater 2
add_action('wp_head', function () {
    $url = get_option('calculator2_apply_href', '/');
    echo '<script>window.applyHref = "' . esc_js($url) . '";</script>';
});
add_action('wp_head', function () {
    $prequal_url = get_option('calculator2_prequal_href', '/');
    echo '<script>window.prequalHref = "' . esc_js($prequal_url) . '";</script>';
});


// 🟣 Calculator 2 Graph Colors Settings
add_action('admin_init', function () {
    add_settings_section(
        'calculator2_graph_section',
        'Graph Colors Settings',
        function () {
            echo 'Change the graph colors from here (primary, secondary, dark, gray).';
        },
        'calculator2_settings'
    );

    $default_colors = [
        'primary'   => '#FF0000',
        'secondary' => '#00FF00',
        'dark'      => '#333333',
        'gray'      => '#888888',
    ];

    foreach ($default_colors as $key => $color) {
        register_setting('calculator2_options_group', "calculator2_graph_color_$key");
        add_settings_field(
            "graph_color_$key",
            ucfirst($key) . " Color",
            function () use ($key, $color) {
                $value = get_option("calculator2_graph_color_$key", $color);
                echo '<input type="color" name="calculator2_graph_color_' . $key . '" value="' . esc_attr($value) . '" style="width:100px;height:40px;">';
            },
            'calculator2_settings',
            'calculator2_graph_section'
        );
    }
});
add_action('wp_head', function () {
    $colors = [
        'primary'   => get_option('calculator2_graph_color_primary', '#FF0000'),
        'secondary' => get_option('calculator2_graph_color_secondary', '#00FF00'),
        'dark'      => get_option('calculator2_graph_color_dark', '#333333'),
        'gray'      => get_option('calculator2_graph_color_gray', '#888888'),
    ];

    $colors_js = json_encode(array_values($colors)); // JS array ke liye values sirf
    echo "<script>window.calculator2Colors = $colors_js;</script>";
});



// ----------------- ADMIN MENU -----------------
add_action('admin_menu', function () {
add_menu_page(
        'Mortgage Tools',
        'Mortgage Tools',
        'manage_options',
        'custom-tools',
        function () {
            // Current User Email (Optional: agar dynamic uthana ho, warna static string use karein)
            $dev_name = "Arsalan Khatri";
            $dev_email = "contact@akdeepknowledge.com"; // Yahan apni asli email likh dena
            $dev_site = "https://akdeepknowledge.com";
            $plugin_ver = "1.0.0";
            
            ?>
            <style>
                /* --- Dashboard Layout --- */
                .ak-wrap { max-width: 1200px; margin: 20px 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif; }
                
                /* Header */
                .ak-header { background: #fff; padding: 20px 30px; border-left: 5px solid #2271b1; box-shadow: 0 1px 2px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
                .ak-header h1 { font-size: 22px; font-weight: 700; color: #1d2327; margin: 0; }
                .ak-header-badge { background: #f0f0f1; color: #50575e; padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

                /* Grid System */
                .ak-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 25px; }

                /* Common Card Styles */
                .ak-card { background: #fff; border: 1px solid #dcdcde; box-shadow: 0 1px 1px rgba(0,0,0,0.04); transition: transform 0.2s, box-shadow 0.2s; position: relative; overflow: hidden; }
                .ak-card:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.08); border-color: #c3c4c7; }
                
                .ak-card-header { background: #fbfbfc; padding: 15px 20px; border-bottom: 1px solid #f0f0f1; display: flex; align-items: center; gap: 10px; }
                .ak-card-header h2 { font-size: 16px; font-weight: 600; margin: 0; color: #1d2327; }
                .ak-card-header .dashicons { color: #2271b1; font-size: 20px; }
                
                .ak-card-body { padding: 20px; color: #646970; font-size: 14px; line-height: 1.6; }
                
                /* Shortcode Box */
                .ak-code-box { background: #f6f7f7; border: 1px solid #c3c4c7; border-left: 3px solid #72aee6; padding: 12px; margin-top: 15px; border-radius: 0 3px 3px 0; }
                .ak-code-box code { background: transparent; padding: 0; font-size: 14px; color: #2c3338; display: block; margin-top: 4px; }
                .ak-label { font-size: 10px; text-transform: uppercase; color: #8c8f94; font-weight: 700; }

                .ak-card-footer { padding: 15px 20px; border-top: 1px solid #f0f0f1; background: #fff; text-align: right; }
                .ak-btn { text-decoration: none; font-weight: 600; font-size: 13px; color: #2271b1; }
                .ak-btn:hover { color: #135e96; }

                /* --- DEVELOPER CARD SPECIAL STYLES --- */
                .ak-dev-card { border-top: 3px solid #135e96; } /* Dark Blue Top Border */
                .ak-profile { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
                .ak-avatar { width: 50px; height: 50px; background: #2271b1; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: bold; }
                
                .ak-meta-row { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; font-size: 13px; border-bottom: 1px solid #f0f0f1; padding-bottom: 8px; }
                .ak-meta-row:last-child { border-bottom: none; }
                .ak-meta-row .dashicons { color: #8c8f94; font-size: 16px; }
                .ak-meta-label { width: 70px; font-weight: 600; color: #1d2327; }
                .ak-meta-value a { color: #2271b1; text-decoration: none; }
                .ak-meta-value a:hover { text-decoration: underline; }

                .ak-contact-btn { display: block; width: 100%; text-align: center; background: #135e96; color: #fff; padding: 10px 0; border-radius: 4px; text-decoration: none; font-weight: 600; margin-top: 15px; transition: background 0.2s; }
                .ak-contact-btn:hover { background: #0f4a76; color: #fff; }

            </style>

            <div class="wrap ak-wrap">
                <div class="ak-header">
                    <h1>Aw Mortgage Calculators</h1>
                    <span class="ak-header-badge">Version <?php echo esc_html($plugin_ver); ?></span>
                </div>

                <div class="ak-grid">
                    
                    <div class="ak-card">
                        <div class="ak-card-header">
                            <span class="dashicons dashicons-admin-home"></span>
                            <h2>Affordability Calculator</h2>
                        </div>
                        <div class="ak-card-body">
                            <p>Determines maximum home price based on income and debts. Includes customizable 6-color breakdown graphs.</p>
                            <div class="ak-code-box">
                                <span class="ak-label">Shortcode</span>
                                <code>[calculator1]</code>
                            </div>
                        </div>
                        <div class="ak-card-footer">
                            <a href="admin.php?page=calculator1" class="ak-btn">Settings & Configuration &rarr;</a>
                        </div>
                    </div>

                    <div class="ak-card">
                        <div class="ak-card-header">
                            <span class="dashicons dashicons-money-alt"></span>
                            <h2>Payment Calculator</h2>
                        </div>
                        <div class="ak-card-body">
                            <p>Estimates monthly PITI (Principal, Interest, Taxes, Insurance) payments. Features interactive rate sliders.</p>
                            <div class="ak-code-box">
                                <span class="ak-label">Shortcode</span>
                                <code>[calculator2]</code>
                            </div>
                        </div>
                        <div class="ak-card-footer">
                            <a href="admin.php?page=calculator2" class="ak-btn">Settings & Configuration &rarr;</a>
                        </div>
                    </div>

                    <div class="ak-card ak-dev-card">
                        <div class="ak-card-header">
                            <span class="dashicons dashicons-id-alt"></span>
                            <h2>Developer Information</h2>
                        </div>
                        <div class="ak-card-body">
                            <div class="ak-profile">
                                <div class="ak-avatar">AK</div> <div>
                                    <strong style="font-size: 15px; color: #1d2327;"><?php echo esc_html($dev_name); ?></strong><br>
                                    <span style="font-size: 12px;">Full Stack Developer</span>
                                </div>
                            </div>

                            <div class="ak-meta-row">
                                <span class="dashicons dashicons-building"></span>
                                <span class="ak-meta-label">Company:</span>
                                <span class="ak-meta-value"><a href="<?php echo esc_url($dev_site); ?>" target="_blank">AK Deep Knowledge</a></span>
                            </div>

                            <div class="ak-meta-row">
                                <span class="dashicons dashicons-email"></span>
                                <span class="ak-meta-label">Email:</span>
                                <span class="ak-meta-value"><a href="mailto:<?php echo esc_attr($dev_email); ?>"><?php echo esc_html($dev_email); ?></a></span>
                            </div>

                            <div class="ak-meta-row">
                                <span class="dashicons dashicons-admin-site-alt3"></span>
                                <span class="ak-meta-label">Web:</span>
                                <span class="ak-meta-value"><a href="<?php echo esc_url($dev_site); ?>" target="_blank">akdeepknowledge.com</a></span>
                            </div>

                            <a href="mailto:<?php echo esc_attr($dev_email); ?>?subject=Support Request: Aw Mortgage Plugin" class="ak-contact-btn">
                                <span class="dashicons dashicons-email-alt" style="margin-top: 2px;"></span> Contact Developer
                            </a>
                        </div>
                    </div>

                </div>
                
                <p style="text-align: center; margin-top: 30px; color: #8c8f94; font-size: 12px;">
                    &copy; <?php echo date('Y'); ?> <strong>AK Deep Knowledge</strong>. All rights reserved.
                </p>
            </div>
            <?php
        },
        'dashicons-calculator',
        6
    );


    add_submenu_page(
        'custom-tools',
        'Affordability',
        'Affordability',
        'manage_options',
        'calculator1',
        function () {
?>
        <div class="wrap">
            <h1>Affordability Calculator</h1>
            <p>Use shortcode: <code>[calculator1]</code></p>
            <form method="post" action="options.php">
                <?php
                settings_fields('calculator1_options_group');
                do_settings_sections('calculator1_settings');
                submit_button('Save Settings');
                ?>
            </form>
            <form method="post">
                <?php submit_button('🔁 Reset All Settings', 'delete', 'calculator1_reset_settings'); ?>
            </form>
        </div>
    <?php
        }
    );

    add_submenu_page(
        'custom-tools',
        'Calculator 2',
        'Payment',
        'manage_options',
        'calculator2',
        function () {
    ?>
        <div class="wrap">
            <h1>Payment Calculator</h1>
            <p>Use shortcode: <code>[calculator2]</code></p>
            <form method="post" action="options.php">
                <?php
                settings_fields('calculator2_options_group');
                do_settings_sections('calculator2_settings');
                submit_button('Save Settings');
                ?>
            </form>
            <form method="post">
                <?php submit_button('🔁 Reset All Settings', 'delete', 'calculator2_reset_settings'); ?>
            </form>

        </div>
<?php
        }
    );
});


// ----------------- REGISTER SETTINGS FOR CALCULATOR 1 -----------------
add_action('admin_init', function () {

    // ============================================================
    // SECTION 1: LINKS & NAVIGATION
    // ============================================================
    add_settings_section(
        'calculator1_links_section',
        '🔗 Links & Navigation',
        function () { echo 'Set the destination URLs for your buttons.'; },
        'calculator1_settings'
    );

    // 🟢 Get Prequalified Now URL
    register_setting('calculator1_options_group', 'calculator1_prequal_href');
    add_settings_field(
        'prequal_href_1',
        'Get Prequalified Now URL',
        function () {
            $value = get_option('calculator1_prequal_href', '/');
            echo '<input type="text" name="calculator1_prequal_href" value="' . esc_attr($value) . '" style="width:400px;">';
        },
        'calculator1_settings',
        'calculator1_links_section'
    );

    // 🟢 Apply Now URL
    register_setting('calculator1_options_group', 'calculator1_apply_href');
    add_settings_field(
        'apply_href_1',
        'Apply Now Button URL',
        function () {
            $value = get_option('calculator1_apply_href', '/');
            echo '<input type="text" name="calculator1_apply_href" value="' . esc_attr($value) . '" style="width:400px;">';
        },
        'calculator1_settings',
        'calculator1_links_section'
    );


    // ============================================================
    // SECTION 2: GLOBAL APPEARANCE (Colors & Sliders)
    // ============================================================
    add_settings_section(
        'calculator1_global_styles',
        '🎨 Global Appearance',
        function () { echo 'Control the main background and slider colors.'; },
        'calculator1_settings'
    );

    // ✅ Background Color Picker
    register_setting('calculator1_options_group', 'calculator1_bg_color');
    add_settings_field(
        'bg_color',
        'Label & Button Background',
        function () {
            $value = get_option('calculator1_bg_color', '#008cffff');
            echo '<input type="color" name="calculator1_bg_color" value="' . esc_attr($value) . '" style="width:100px;height:40px;">';
        },
        'calculator1_settings',
        'calculator1_global_styles'
    );

    // ✅ Slider Track Color
    register_setting('calculator1_options_group', 'calculator1_slider_track_color');
    add_settings_field(
        'slider_track_color',
        'Slider Track Color',
        function () {
            $value = get_option('calculator1_slider_track_color', '#0073aa');
            echo '<input type="color" name="calculator1_slider_track_color" value="' . esc_attr($value) . '" style="width:100px;height:40px;">';
        },
        'calculator1_settings',
        'calculator1_global_styles'
    );

    // ✅ Slider Thumb Color
    register_setting('calculator1_options_group', 'calculator1_slider_thumb_color');
    add_settings_field(
        'slider_thumb_color',
        'Slider Thumb Color',
        function () {
            $value = get_option('calculator1_slider_thumb_color', '#0073aa');
            echo '<input type="color" name="calculator1_slider_thumb_color" value="' . esc_attr($value) . '" style="width:100px;height:40px;">';
        },
        'calculator1_settings',
        'calculator1_global_styles'
    );


    // ============================================================
    // SECTION 3: PDF BUTTON STYLING
    // ============================================================
    add_settings_section(
        'calculator1_pdf_section',
        '📄 Generate PDF Button Styling',
        function () { echo 'Customize Normal, Hover, and Layout settings for the PDF button.'; },
        'calculator1_settings'
    );

    // --- NORMAL STATE ---
    
    register_setting('calculator1_options_group', 'calculator1_pdf_text_color');
    add_settings_field('pdf_text_color', 'Normal Text Color', function () {
        $value = get_option('calculator1_pdf_text_color', '#ffffff');
        echo '<input type="color" name="calculator1_pdf_text_color" value="' . esc_attr($value) . '" style="width:100px;height:40px;">';
    }, 'calculator1_settings', 'calculator1_pdf_section');

    register_setting('calculator1_options_group', 'generetepdfbutton');
    add_settings_field('pdf_bg_color', 'Normal Background', function () {
        $value = get_option('generetepdfbutton', '#7bc246');
        echo '<input type="color" name="generetepdfbutton" value="' . esc_attr($value) . '" style="width:100px;height:40px;">';
    }, 'calculator1_settings', 'calculator1_pdf_section');

    register_setting('calculator1_options_group', 'calculator1_pdf_border_color');
    add_settings_field('pdf_border_color', 'Normal Border Color', function () {
        $value = get_option('calculator1_pdf_border_color', '#7bc246');
        echo '<input type="color" name="calculator1_pdf_border_color" value="' . esc_attr($value) . '" style="width:100px;height:40px;">';
    }, 'calculator1_settings', 'calculator1_pdf_section');

    // --- HOVER STATE (NEW) ---

    register_setting('calculator1_options_group', 'calculator1_pdf_hover_text_color');
    add_settings_field('pdf_hover_text_color', '🖱️ Hover Text Color', function () {
        $value = get_option('calculator1_pdf_hover_text_color', '#ffffff');
        echo '<input type="color" name="calculator1_pdf_hover_text_color" value="' . esc_attr($value) . '" style="width:100px;height:40px;">';
    }, 'calculator1_settings', 'calculator1_pdf_section');

    register_setting('calculator1_options_group', 'calculator1_pdf_hover_bg_color');
    add_settings_field('pdf_hover_bg_color', '🖱️ Hover Background', function () {
        $value = get_option('calculator1_pdf_hover_bg_color', '#5a9632');
        echo '<input type="color" name="calculator1_pdf_hover_bg_color" value="' . esc_attr($value) . '" style="width:100px;height:40px;">';
    }, 'calculator1_settings', 'calculator1_pdf_section');

    register_setting('calculator1_options_group', 'calculator1_pdf_hover_border_color');
    add_settings_field('pdf_hover_border_color', '🖱️ Hover Border Color', function () {
        $value = get_option('calculator1_pdf_hover_border_color', '#5a9632');
        echo '<input type="color" name="calculator1_pdf_hover_border_color" value="' . esc_attr($value) . '" style="width:100px;height:40px;">';
    }, 'calculator1_settings', 'calculator1_pdf_section');

    // --- DIMENSIONS & SPACING ---

    register_setting('calculator1_options_group', 'calculator1_pdf_border_width');
    add_settings_field('pdf_border_width', 'Border Width (px)', function () {
        $value = get_option('calculator1_pdf_border_width', '0');
        echo '<input type="number" name="calculator1_pdf_border_width" value="' . esc_attr($value) . '" style="width:100px;"> px';
    }, 'calculator1_settings', 'calculator1_pdf_section');

    register_setting('calculator1_options_group', 'calculator1_pdf_border_radius');
    add_settings_field('pdf_border_radius', 'Border Radius (px)', function () {
        $value = get_option('calculator1_pdf_border_radius', '4');
        echo '<input type="number" name="calculator1_pdf_border_radius" value="' . esc_attr($value) . '" style="width:100px;"> px';
    }, 'calculator1_settings', 'calculator1_pdf_section');

    register_setting('calculator1_options_group', 'calculator1_pdf_padding_top');
    add_settings_field('pdf_padding_top', 'Padding (Top/Bottom)', function () {
        $value = get_option('calculator1_pdf_padding_top', '10');
        echo '<input type="number" name="calculator1_pdf_padding_top" value="' . esc_attr($value) . '" style="width:100px;"> px';
    }, 'calculator1_settings', 'calculator1_pdf_section');

    register_setting('calculator1_options_group', 'calculator1_pdf_padding_lr');
    add_settings_field('pdf_padding_lr', 'Padding (Left/Right)', function () {
        $value = get_option('calculator1_pdf_padding_lr', '20');
        echo '<input type="number" name="calculator1_pdf_padding_lr" value="' . esc_attr($value) . '" style="width:100px;"> px';
    }, 'calculator1_settings', 'calculator1_pdf_section');

    // --- MARGINS (NEW) ---
    
    register_setting('calculator1_options_group', 'calculator1_pdf_margin_top');
    add_settings_field('pdf_margin_top', 'Margin Top', function () {
        $value = get_option('calculator1_pdf_margin_top', '10');
        echo '<input type="number" name="calculator1_pdf_margin_top" value="' . esc_attr($value) . '" style="width:100px;"> px';
    }, 'calculator1_settings', 'calculator1_pdf_section');

    register_setting('calculator1_options_group', 'calculator1_pdf_margin_bottom');
    add_settings_field('pdf_margin_bottom', 'Margin Bottom', function () {
        $value = get_option('calculator1_pdf_margin_bottom', '10');
        echo '<input type="number" name="calculator1_pdf_margin_bottom" value="' . esc_attr($value) . '" style="width:100px;"> px';
    }, 'calculator1_settings', 'calculator1_pdf_section');
    
});


// ----------------- REGISTER SETTINGS FOR CALCULATOR 2 -----------------
add_action('admin_init', function () {

    // ============================================================
    // SECTION 1: LINKS
    // ============================================================
    add_settings_section(
        'calculator2_links_section',
        '🔗 Links Settings',
        function () { echo 'Set the destination URLs for Calculator 2 buttons.'; },
        'calculator2_settings'
    );

    // Get Prequalified Now
    register_setting('calculator2_options_group', 'calculator2_prequal_href');
    add_settings_field('prequal_href', 'Get Prequalified Now', function () {
        $value = get_option('calculator2_prequal_href', '/');
        echo '<input type="text" name="calculator2_prequal_href" value="' . esc_attr($value) . '" style="width:400px;">';
    }, 'calculator2_settings', 'calculator2_links_section');

    // Apply Now
    register_setting('calculator2_options_group', 'calculator2_apply_href');
    add_settings_field('apply_href', 'Apply Now Button URL', function () {
        $value = get_option('calculator2_apply_href', '/');
        echo '<input type="text" name="calculator2_apply_href" value="' . esc_attr($value) . '" style="width:400px;">';
    }, 'calculator2_settings', 'calculator2_links_section');


    // ============================================================
    // SECTION 2: PDF BUTTON STYLING (CALCULATOR 2)
    // ============================================================
    add_settings_section(
        'calculator2_pdf_section',
        '📄 Generate PDF Button Styling',
        function () { echo 'Customize Normal, Hover, and Layout settings for Calculator 2 PDF button.'; },
        'calculator2_settings'
    );

    // --- NORMAL STATE ---
    register_setting('calculator2_options_group', 'calculator2_pdf_text_color');
    add_settings_field('c2_pdf_text', 'Normal Text Color', function () {
        $value = get_option('calculator2_pdf_text_color', '#ffffff');
        echo '<input type="color" name="calculator2_pdf_text_color" value="' . esc_attr($value) . '" style="width:100px;height:40px;">';
    }, 'calculator2_settings', 'calculator2_pdf_section');

    register_setting('calculator2_options_group', 'calculator2_pdf_bg_color');
    add_settings_field('c2_pdf_bg', 'Normal Background', function () {
        $value = get_option('calculator2_pdf_bg_color', '#7bc246');
        echo '<input type="color" name="calculator2_pdf_bg_color" value="' . esc_attr($value) . '" style="width:100px;height:40px;">';
    }, 'calculator2_settings', 'calculator2_pdf_section');

    register_setting('calculator2_options_group', 'calculator2_pdf_border_color');
    add_settings_field('c2_pdf_border_c', 'Normal Border Color', function () {
        $value = get_option('calculator2_pdf_border_color', '#7bc246');
        echo '<input type="color" name="calculator2_pdf_border_color" value="' . esc_attr($value) . '" style="width:100px;height:40px;">';
    }, 'calculator2_settings', 'calculator2_pdf_section');

    // --- HOVER STATE ---
    register_setting('calculator2_options_group', 'calculator2_pdf_hover_text_color');
    add_settings_field('c2_pdf_hover_text', '🖱️ Hover Text Color', function () {
        $value = get_option('calculator2_pdf_hover_text_color', '#ffffff');
        echo '<input type="color" name="calculator2_pdf_hover_text_color" value="' . esc_attr($value) . '" style="width:100px;height:40px;">';
    }, 'calculator2_settings', 'calculator2_pdf_section');

    register_setting('calculator2_options_group', 'calculator2_pdf_hover_bg_color');
    add_settings_field('c2_pdf_hover_bg', '🖱️ Hover Background', function () {
        $value = get_option('calculator2_pdf_hover_bg_color', '#5a9632');
        echo '<input type="color" name="calculator2_pdf_hover_bg_color" value="' . esc_attr($value) . '" style="width:100px;height:40px;">';
    }, 'calculator2_settings', 'calculator2_pdf_section');

    register_setting('calculator2_options_group', 'calculator2_pdf_hover_border_color');
    add_settings_field('c2_pdf_hover_border', '🖱️ Hover Border Color', function () {
        $value = get_option('calculator2_pdf_hover_border_color', '#5a9632');
        echo '<input type="color" name="calculator2_pdf_hover_border_color" value="' . esc_attr($value) . '" style="width:100px;height:40px;">';
    }, 'calculator2_settings', 'calculator2_pdf_section');

    // --- DIMENSIONS & SPACING ---
    register_setting('calculator2_options_group', 'calculator2_pdf_border_width');
    add_settings_field('c2_pdf_border_w', 'Border Width (px)', function () {
        $value = get_option('calculator2_pdf_border_width', '0');
        echo '<input type="number" name="calculator2_pdf_border_width" value="' . esc_attr($value) . '" style="width:100px;"> px';
    }, 'calculator2_settings', 'calculator2_pdf_section');

    register_setting('calculator2_options_group', 'calculator2_pdf_border_radius');
    add_settings_field('c2_pdf_radius', 'Border Radius (px)', function () {
        $value = get_option('calculator2_pdf_border_radius', '4');
        echo '<input type="number" name="calculator2_pdf_border_radius" value="' . esc_attr($value) . '" style="width:100px;"> px';
    }, 'calculator2_settings', 'calculator2_pdf_section');

    register_setting('calculator2_options_group', 'calculator2_pdf_padding_top');
    add_settings_field('c2_pdf_pad_top', 'Padding (Top/Bottom)', function () {
        $value = get_option('calculator2_pdf_padding_top', '10');
        echo '<input type="number" name="calculator2_pdf_padding_top" value="' . esc_attr($value) . '" style="width:100px;"> px';
    }, 'calculator2_settings', 'calculator2_pdf_section');

    register_setting('calculator2_options_group', 'calculator2_pdf_padding_lr');
    add_settings_field('c2_pdf_pad_lr', 'Padding (Left/Right)', function () {
        $value = get_option('calculator2_pdf_padding_lr', '20');
        echo '<input type="number" name="calculator2_pdf_padding_lr" value="' . esc_attr($value) . '" style="width:100px;"> px';
    }, 'calculator2_settings', 'calculator2_pdf_section');

    // --- MARGINS ---
    register_setting('calculator2_options_group', 'calculator2_pdf_margin_top');
    add_settings_field('c2_pdf_margin_t', 'Margin Top', function () {
        $value = get_option('calculator2_pdf_margin_top', '10');
        echo '<input type="number" name="calculator2_pdf_margin_top" value="' . esc_attr($value) . '" style="width:100px;"> px';
    }, 'calculator2_settings', 'calculator2_pdf_section');

    register_setting('calculator2_options_group', 'calculator2_pdf_margin_bottom');
    add_settings_field('c2_pdf_margin_b', 'Margin Bottom', function () {
        $value = get_option('calculator2_pdf_margin_bottom', '10');
        echo '<input type="number" name="calculator2_pdf_margin_bottom" value="' . esc_attr($value) . '" style="width:100px;"> px';
    }, 'calculator2_settings', 'calculator2_pdf_section');

});



// ----------------- FRONTEND ENQUEUE (global for shortcodes) -----------------
add_action('wp_enqueue_scripts', function () {
    global $post;

    // ✅ CALCULATOR 1
    if (isset($post->post_content) && has_shortcode($post->post_content, 'calculator1')) {
        $base = plugin_dir_url(__FILE__);

        // CSS
        wp_enqueue_style('ct-calculators', $base . 'assets/assets_1/css/calculators.css', [], null);
        wp_enqueue_style('ct-style', $base . 'assets/assets_1css/style.css', [], null);
        wp_enqueue_style('ct-style-min', $base . 'assets/assets_1/css/style.min.css', [], null);
        wp_enqueue_style('ct-main', $base . 'assets/assets_1/css/main.css', [], null);

        // JS
        wp_enqueue_script('ct-calculator1', $base . 'assets/assets_1/js/calculators.js', ['jquery'], null, true);
        wp_enqueue_script('ct-forms', $base . 'assets/assets_1/js/forms.js', ['jquery'], null, true);
        wp_enqueue_script('ct-main', $base . 'assets/assets_1/js/main.js', ['jquery'], null, true);
        wp_enqueue_script('ct-script2', $base . 'assets/assets_1/js/script.js', ['jquery'], null, true);
        wp_enqueue_script('ct-script3', $base . 'assets/assets_1/main/jquery/jquery-migrate.min.js', ['jquery'], null, true);
        wp_enqueue_script('ct-script4', $base . 'assets/assets_1/main/jquery/jquery.min.js', ['jquery'], null, true);
        wp_enqueue_script('ct-script4', $base . 'assets/assets_1/js/print.js', ['jquery'], null, true);
    }

    // ✅ CALCULATOR 2
    if (isset($post->post_content) && has_shortcode($post->post_content, 'calculator2')) {
        $base = plugin_dir_url(__FILE__);

        // CSS
        wp_enqueue_style('ct-calculators2', $base . 'assets/assets_2/css/calculators.css', [], null);
        wp_enqueue_style('ct-style2', $base . 'assets/assets_2/css/style.css', [], null);
        wp_enqueue_style('ct-style-min2', $base . 'assets/assets_2/css/style.min.css', [], null);
        wp_enqueue_style('ct-main2', $base . 'assets/assets_2/css/style2.css', [], null);
        // JS
        wp_enqueue_script('ct2-forms', $base . 'assets/assets_2/js/jquery-migrate.min.js', ['jquery'], null, true);
        wp_enqueue_script('ct2-jquery', $base . 'assets/assets_2/js/jquery.min.js', ['jquery'], null, true);
        // wp_enqueue_script('ct2-calculator', $base . 'assets\assets_2\js\calculator.js', ['jquery'], null, true);
        wp_enqueue_script('ct2-main', $base . 'assets/assets_2/js/main.js', ['jquery'], null, true);
        wp_enqueue_script('ct2-script', $base . 'assets/assets_2/js/script.js', ['jquery'], null, true);
    }
});

// ----------------- SHORTCODES -----------------

// 🧮 Calculator 1 shortcode
add_shortcode('calculator1', function () {
    ob_start();

    $file = plugin_dir_path(__FILE__) . 'html/how_much_can_I_afford.html';

    if (file_exists($file)) {
        $html = file_get_contents($file);
        // Replace {{BASE_URL}} dynamically if needed inside your HTML
        $base_url = plugin_dir_url(__FILE__);
        $html = str_replace('{{BASE_URL}}', $base_url, $html);
        echo $html;
    } else {
        echo '<p style="color:red;">HTML file not found: ' . esc_html($file) . '</p>';
    }

    return ob_get_clean();
});

// 🧮 Calculator 2 shortcode
add_shortcode('calculator2', function () {

    ob_start();

    $file = plugin_dir_path(__FILE__) . 'html/payment-calculator.html';

    if (file_exists($file)) {
        $html = file_get_contents($file);
        // Replace {{BASE_URL}} dynamically if needed inside your HTML
        $base_url = plugin_dir_url(__FILE__);
        $html = str_replace('{{BASE_URL}}', $base_url, $html);
        echo $html;
    } else {
        echo '<p style="color:red;">HTML file not found: ' . esc_html($file) . '</p>';
    }

    return ob_get_clean();
});
