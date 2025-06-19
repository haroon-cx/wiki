<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( ! function_exists( 'anesta_woocommerce_get_css' ) ) {
	add_filter( 'anesta_filter_get_css', 'anesta_woocommerce_get_css', 10, 2 );
	function anesta_woocommerce_get_css( $css, $args ) {

		if ( isset( $css['fonts'] ) && isset( $args['fonts'] ) ) {
			$fonts         = $args['fonts'];
			$css['fonts'] .= <<<CSS

.woocommerce .checkout table.shop_table .product-name .variation,
.woocommerce .shop_table.order_details td.product-name .variation {
	{$fonts['p_font-family']}
}
.woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price,
.woocommerce ul.products li.product .post_header, .woocommerce-page ul.products li.product .post_header,
.single-product div.product .woocommerce-tabs .wc-tabs li a,
.woocommerce .shop_table th,
.woocommerce span.onsale,
.woocommerce div.product p.price, .woocommerce div.product span.price,
.woocommerce div.product .summary .stock,
.woocommerce #reviews #comments ol.commentlist li .comment-text p.meta strong,
.woocommerce-page #reviews #comments ol.commentlist li .comment-text p.meta strong,
.woocommerce table.cart td.product-name a, .woocommerce-page table.cart td.product-name a, 
.woocommerce #content table.cart td.product-name a, .woocommerce-page #content table.cart td.product-name a,
.woocommerce .checkout table.shop_table .product-name,
.woocommerce .shop_table.order_details td.product-name,
.woocommerce .order_details li strong,
.woocommerce-MyAccount-navigation,
.woocommerce-MyAccount-content .woocommerce-Address-title a {
	{$fonts['h5_font-family']}
}
.woocommerce div.product .woocommerce-tabs h2, .woocommerce #content div.product .woocommerce-tabs h2,
.woocommerce-page div.product .woocommerce-tabs h2, .woocommerce-page #content div.product .woocommerce-tabs h2,
.woocommerce div.product .woocommerce-tabs h3, .woocommerce #content div.product .woocommerce-tabs h3,
.woocommerce-page div.product .woocommerce-tabs h3, .woocommerce-page #content div.product .woocommerce-tabs h3,

.woocommerce ul.products li.product .woocommerce-loop-category__title, 
.woocommerce ul.products li.product .woocommerce-loop-product__title, 
.woocommerce ul.products li.product h3, 
.woocommerce-page ul.products li.product .woocommerce-loop-category__title, 
.woocommerce-page ul.products li.product .woocommerce-loop-product__title, 
.woocommerce-page ul.products li.product h3 {
	{$fonts['h5_font-family']}
	font-size: var(--theme-font-h5_font-size); /* replace with {$fonts['h5_font-size']} */
	{$fonts['h5_font-weight']}
	{$fonts['h5_font-style']}
	{$fonts['h5_line-height']}
	{$fonts['h5_text-decoration']}
	{$fonts['h5_text-transform']}
	{$fonts['h5_letter-spacing']}
}
.woocommerce ul.products li.product .button, .woocommerce div.product form.cart .button,
.woocommerce .woocommerce-message .button,
.woocommerce #review_form #respond p.form-submit input[type="submit"],
.woocommerce-page #review_form #respond p.form-submit input[type="submit"],
.woocommerce .button, .woocommerce-page .button,
.woocommerce a.button,
.woocommerce button.button,
.woocommerce input.button,
.woocommerce #respond input#submit,
.woocommerce .hidden-title-form a.hide-title-form,
.woocommerce input[type="button"], .woocommerce-page input[type="button"],
.woocommerce input[type="submit"], .woocommerce-page input[type="submit"],
#btn-buy {
	{$fonts['button_font-family']}
	font-size: var(--theme-font-button_font-size); /* replace with {$fonts['button_font-size']} */
	{$fonts['button_font-weight']}
	{$fonts['button_font-style']}
	{$fonts['button_line-height']}
	{$fonts['button_text-decoration']}
	{$fonts['button_text-transform']}
	{$fonts['button_letter-spacing']}
}
.woocommerce-input-wrapper,
.woocommerce table.cart td.actions .coupon .input-text,
.woocommerce #content table.cart td.actions .coupon .input-text,
.woocommerce-page table.cart td.actions .coupon .input-text,
.woocommerce-page #content table.cart td.actions .coupon .input-text {
	{$fonts['input_font-family']}
	{$fonts['input_font-size']}
	{$fonts['input_font-weight']}
	{$fonts['input_font-style']}
	{$fonts['input_line-height']}
	{$fonts['input_text-decoration']}
	{$fonts['input_text-transform']}
	{$fonts['input_letter-spacing']}
}
.woocommerce form .form-row input.input-text, 
.woocommerce form .form-row textarea {
	{$fonts['input_line-height']}
}
.woocommerce ul.products li.product .post_header .post_tags,
.woocommerce div.product .product_meta span > a, .woocommerce div.product .product_meta span > span,
.woocommerce div.product form.cart .reset_variations,
.woocommerce #reviews #comments ol.commentlist li .comment-text p.meta time, .woocommerce-page #reviews #comments ol.commentlist li .comment-text p.meta time {
	{$fonts['info_font-family']}
}

.list_products_header .page-title,
.woocommerce div.product .product_title,
.woocommerce div.product p.price, 
.woocommerce div.product span.price,
.single-product .related > h2,
.single-product .upsells > h2,
.woocommerce .cart-collaterals h2,
.woocommerce-page .cart-collaterals h2,
.woocommerce #review_form #respond #reply-title {
	{$fonts['h3_font-family']}
	font-size: var(--theme-font-h3_font-size); /* replace with {$fonts['h3_font-size']} */
	{$fonts['h3_font-weight']}
	{$fonts['h3_font-style']}
	{$fonts['h3_line-height']}
	{$fonts['h3_text-decoration']}
	{$fonts['h3_text-transform']}
	{$fonts['h3_letter-spacing']}
}

CSS;
		}

		return $css;
	}
}

// Load skin-specific functions
$fdir = anesta_get_file_dir( 'plugins/woocommerce/woocommerce-skin.php' );
if ( ! empty( $fdir ) ) {
	require_once $fdir;
}
