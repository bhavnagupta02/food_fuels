<?php
global $etheme_responsive;
$fd = etheme_get_option('footer_demo');
$fbg = etheme_get_option('footer_bg');
$fcolor = etheme_get_option('footer_text_color');
$ft = '';
$ft = apply_filters('custom_footer_filter', $ft);
$custom_footer = etheme_get_custom_field('custom_footer', et_get_page_id());
/**
 * Thankyou page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if ($order) :
    ?>

    <?php if ($order->has_status('failed')) : ?>

        <p><?php _e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'woocommerce'); ?></p>

        <p><?php
        if (is_user_logged_in())
            _e('Please attempt your purchase again or go to your account page.', 'woocommerce');
        else
            _e('Please attempt your purchase again.', 'woocommerce');
        ?></p>

        <p>
            <a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="button pay"><?php _e('Pay', 'woocommerce') ?></a>
        <?php if (is_user_logged_in()) : ?>
                <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="button pay"><?php _e('My Account', 'woocommerce'); ?></a>
            <?php endif; ?>
        </p>

    <?php else : ?>
        <div class="order-wrapper">
            <div class="thankyou-text">
                <h1>Thank you for your order.</h1>
                <h2>We are processing it now. You will receive an email confirmation shortly.</h2>
                <p>Visit order status to make changes to your order, track your shipment and more. We recommend you print this page write down your Order Number below.</p>
            </div>
            <div class="col-sm-6">
                <h2 class="order-heading">Order Details</h2>

                <table class="order-details">
                    <tr>
                        <td><?php _e('Order Date:', 'woocommerce'); ?></td>
                        <td><?php echo date('d M Y', strtotime($order->order_date)); ?></td>
                    </tr>
                    <tr>
                        <td><?php _e('Payment Method:', 'woocommerce'); ?></td>
                        <td><?php echo $order->get_formatted_order_total(); ?> on <?php echo $order->payment_method_title; ?></td>
                    </tr>
                    <tr>
                        <td><?php _e('Order Number:', 'woocommerce'); ?></td>
                        <td><?php echo $order->get_order_number(); ?></td>
                    </tr>
                    <tr>
                        <td><?php _e('Shipping Address:', 'woocommerce'); ?></td>
                        <td><?php echo $order->get_formatted_shipping_address(); ?></td>
                    </tr>
                    <tr>
                        <td><?php _e('Billing Address:', 'woocommerce'); ?></td>
                        <td><?php echo $order->get_formatted_billing_address(); ?></td>
                    </tr>
                    <tr>
                        <td><?php _e('Email Confirmation Address:', 'woocommerce'); ?></td>
                        <td></td>
                    </tr>                    
                </table>
            </div>
            <div class="col-sm-6"><h2 class="order-heading">Questions About Your Order?</h2>
                <table class="order-details">
                    <tr>
                        <td><?php _e('Email:', 'woocommerce'); ?></td>
                        <td><?php echo get_post_meta($order->id, 'shipping_email', true); ?></td>
                    </tr>
                    <tr>
                        <td><?php _e('Telephone:', 'woocommerce'); ?></td>
                        <td><?php echo get_post_meta($order->id, 'shipping_phone', true); ?></td>
                    </tr>
                </table>

            </div>
            <div class="clear"></div>
            <a class="button" href="<?php echo get_permalink(woocommerce_get_page_id('shop')); ?>">Continue Shopping</a>
        </div>
    <?php endif; ?>
    <div class="order-wrapper" style="padding:20px">
    <?php //do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?>
        <?php do_action('woocommerce_thankyou_custom', $order->id); ?>
    </div>
    <?php else : ?>

    <p><?php echo apply_filters('woocommerce_thankyou_order_received_text', __('Thank you. Your order has been received.', 'woocommerce'), null); ?></p>

<?php endif; ?>

<div class="order-wrapper survey">
    <div class="col-sm-9">
        <h1>Customer Survey</h1>
        <h2>Your Opinion Counts and we Reward you for it!</h2>
        <p>We would love to hear about your shopping experience at CosmeaGardens.com. Upon completing the survey you will bo given a discount coupon for your next order.</p>
    </div>
    <div class="col-sm-3 rightimg"><a href="#"><img src="<?php echo get_stylesheet_directory_uri(); ?>/survey.jpg" /></a></div>
    <div class="clear"></div>
</div>
<div class="order-wrapper" style="margin:15px 0px; padding:0">
<?php echo do_shortcode('[mc4wp_form id="17667"]'); ?>
</div>
<!-------------------custom----------------->
<div class="foots">
    <footer class="main-footer main-footer-<?php echo esc_attr($ft); ?> text-color-<?php echo $fcolor; ?>" <?php if ($fbg != ''): ?>style="background-color:<?php echo $fbg; ?>"<?php endif; ?>>
        <div class="container">
<?php echo et_get_block($custom_footer); ?>  
        </div>

        <div class="container">
            <p class="payment_heading">SECURE PAYMENT WITH</p>
            <div class="carts"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/carts.png"></div>
            <div class="links_row">
                <a href="#">Site Feedback</a>|
                <a href="#">Bookmark this Site</a>|
                <a href="#">Partners Links</a>|
                <a href="#">Sitemap</a>|
                <a href="#">Customer Testimonials</a>
            </div>
            <div class="links_row">
                <span class="custom_copyright">Copyright &copy; 2016 &nbsp;</span>
                <a href="#">CosmeaGardens.com</a>|
                <a href="#">All Rights Reserved</a>|
                <a href="#">Send Flowers to Cyprus</a>|
                <a href="#">Privacy</a>|<a href="#">Contact Us</a>
            </div>
            <div class="norton">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/norton_logo.png">
            </div>
        </div>

        <div class="container">
            <div class="footer_text">
                <p class="footer_text_heading">
                    Send Flowers to Cyprus from CosmeaGardens.com
                </p>
                <p class="footer_text_row_1">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                    when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                    It has survived not only five centuries, but also the leap into electronic typesetting, remaining 
                    essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing 
                    Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including 
                    versions of Lorem Ipsum.Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                    when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                    It has survived not only five centuries, but also the leap into electronic typesetting, remaining 
                    essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing 
                    Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including 
                    versions of Lorem Ipsum.
                </p>
                <p class="footer_text_row_2">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                    when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                    It has survived not only five centuries, but also the leap into electronic typesetting, remaining 
                    essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing 
                    Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including 
                    versions of Lorem Ipsum.Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                    when an unknown printer.
                </p>


            </div>
        </div>

    </footer>



</div> <!-- page wrapper -->
</div> <!-- st-content-inner -->
</div>
</div>
<?php do_action('after_page_wrapper'); ?>



<?php if (etheme_get_option('loader')): ?>
    <script type="text/javascript">
        if (jQuery(window).width() > 1200) {
            jQuery("body").queryLoader2({
                barColor: "#111",
                backgroundColor: "#fff",
                percentage: true,
                barHeight: 2,
                completeAnimation: "grow",
                minimumTime: 500,
                onLoadComplete: function () {
                    jQuery('body').addClass('page-loaded');
                }
            });
        }
    </script>
<?php endif; ?>

<?php if (etheme_get_option('to_top')): ?>
    <div id="back-top" class="back-top <?php if (!etheme_get_option('to_top_mobile')): ?>visible-lg<?php endif; ?> bounceOut">
        <a href="#top">
            <span></span>
        </a>
    </div>
<?php endif ?>

<div>
<?php
/* Always have wp_footer() just before the closing </body>
 * tag of your theme, or you will break many plugins, which
 * generally use this hook to reference JavaScript files.
 */

wp_footer();
?>
</div>
</div>
