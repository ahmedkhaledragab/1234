<?php
/**
 * Contact section.
 *
 * @package Insectra
 */
?>
<section class="ins-section ins-contact" id="contact">
    <div class="container contact-grid">
        <div class="contact-info">
            <span class="eyebrow"><?php esc_html_e( 'Get In Touch', 'insectra' ); ?></span>
            <h2 class="section-title"><?php esc_html_e( 'Request a free pest inspection', 'insectra' ); ?></h2>
            <p><?php esc_html_e( 'Fill in the form and our team will reach out within 1 hour during business hours.', 'insectra' ); ?></p>
            <ul class="info-list">
                <li><span class="ic"><i class="fa-solid fa-location-dot"></i></span><div><strong><?php esc_html_e( 'Address', 'insectra' ); ?></strong><span><?php echo esc_html( insectra_tr( 'insectra_address', '123 Pest Control St., NY' ) ); ?></span></div></li>
                <li><span class="ic"><i class="fa-solid fa-phone"></i></span><div><strong><?php esc_html_e( 'Phone', 'insectra' ); ?></strong><a href="tel:<?php echo esc_attr( insectra_tr( 'insectra_phone', '' ) ); ?>"><?php echo esc_html( insectra_tr( 'insectra_phone', '+1 (800) 555-1234' ) ); ?></a></div></li>
                <li><span class="ic"><i class="fa-regular fa-envelope"></i></span><div><strong><?php esc_html_e( 'Email', 'insectra' ); ?></strong><a href="mailto:<?php echo esc_attr( insectra_tr( 'insectra_email', '' ) ); ?>"><?php echo esc_html( insectra_tr( 'insectra_email', 'info@insectra.com' ) ); ?></a></div></li>
            </ul>
        </div>

        <form class="ins-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
            <input type="hidden" name="action" value="insectra_contact">
            <?php wp_nonce_field( 'insectra_contact', 'insectra_contact_nonce' ); ?>

            <div class="row">
                <label class="field">
                    <i class="fa-regular fa-user field-icon"></i>
                    <input type="text" name="name" required placeholder="<?php esc_attr_e( 'Your Name', 'insectra' ); ?>">
                </label>
                <label class="field">
                    <i class="fa-regular fa-envelope field-icon"></i>
                    <input type="email" name="email" required placeholder="<?php esc_attr_e( 'Your Email', 'insectra' ); ?>">
                </label>
            </div>
            <div class="row">
                <label class="field">
                    <i class="fa-solid fa-phone field-icon"></i>
                    <input type="tel" name="phone" placeholder="<?php esc_attr_e( 'Phone Number', 'insectra' ); ?>">
                </label>
                <label class="field">
                    <i class="fa-solid fa-shield field-icon"></i>
                    <select name="service">
                        <option value=""><?php esc_html_e( 'Select Service', 'insectra' ); ?></option>
                        <option><?php esc_html_e( 'Termite Control', 'insectra' ); ?></option>
                        <option><?php esc_html_e( 'Mosquito Control', 'insectra' ); ?></option>
                        <option><?php esc_html_e( 'Rodent Control', 'insectra' ); ?></option>
                        <option><?php esc_html_e( 'Disinfection', 'insectra' ); ?></option>
                    </select>
                </label>
            </div>
            <label class="field full">
                <i class="fa-regular fa-comment field-icon"></i>
                <textarea name="message" rows="5" placeholder="<?php esc_attr_e( 'Tell us about your pest problem…', 'insectra' ); ?>"></textarea>
            </label>
            <button type="submit" class="ins-btn ins-btn-primary">
                <?php esc_html_e( 'Send Request', 'insectra' ); ?>
                <i class="fa-solid fa-arrow-right arrow"></i>
            </button>
        </form>
    </div>
</section>

<?php
/**
 * Process the contact form (basic; replace with Contact Form 7/Forminator in production).
 */
if ( ! function_exists( 'insectra_handle_contact' ) ) {
    function insectra_handle_contact() {
        if ( ! isset( $_POST['insectra_contact_nonce'] ) || ! wp_verify_nonce( $_POST['insectra_contact_nonce'], 'insectra_contact' ) ) {
            wp_safe_redirect( wp_get_referer() ?: home_url( '/' ) ); exit;
        }
        $to      = get_option( 'admin_email' );
        $name    = sanitize_text_field( $_POST['name']    ?? '' );
        $email   = sanitize_email   ( $_POST['email']    ?? '' );
        $phone   = sanitize_text_field( $_POST['phone']   ?? '' );
        $service = sanitize_text_field( $_POST['service'] ?? '' );
        $message = sanitize_textarea_field( $_POST['message'] ?? '' );
        $body = "Name: $name\nEmail: $email\nPhone: $phone\nService: $service\n\n$message";
        wp_mail( $to, 'New Inspection Request', $body, array( 'Reply-To: ' . $email ) );
        wp_safe_redirect( add_query_arg( 'sent', '1', wp_get_referer() ?: home_url( '/' ) ) ); exit;
    }
    add_action( 'admin_post_insectra_contact', 'insectra_handle_contact' );
    add_action( 'admin_post_nopriv_insectra_contact', 'insectra_handle_contact' );
}
