<?php
/*
* QuantumCloud Promo + Support Page
* Revised On: 18-10-2023
*/

if ( ! defined( 'qcld_bargain_support_url' ) )
    define('qcld_bargain_support_url', plugin_dir_url( __FILE__ ) );

if ( ! defined( 'qcld_support_img_url' ) )
    define('qcld_support_img_url', qcld_bargain_support_url . "/images" );


/*Callback function to add the menu */
function qcld_bargain_show_promo_page_callback_func()
{
    add_submenu_page(
        "wbpt-minimum-acceptable-price-page",
        esc_html__('More WordPress Goodies for You!'),
        esc_html__('Support'),
        'manage_options',
        "wbpt-minimum-acceptable-price-supports",
        'qcld_bargain_promo_support_page_callback_func'
    );
} //show_promo_page_callback_func

add_action( 'admin_menu', 'qcld_bargain_show_promo_page_callback_func', 99 );


/*******************************
 * Main Class to Display Support
 * form and the promo pages
 *******************************/

if ( ! function_exists( 'qcld_bargain_include_promo_page_scripts' ) ) {	
	function qcld_bargain_include_promo_page_scripts( ) {   


        if( isset($_GET["page"]) && !empty($_GET["page"]) && (   $_GET["page"] == "wbpt-minimum-acceptable-price-supports"  ) ){

            wp_enqueue_style( 'qcld-support-fontawesome-css', qcld_bargain_support_url . "css/font-awesome.min.css");                              
            wp_enqueue_style( 'qcld-support-style-css', qcld_bargain_support_url . "css/style.css");

            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'jquery-ui-core');
            wp_enqueue_script( 'jquery-ui-tabs' );
            wp_enqueue_script( 'jquery-custom-form-processor', qcld_bargain_support_url . 'js/support-form-script.js',  array('jquery', 'jquery-ui-core','jquery-ui-tabs') );

            wp_add_inline_script( 'jquery-custom-form-processor', 
                                    'var qcld_bargain_ajaxurl    = "' . admin_url('admin-ajax.php') . '";
                                    var qcld_bargain_ajax_nonce  = "'. wp_create_nonce( 'woo-minimum-acceptable-price' ).'";   
                                ', 'before');
            
        }
	   
	}
	add_action('admin_enqueue_scripts', 'qcld_bargain_include_promo_page_scripts');
	
}
		
/*******************************
 * Callback function to show the HTML
 *******************************/
if ( ! function_exists( 'qcld_bargain_promo_support_page_callback_func' ) ) {

	function qcld_bargain_promo_support_page_callback_func() {
		
?>
        <div class="qcld-bargain-support">
            <div class="support-btn-main justify-content-center">
                <div class="col text-center">
                    <h2 class="py-3"><?php esc_html_e('Check Out Some of Our Other Works that Might Make Your Website Better', 'woo-minimum-acceptable-price'); ?></h2>
                    <h5><?php esc_html_e('All our Pro Version users get Premium, Guaranteed Quick, One on One Priority Support.', 'woo-minimum-acceptable-price'); ?></h5>
                    <div class="support-btn">
                        <a class="premium-support" href="<?php echo esc_url('https://qc.ticksy.com/'); ?>" target="_blank"><?php esc_html_e('Get Priority Support ', 'woo-minimum-acceptable-price'); ?></a>
                        <a style="width:282px" class="premium-support" href="<?php echo esc_url('https://www.quantumcloud.com/resources/kb-sections/bargain-bot'); ?>" target="_blank"><?php esc_html_e('Online KnowledgeBase', 'woo-minimum-acceptable-price'); ?></a>
                    </div>
                </div>
            </div>
            <div class="qc-column-12" style="margin-top: 12px;">
                <div class="support-btn">
                    
                    <a class="premium-support premium-support-free" href="<?php echo esc_url('https://www.quantumcloud.com/resources/free-support/','woo-minimum-acceptable-price') ?>" target="_blank"><?php esc_html_e('Get Support for Free Version','woo-minimum-acceptable-price') ?></a>
                </div>
            </div>
            <div class="row g-0">

                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.wpbot.pro/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/wp-bot.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.wpbot.pro/'); ?>" target="_blank"><?php esc_html_e('WPBot – ChatBot for WordPress', 'woo-minimum-acceptable-price'); ?></a></h5>
                            <p><?php esc_html_e('WPBot is a ChatBot for any WordPress website that can improve user engagement, answer questions & help generate more leads. Integrated with Google‘s DialogFlow (AI and NLP).', 'woo-minimum-acceptable-price'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-business-directory/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/icon.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-business-directory/'); ?>" target="_blank"><?php esc_html_e('Simple Business Directory', 'woo-minimum-acceptable-price'); ?></a></h5>
                            <p><?php esc_html_e('This innovative and powerful, yet', 'woo-minimum-acceptable-price'); ?><strong> <?php esc_html_e('Simple Multi-purpose Business Directory', 'woo-minimum-acceptable-price'); ?></strong> <?php esc_html_e('WordPress PlugIn allows you to create 
                            comprehensive Lists of Businesses with maps and tap to call features.', 'woo-minimum-acceptable-price'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/slider-hero/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/slider-hero-icon-256x256.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/slider-hero/'); ?>" target="_blank"><?php esc_html_e('Slider Hero', 'woo-minimum-acceptable-price'); ?></a></h5>
                            <p><?php esc_html_e('Slider Hero is a unique slider plugin that allows you to create', 'woo-minimum-acceptable-price'); ?> <strong><?php esc_html_e('Cinematic Product Intro Adverts', 'woo-minimum-acceptable-price'); ?></strong>  <?php esc_html_e('and', 'woo-minimum-acceptable-price'); ?>
                            <strong><?php esc_html_e('Hero sliders', 'woo-minimum-acceptable-price'); ?></strong> <?php esc_html_e('with great Javascript animation effects.', 'woo-minimum-acceptable-price'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-link-directory/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/sld-icon-256x256.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-link-directory/'); ?>" target="_blank"><?php esc_html_e('Simple Link Directory', 'woo-minimum-acceptable-price'); ?></a></h5>
                            <p><?php esc_html_e('Directory plugin with a unique approach! Simple Link Directory is an advanced WordPress Directory plugin for One Page directory and Content Curation solution.', 'woo-minimum-acceptable-price'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->

                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center" >
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/infographic-maker-ilist/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/iList-icon-256x256.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/infographic-maker-ilist/'); ?>" target="_blank"><?php esc_html_e('InfoGraphic Maker – iList', 'woo-minimum-acceptable-price'); ?></a></h5>
                            <p><?php esc_html_e('iList is first of its kind', 'woo-minimum-acceptable-price'); ?> <strong><?php esc_html_e('InfoGraphic maker', 'woo-minimum-acceptable-price'); ?></strong> <?php esc_html_e('WordPress plugin to create Infographics and elegant Lists effortlessly to visualize data. It is a must have content creation and content curation tool.', 'woo-minimum-acceptable-price'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->

                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/woocommerce-chatbot-woowbot/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/logo (1).png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/woocommerce-chatbot-woowbot/'); ?>" target="_blank"><?php esc_html_e('WoowBot WooCommerce ChatBot', 'woo-minimum-acceptable-price'); ?></a></h5>
                            <p><?php esc_html_e('WooWBot is a stand alone WooCommerce Chat Bot with zero configuration or bot training required. This plug and play chatbot also does not require any 3rd party service integration like Facebook.', 'woo-minimum-acceptable-price'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/woocommerce-shop-assistant-jarvis/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/jarvis-icon-256x256.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/woocommerce-shop-assistant-jarvis/'); ?>" target="_blank"><?php esc_html_e('WooCommerce Shop Assistant', 'woo-minimum-acceptable-price'); ?></a></h5>
                            <p><?php esc_html_e('WooCommerce Shop Assistant', 'woo-minimum-acceptable-price'); ?> – <strong><?php esc_html_e('JARVIS', 'woo-minimum-acceptable-price'); ?></strong> <?php esc_html_e('shows recent user activities, provides advanced search, floating cart, featured products, store notifications, order notifications – all in one place for easy access by buyer and make quick decisions.', 'woo-minimum-acceptable-price'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/portfolio-x-plugin/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/portfolio-x-logo-dark-2.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/portfolio-x-plugin/'); ?>" target="_blank"><?php esc_html_e('Portfolio X', 'woo-minimum-acceptable-price'); ?></a></h5>
                            <p><?php esc_html_e('Portfolio X is an advanced, responsive portfolio with streamlined workflow and unique designs and templates to show your works or projects.', 'woo-minimum-acceptable-price'); ?>&nbsp;<strong>
                            <?php esc_html_e('Portfolio Showcase', 'woo-minimum-acceptable-price'); ?></strong> <?php esc_html_e('and', 'woo-minimum-acceptable-price'); ?> <strong><?php esc_html_e('Portfolio Widgets', 'woo-minimum-acceptable-price'); ?></strong> <?php esc_html_e('are included.', 'woo-minimum-acceptable-price'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/woo-tabbed-category-product-listing/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/woo-tabbed-icon-256x256.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/woo-tabbed-category-product-listing/'); ?>" target="_blank"><?php esc_html_e('Woo Tabbed Category Products', 'woo-minimum-acceptable-price'); ?></a></h5>
                            <p><?php esc_html_e('WooCommerce plugin that allows you to showcase your products category wise in tabbed format. This is a unique woocommerce plugin that lets dynaimically load your products in tabs based on your product categories .', 'woo-minimum-acceptable-price'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/knowledgebase-helpdesk/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/knowledgebase-helpdesk.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/knowledgebase-helpdesk/'); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('KnowledgeBase HelpDesk', 'woo-minimum-acceptable-price'); ?></a></h5>
                            <p><p><?php esc_html_e('KnowledgeBase HelpDesk is an advanced Knowledgebase plugin with helpdesk', 'woo-minimum-acceptable-price'); ?><strong>, </strong><?php esc_html_e('glossary and FAQ features all in one. KnowledgeBase HelpDesk is extremely simple and easy to use.', 'woo-minimum-acceptable-price'); ?></p></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/express-shop/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/express-shop.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/express-shop/'); ?>" target="_blank"><?php esc_html_e('Express Shop', 'woo-minimum-acceptable-price'); ?></a></h5>
                            <p><?php esc_html_e('Express Shop is a WooCommerce addon to show all products in one page. User can add products to cart and go to checkout. Filtering and search integrated in single page.', 'woo-minimum-acceptable-price'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/ichart/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/ichart.jpg" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/ichart/'); ?>" target="_blank"><?php esc_html_e('iChart – Easy Charts and Graphs', 'woo-minimum-acceptable-price'); ?></a></h5>
                            <p><?php esc_html_e('Charts and graphs are now easy to build and add to any WordPress page with just a few clicks and shortcode generator. iChart is a Google chartjs implementation to add graphs', 'woo-minimum-acceptable-price'); ?> &amp; 
                            <strong><?php esc_html_e('charts', 'woo-minimum-acceptable-price'); ?></strong> – <?php esc_html_e('directly from WordPress Visual editor.', 'woo-minimum-acceptable-price'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/comment-link-remove/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/comment-link-remove.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/comment-link-remove/'); ?>" target="_blank"><?php esc_html_e('Comment Link Remove', 'woo-minimum-acceptable-price'); ?></a></h5>
                            <p><?php esc_html_e('All in one solution to fight comment spammers. Tired of deleting useless spammy comments from your WordPress blog posts? Comment Link Remove WordPress plugin removes author link and any other links from the user comments.', 'woo-minimum-acceptable-price'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/bargain-bot/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/bargaining-chatbot.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/bargain-bot/'); ?>" target="_blank"><?php esc_html_e('Bargain Bot', 'woo-minimum-acceptable-price'); ?></a></h5>
                            <p><?php esc_html_e('Allow shoppers to Make Their Offer Now with a Bargaining Bot. Win more customers with smart price negotiations. Bargain Bot can work with any WooCommerce website in LightBox mode or as an addon for the WoowBot!', 'woo-minimum-acceptable-price'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/chatbot-addons/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/chatbot-addons.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/chatbot-addons/'); ?>" target="_blank"><?php esc_html_e('ChatBot Addons', 'woo-minimum-acceptable-price'); ?></a></h5>
                            <p><?php esc_html_e('Empower ', 'woo-minimum-acceptable-price'); ?><a href="<?php echo esc_url('https://www.quantumcloud.com/products/chatbot-for-wordpress/'); ?>" target="_blank"><?php esc_html_e('WPBot ', 'woo-minimum-acceptable-price'); ?></a> <?php esc_html_e('and ', 'woo-minimum-acceptable-price'); ?> <a href="<?php echo esc_url('https://www.quantumcloud.com/products/woocommerce-chatbot-woowbot/'); ?>" target="_blank"> <?php esc_html_e('WoowBot', 'woo-minimum-acceptable-price'); ?> </a> <?php esc_html_e(' – Extend Capabilities with AddOns! FaceBook messenger, white label and more!', 'woo-minimum-acceptable-price'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/directory-addons/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/directory-addons.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/directory-addons/'); ?>" target="_blank"><?php esc_html_e('Directory AddOns', 'woo-minimum-acceptable-price'); ?></a></h5>
                            <p><?php esc_html_e('Empower ', 'woo-minimum-acceptable-price'); ?><a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-link-directory/'); ?>" target="_blank"><?php esc_html_e('Simple Link Directory ', 'woo-minimum-acceptable-price'); ?></a> <?php esc_html_e('and ', 'woo-minimum-acceptable-price'); ?> <a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-business-directory/'); ?>" target="_blank"> <?php esc_html_e('Simple Business Directory ', 'woo-minimum-acceptable-price'); ?> </a> <?php esc_html_e(' Pro  – Extend Capabilities with AddOns!', 'woo-minimum-acceptable-price'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/image-tools-for-wordpress/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/image-tools-pro.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/image-tools-for-wordpress/'); ?>" target="_blank"><?php esc_html_e('Image Tools Pro', 'woo-minimum-acceptable-price'); ?></a></h5>
                            <p><?php esc_html_e('Image Tools Pro adds an arsenal of ', 'woo-minimum-acceptable-price'); ?> <b><?php esc_html_e('practical tools', 'woo-minimum-acceptable-price'); ?></b>  <?php esc_html_e(' for your WordPress Images to make your life easier.', 'woo-minimum-acceptable-price'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/live-chat/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/live-chat-wordpress-plugin.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/live-chat/'); ?>" target="_blank"><?php esc_html_e('Live Chat plugin for WordPress', 'woo-minimum-acceptable-price'); ?></a></h5>
                            <p><?php esc_html_e('This feature rich, ', 'woo-minimum-acceptable-price'); ?> <b><?php esc_html_e('native Live Chat plugin for WordPress', 'woo-minimum-acceptable-price'); ?></b>  <?php esc_html_e('plugin can work with the WPBot or work', 'woo-minimum-acceptable-price'); ?> <b><?php esc_html_e('stand alone.', 'woo-minimum-acceptable-price'); ?></b> <?php esc_html_e(' Does not require external server or complex set up.', 'woo-minimum-acceptable-price'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/support-ticket-for-knowledgebase/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/support-ticket.jpg" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/support-ticket-for-knowledgebase/'); ?>" target="_blank"><?php esc_html_e('WordPress Support Ticket', 'woo-minimum-acceptable-price'); ?></a></h5>
                            <p><?php esc_html_e('Provide complete helpdesk ticket system on your website. Easy to configure and AJAX based ticket plugin for WordPress.', 'woo-minimum-acceptable-price'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/premium-themes.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/'); ?>" target="_blank"><?php esc_html_e('Premium WordPress Themes', 'woo-minimum-acceptable-price'); ?></a></h5>
                            <p><b><?php esc_html_e('Premium WordPress Themes', 'woo-minimum-acceptable-price'); ?></b> <?php esc_html_e('that add perceptible value to your business and website.', 'woo-minimum-acceptable-price'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->




            </div>
            <!--qc row-->

            <div class="qcld-bargain-sup_wrap">

                <div class="qcld-bargain-sup_title">
                    <h3><?php esc_html_e('Available on our ', 'woo-minimum-acceptable-price'); ?> <a href="<?php echo esc_url('https://www.dna88.com/'); ?>"> <?php esc_html_e('dna88.com', 'woo-minimum-acceptable-price'); ?> </a> <?php esc_html_e('website', 'woo-minimum-acceptable-price'); ?></h3>
                </div>
                <div class="row g-0">

                    <div class="col"><!-- col-sm-4 -->
                        <!-- Feature Box 1 -->
                        <div class="card text-center"  >
                            
                            <a href="<?php echo esc_url('https://www.dna88.com/product/button-menu/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/button-menu.png" alt=""></a>
                            <div class="card-body">
                                <h5><a href="<?php echo esc_url('https://www.dna88.com/product/button-menu/'); ?>" target="_blank"><?php esc_html_e('Button Menu', 'woo-minimum-acceptable-price'); ?></a></h5>
                                <p><?php esc_html_e('Show your WordPress navigation menus anywhere on any page as buttons easily using a shortcode. Supports unlimited sub menu levels with icons, animations and complete control over the colors of the individual icons.', 'woo-minimum-acceptable-price'); ?></p>

                            </div>
                        </div>
                    </div><!--/col-sm-4 -->
                    
                    <div class="col"><!-- col-sm-4 -->
                        <!-- Feature Box 1 -->
                        <div class="card text-center"  >
                            
                            <a href="<?php echo esc_url('https://www.dna88.com/product/notice-pro/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/notice-pro.png" alt=""></a>
                            <div class="card-body">
                                <h5><a href="<?php echo esc_url('https://www.dna88.com/product/notice-pro/'); ?>" target="_blank"><?php esc_html_e('WordPress Notifications', 'woo-minimum-acceptable-price'); ?></a></h5>
                                <p><?php esc_html_e('Display Sitewide notices elegantly with beautiful action button. The Notice Pro version supports unlimited, concurrent sitewide notices that can be defined to display for specific user roles on specific pages.', 'woo-minimum-acceptable-price'); ?></p>

                            </div>
                        </div>
                    </div><!--/col-sm-4 -->
                    
                    <div class="col"><!-- col-sm-4 -->
                        <!-- Feature Box 1 -->
                        <div class="card text-center"  >
                            
                            <a href="<?php echo esc_url('https://www.dna88.com/product/highlight/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/highlight.png" alt=""></a>
                            <div class="card-body">
                                <h5><a href="<?php echo esc_url('https://www.dna88.com/product/highlight/'); ?>" target="_blank"><?php esc_html_e('Highlight Sitewide Notice, Text, Button Menu', 'woo-minimum-acceptable-price'); ?></a></h5>
                                <p><?php esc_html_e('Add a sitewide notice or small message bar to the top or bottom of each page of your website to display notice messages or notification such as sales, notices, coupons and any text messages.', 'woo-minimum-acceptable-price'); ?> </p>

                            </div>
                        </div>
                    </div><!--/col-sm-4 -->
                    
                    <div class="col"><!-- col-sm-4 -->
                        <!-- Feature Box 1 -->
                        <div class="card text-center"  >
                            
                            <a href="<?php echo esc_url('https://www.dna88.com/product/video-connect/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/video-connect.png" alt=""></a>
                            <div class="card-body">
                                <h5><a href="<?php echo esc_url('https://www.dna88.com/product/video-connect/'); ?>" target="_blank"><?php esc_html_e('Video Connect', 'woo-minimum-acceptable-price'); ?></a></h5>
                                <p><?php esc_html_e('Featured Product videos for Woocommerce, Video widget, Videos with contact form 7. Use videos to explain your products or services and connect with your users. All in one Video solution for WordPress.', 'woo-minimum-acceptable-price'); ?> </p>

                            </div>
                        </div>
                    </div><!--/col-sm-4 -->
                    
                    <div class="col"><!-- col-sm-4 -->
                        <!-- Feature Box 1 -->
                        <div class="card text-center"  >
                            
                            <a href="<?php echo esc_url('https://www.dna88.com/product/seo-help-pro/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/seo-help.png" alt=""></a>
                            <div class="card-body">
                                <h5><a href="<?php echo esc_url('https://www.dna88.com/product/seo-help-pro/'); ?>" target="_blank"><?php esc_html_e('SEO Help', 'woo-minimum-acceptable-price'); ?></a></h5>
                                <p><?php esc_html_e('SEO Help is a unique WordPress plugin to help you write better Link Bait titles. The included LinkBait title generator will take the WordPress post title as Subject and generate alternative ClickBait titles for you to choose from.', 'woo-minimum-acceptable-price'); ?></p>

                            </div>
                        </div>
                    </div><!--/col-sm-4 -->
                    
                    <div class="col"><!-- col-sm-4 -->
                        <!-- Feature Box 1 -->
                        <div class="card text-center"  >
                            
                            <a href="<?php echo esc_url('https://www.dna88.com/product/voice-widgets/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/voice-widgets-for-wordPress.png" alt=""></a>
                            <div class="card-body">
                                <h5><a href="<?php echo esc_url('https://www.dna88.com/product/voice-widgets/'); ?>" target="_blank"><?php esc_html_e('Voice Widgets', 'woo-minimum-acceptable-price'); ?></a></h5>
                                <p><?php esc_html_e('Get voice messages with your forms and increase user conversions with Voice widgets. Record voice messages with your WordPress forms – CF7, WPForms, BBPress, Blog Comments, and Woocommerce Product Reviews. Supports standalone voice form.', 'woo-minimum-acceptable-price'); ?> </p>

                            </div>
                        </div>
                    </div><!--/col-sm-4 -->

                </div><!--/row -->
                
            </div>

        </div>



			
		
<?php
            
       
    }
}


/*******************************
 * Handle Ajex Request for Form Processing
 *******************************/
add_action( 'wp_ajax_qcld_bargain_process_qc_promo_form', 'qcld_bargain_process_qc_promo_form' );

if( !function_exists('qcld_bargain_process_qc_promo_form') ){

    function qcld_bargain_process_qc_promo_form(){

        check_ajax_referer( 'woo-minimum-acceptable-price', 'security');
        
        $data['status']   = 'failed';
        $data['message']  = esc_html__('Problem in processing your form submission request! Apologies for the inconveniences.<br> 
Please email to <span style="color:#22A0C9;font-weight:bold !important;font-size:14px "> quantumcloud@gmail.com </span> with any feedback. We will get back to you right away!', 'woo-minimum-acceptable-price');

        $name         = isset($_POST['post_name']) ? trim(sanitize_text_field($_POST['post_name'])) : '';
        $email        = isset($_POST['post_email']) ? trim(sanitize_email($_POST['post_email'])) : '';
        $subject      = isset($_POST['post_subject']) ? trim(sanitize_text_field($_POST['post_subject'])) : '';
        $message      = isset($_POST['post_message']) ? trim(sanitize_text_field($_POST['post_message'])) : '';
        $plugin_name  = isset($_POST['post_plugin_name']) ? trim(sanitize_text_field($_POST['post_plugin_name'])) : '';

        if( $name == "" || $email == "" || $subject == "" || $message == "" )
        {
            $data['message'] = esc_html('Please fill up all the requried form fields.', 'woo-minimum-acceptable-price');
        }
        else if ( filter_var($email, FILTER_VALIDATE_EMAIL) === false ) 
        {
            $data['message'] = esc_html('Invalid email address.', 'woo-minimum-acceptable-price');
        }
        else
        {

            //build email body

            $bodyContent = "";
                
            $bodyContent .= "<p><strong>".esc_html('Support Request Details:', 'woo-minimum-acceptable-price')."</strong></p><hr>";

            $bodyContent .= "<p>".esc_html('Name', 'woo-minimum-acceptable-price')." : ".$name."</p>";
            $bodyContent .= "<p>".esc_html('Email', 'woo-minimum-acceptable-price')." : ".$email."</p>";
            $bodyContent .= "<p>".esc_html('Subject', 'woo-minimum-acceptable-price')." : ".$subject."</p>";
            $bodyContent .= "<p>".esc_html('Message', 'woo-minimum-acceptable-price')." : ".$message."</p>";

            $bodyContent .= "<p>".esc_html('Sent Via the Plugin', 'woo-minimum-acceptable-price')." : ".$plugin_name."</p>";

            $bodyContent .="<p></p><p>".esc_html('Mail sent from:', 'woo-minimum-acceptable-price')." <strong>".get_bloginfo('name')."</strong>, URL: [".get_bloginfo('url')."].</p>";
            $bodyContent .="<p>".esc_html('Mail Generated on:', 'woo-minimum-acceptable-price')." " . date("F j, Y, g:i a") . "</p>";           
            
            $toEmail = "quantumcloud@gmail.com"; //Receivers email address
            //$toEmail = "qc.kadir@gmail.com"; //Receivers email address

            //Extract Domain
            $url = get_site_url();
            $url = parse_url($url);
            $domain = $url['host'];
            

            $fakeFromEmailAddress = "wordpress@" . $domain;
            
            $to = $toEmail;
            $body = $bodyContent;
            $headers = array();
            $headers[] = 'Content-Type: text/html; charset=UTF-8';
            $headers[] = 'From: '.esc_attr($name, 'woo-minimum-acceptable-price').' <'.esc_attr($fakeFromEmailAddress, 'woo-minimum-acceptable-price').'>';
            $headers[] = 'Reply-To: '.esc_attr($name, 'woo-minimum-acceptable-price').' <'.esc_attr($email, 'woo-minimum-acceptable-price').'>';

            $finalSubject = esc_html('From Plugin Support Page:', 'woo-minimum-acceptable-price')." " . esc_attr($subject, 'woo-minimum-acceptable-price');
            
            $result = wp_mail( $to, $finalSubject, $body, $headers );

            if( $result )
            {
                $data['status'] = 'success';
                $data['message'] = esc_html__('Your email was sent successfully. Thanks!', 'woo-minimum-acceptable-price');
            }

        }

        ob_clean();

        
        echo json_encode($data);
    
        die();
    }
}