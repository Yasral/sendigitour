<?php
/**
 * Wheel Submission template.
 *
 * @package Wheel_Of_Life
 */

defined( 'ABSPATH' ) || exit;

get_header();

global $post;
$chart_data    = get_post_meta( $post->ID, 'chartData', true );
$chart_options = get_post_meta( $post->ID, 'chartOptions', true );
$wheel_id      = get_post_meta( $post->ID, 'wheelId', true );
$wheel_title   = $wheel_id ? get_the_title( $wheel_id ) : __( 'My Wheel of Life', 'wheel-of-life' );
$chart_type    = get_post_meta( $post->ID, 'chartType', true );
$chart_type    = isset( $chart_type ) && '' != $chart_type ? $chart_type : 'polar-chart';
$wheel_CTA     = apply_filters( 'get_wheel_CTA_meta', array(), $wheel_id );



if ( ! empty( $wheel_CTA ) && $wheel_CTA['ctaType'] === 'no-cta' ) { ?>
	<div class="wlof-main-wrapper">
	<div class="wlof-title-wrap">
		<h2 class='wlof-title'><?php echo esc_html( $wheel_title ); ?></h2>
		<span class='wlof-published-on'>
			<?php esc_html_e( 'Assessment taken on: ', 'wheel-of-life' ); ?>
			<time datetime='<?php echo get_the_date( 'c' ); ?>' itemprop='datePublished'><?php echo get_the_date(); ?></time>
		</span>
	</div>
	<div class='wlof-life-sbwl'>
		<div id='submission-chart' data-chartData='<?php echo json_encode( $chart_data ); ?>' data-chartOption='<?php echo json_encode( $chart_options ); ?>' data-reportLink='<?php echo esc_url( get_the_permalink() ); ?>' data-reportTitle='<?php echo esc_attr( get_the_title() ); ?>' data-chartType='<?php echo esc_attr( $chart_type ); ?>' data-wheelID='<?php echo absint( $wheel_id ); ?>'>
		</div>
	</div>
	<?php
} elseif ( ! empty( $wheel_CTA ) && $wheel_CTA['ctaType'] === 'page-cta' ) {
	$cta_data = json_decode( json_encode( $wheel_CTA, true ) );

	// cta
	$cta_title = isset( $cta_data->title ) ? esc_html( $cta_data->title ) : '';
	$cta_desc  = isset( $cta_data->description ) ? esc_html( $cta_data->description ) : '';
	$pb_label  = isset( $cta_data->btn_label ) ? esc_html( $cta_data->btn_label ) : '';
	$pb_link   = isset( $cta_data->page->value ) ? esc_url( get_page_link( $cta_data->page->value ) ) : '';
	$pb_newtab = isset( $cta_data->openInTab ) ? rest_sanitize_boolean( $cta_data->openInTab ) : false;


	// cta background
	$bg_type           = isset( $cta_data->customizer->background->background_type ) ? esc_html( $cta_data->customizer->background->background_type ) : '';
	$bg_positionX      = $cta_data->customizer->background->background_image->x;
	$bg_positionY      = $cta_data->customizer->background->background_image->y;
	$bg_repeate        = isset( $cta_data->customizer->background->background_repeat ) ? $cta_data->customizer->background->background_repeat : '';
	$bg_size           = isset( $cta_data->customizer->background->background_size ) ? $cta_data->customizer->background->background_size : '';
	$bg_attachement    = isset( $cta_data->customizer->background->background_attachment ) ? $cta_data->customizer->background->background_attachment : '';
	$bg_img_url        = isset( $cta_data->customizer->background->background_image->url ) ? $cta_data->customizer->background->background_image->url : '';
	$bg_solid_color    = isset( $cta_data->customizer->background->backgroundColor->default->color ) ? $cta_data->customizer->background->backgroundColor->default->color : '';
	$bg_gradient_color = isset( $cta_data->customizer->background->gradient ) ? $cta_data->customizer->background->gradient : '';
	$x_axis            = $bg_positionX * 100 . '% ';
	$y_axis            = $bg_positionY * 100 . '% ';
	$cta_overlay_color = $cta_data->customizer->background->overlayColor->default->color;

	if ( $bg_type === 'image' ) {
		$cta_background = 'url(' . $bg_img_url . '); background-repeat:' . $bg_repeate . '; background-size:' . $bg_size . '; background-attachment:' . $bg_attachement . '; background-position:' . $x_axis . '% ' . $y_axis . '%;';
	} elseif ( $bg_type === 'gradient' ) {
		$cta_background = $bg_gradient_color;
	} else {
		$cta_background = $bg_solid_color;
	}
	// spacing
	$cta_margin  = $cta_data->customizer->margin->top . ' ' . $cta_data->customizer->margin->right . ' ' . $cta_data->customizer->margin->bottom . ' ' . $cta_data->customizer->margin->left;
	$cta_padding = $cta_data->customizer->padding->top . ' ' . $cta_data->customizer->padding->right . ' ' . $cta_data->customizer->padding->bottom . ' ' . $cta_data->customizer->padding->left;

	// align
	$cta_align = isset( $cta_data->customizer->alignment ) ? $cta_data->customizer->alignment : '';

	// cta title
	$cta_title_size  = isset( $cta_data->customizer->fontSize ) ? $cta_data->customizer->fontSize : '';
	$cta_title_color = isset( $cta_data->customizer->fontColor ) ? $cta_data->customizer->fontColor : '';

	// cta desc
	$cta_desc_size  = isset( $cta_data->customizer->descFontSize ) ? $cta_data->customizer->descFontSize : '';
	$cta_desc_color = isset( $cta_data->customizer->descFontColor ) ? $cta_data->customizer->descFontColor : '';

	// buttons
	// primary button
	$pbFontSize       = isset( $cta_data->customizer->pbFontSize ) ? $cta_data->customizer->pbFontSize : '';
	$pbFontColor      = isset( $cta_data->customizer->pbfontColors->pbfontColor ) ? $cta_data->customizer->pbfontColors->pbfontColor : '';
	$pbBg             = isset( $cta_data->customizer->pbBgColors->pbBgColor ) ? $cta_data->customizer->pbBgColors->pbBgColor : '';
	$pbBorderRadius   = isset( $cta_data->customizer->pbBorderRadius ) ? $cta_data->customizer->pbBorderRadius : '';
	$pbfontHoverColor = isset( $cta_data->customizer->pbfontColors->pbfontHoverColor ) ? $cta_data->customizer->pbfontColors->pbfontHoverColor : '';
	$pbBgHoverColor   = isset( $cta_data->customizer->pbBgColors->pbBgHoverColor ) ? $cta_data->customizer->pbBgColors->pbBgHoverColor : '';

	?>
	<div class="wlof-main-wrapper">
		<div class="wlof-title-wrap">
			<h2 class='wlof-title'><?php echo esc_html( $wheel_title ); ?></h2>
			<span class='wlof-published-on'>
				<?php esc_html_e( 'Assessment taken on: ', 'wheel-of-life' ); ?>
				<time datetime='<?php echo get_the_date( 'c' ); ?>' itemprop='datePublished'><?php echo get_the_date(); ?></time>
			</span>
		</div>
		<div class='wlof-life-sbwl'>
			<div id='submission-chart' data-chartData='<?php echo json_encode( $chart_data ); ?>' data-chartOption='<?php echo json_encode( $chart_options ); ?>' data-reportLink='<?php echo esc_url( get_the_permalink() ); ?>' data-reportTitle='<?php echo esc_attr( get_the_title() ); ?>' data-chartType='<?php echo esc_attr( $chart_type ); ?>' data-wheelID='<?php echo absint( $wheel_id ); ?>'>
			</div>
		</div>
		<div class="wheeloflife-cta-container">
		<div
			class="wheeloflife-cta-wrapper <?php echo $bg_type === 'image' ? 'has-overlay' : ''; ?>"
			style="background: <?php echo $cta_background . '; padding:' . $cta_padding . ';margin:' . $cta_margin . '; --cta-overlay-color:' . $cta_overlay_color; ?>"
			align="<?php echo $cta_align; ?>"
		>
			<h2
				class="wheeloflife-cta-title"
				style="font-size: <?php echo $cta_data->customizer->fontSize . 'px; color:' . $cta_data->customizer->fontColor; ?>"
			>
				<?php echo $cta_title; ?>
			</h2>
			<div
				class="wheeloflife-cta-description"
				style="font-size: <?php echo $cta_data->customizer->descFontSize . 'px; color:' . $cta_data->customizer->descFontColor; ?>"
			>
				<?php echo $cta_desc; ?>
			</div>
			<div class="wheeloflife-cta-btns">
			<?php if ( ! empty( $pb_label ) && ! empty( $pb_link ) ) { ?>
				<a
					href="<?php echo $pb_link; ?>"
					rel="noexternal noopener"
					target="<?php echo $pb_newtab != '' ? '_blank' : ''; ?>"
					class="wheeloflife-btn primary"
					style="font-size: <?php echo $pbFontSize . '; color:' . $pbFontColor . '; background:' . $pbBg . '; border-radius:' . $pbBorderRadius . 'px'; ?>"
					onMouseOver="this.style.color='<?php echo $pbfontHoverColor; ?>';this.style.background='<?php echo $pbBgHoverColor; ?>'"
					onMouseOut="this.style.color='<?php echo $pbFontColor; ?>';this.style.background='<?php echo $pbBg; ?>'"
				>
					<?php echo $pb_label; ?>
				</a>
				<?php } ?>
			</div>
		</div>
	</div>
	</div>
	<?php

} else {
	$cta_data = wol_get_cta_settings();
	$cta_data = json_decode( json_encode( $cta_data ), false );

	// cta
	$cta_title = isset( $cta_data->title ) ? esc_html( $cta_data->title ) : '';
	$cta_desc  = isset( $cta_data->description ) ? esc_html( $cta_data->description ) : '';
	$pb_label  = isset( $cta_data->buttonLabel ) ? esc_html( $cta_data->buttonLabel ) : '';
	$pb_link   = isset( $cta_data->buttonLink ) ? esc_url( $cta_data->buttonLink ) : '';
	$pb_newtab = isset( $cta_data->openInTab ) ? rest_sanitize_boolean( $cta_data->openInTab ) : false;
	$sb_label  = isset( $cta_data->sbuttonLabel ) ? esc_html( $cta_data->sbuttonLabel ) : '';
	$sb_link   = isset( $cta_data->sbuttonLink ) ? esc_url( $cta_data->sbuttonLink ) : '';
	$sb_newtab = isset( $cta_data->sbopenInTab ) ? rest_sanitize_boolean( $cta_data->sbopenInTab ) : false;

	$link_nofollow   = isset( $cta_data->setLinkAttrNoFollow ) ? rest_sanitize_boolean( $cta_data->setLinkAttrNoFollow ) : false;
	$link_sponser    = isset( $cta_data->setLinkAttrSponser ) ? rest_sanitize_boolean( $cta_data->setLinkAttrSponser ) : false;
	$link_download   = isset( $cta_data->setLinkAttrDownload ) ? rest_sanitize_boolean( $cta_data->setLinkAttrDownload ) : false;
	$sblink_nofollow = isset( $cta_data->sbsetLinkAttrNoFollow ) ? rest_sanitize_boolean( $cta_data->sbsetLinkAttrNoFollow ) : false;
	$sblink_sponser  = isset( $cta_data->sbsetLinkAttrSponser ) ? rest_sanitize_boolean( $cta_data->sbsetLinkAttrSponser ) : false;
	$sblink_download = isset( $cta_data->sbsetLinkAttrDownload ) ? rest_sanitize_boolean( $cta_data->sbsetLinkAttrDownload ) : false;

	// cta background
	$bg_type           = isset( $cta_data->customizer->background->background_type ) ? esc_html( $cta_data->customizer->background->background_type ) : '';
	$bg_positionX      = $cta_data->customizer->background->background_image->x;
	$bg_positionY      = $cta_data->customizer->background->background_image->y;
	$bg_repeate        = isset( $cta_data->customizer->background->background_repeat ) ? $cta_data->customizer->background->background_repeat : '';
	$bg_size           = isset( $cta_data->customizer->background->background_size ) ? $cta_data->customizer->background->background_size : '';
	$bg_attachement    = isset( $cta_data->customizer->background->background_attachment ) ? $cta_data->customizer->background->background_attachment : '';
	$bg_img_url        = isset( $cta_data->customizer->background->background_image->url ) ? $cta_data->customizer->background->background_image->url : '';
	$bg_solid_color    = isset( $cta_data->customizer->background->backgroundColor->default->color ) ? $cta_data->customizer->background->backgroundColor->default->color : '';
	$bg_gradient_color = isset( $cta_data->customizer->background->gradient ) ? $cta_data->customizer->background->gradient : '';
	$x_axis            = $bg_positionX * 100 . '% ';
	$y_axis            = $bg_positionY * 100 . '% ';
	$cta_overlay_color = $cta_data->customizer->background->overlayColor->default->color;

	if ( $bg_type === 'image' ) {
		$cta_background = 'url(' . $bg_img_url . '); background-repeat:' . $bg_repeate . '; background-size:' . $bg_size . '; background-attachment:' . $bg_attachement . '; background-position:' . $x_axis . '% ' . $y_axis . '%;';
	} elseif ( $bg_type === 'gradient' ) {
		$cta_background = $bg_gradient_color;
	} else {
		$cta_background = $bg_solid_color;
	}
	// spacing
	$cta_margin  = $cta_data->customizer->margin->top . ' ' . $cta_data->customizer->margin->right . ' ' . $cta_data->customizer->margin->bottom . ' ' . $cta_data->customizer->margin->left;
	$cta_padding = $cta_data->customizer->padding->top . ' ' . $cta_data->customizer->padding->right . ' ' . $cta_data->customizer->padding->bottom . ' ' . $cta_data->customizer->padding->left;

	// align
	$cta_align = isset( $cta_data->customizer->alignment ) ? $cta_data->customizer->alignment : '';

	// cta title
	$cta_title_size  = isset( $cta_data->customizer->fontSize ) ? $cta_data->customizer->fontSize : '';
	$cta_title_color = isset( $cta_data->customizer->fontColor ) ? $cta_data->customizer->fontColor : '';

	// cta desc
	$cta_desc_size  = isset( $cta_data->customizer->descFontSize ) ? $cta_data->customizer->descFontSize : '';
	$cta_desc_color = isset( $cta_data->customizer->descFontColor ) ? $cta_data->customizer->descFontColor : '';

	// buttons
	// primary button
	$pbFontSize       = isset( $cta_data->customizer->pbFontSize ) ? $cta_data->customizer->pbFontSize : '';
	$pbFontColor      = isset( $cta_data->customizer->pbfontColors->pbfontColor ) ? $cta_data->customizer->pbfontColors->pbfontColor : '';
	$pbBg             = isset( $cta_data->customizer->pbBgColors->pbBgColor ) ? $cta_data->customizer->pbBgColors->pbBgColor : '';
	$pbBorderRadius   = isset( $cta_data->customizer->pbBorderRadius ) ? $cta_data->customizer->pbBorderRadius : '';
	$pbfontHoverColor = isset( $cta_data->customizer->pbfontColors->pbfontHoverColor ) ? $cta_data->customizer->pbfontColors->pbfontHoverColor : '';
	$pbBgHoverColor   = isset( $cta_data->customizer->pbBgColors->pbBgHoverColor ) ? $cta_data->customizer->pbBgColors->pbBgHoverColor : '';

	// secondary button
	$sbFontSize       = isset( $cta_data->customizer->sbFontSize ) ? $cta_data->customizer->sbFontSize : '';
	$sbFontColor      = isset( $cta_data->customizer->sbfontColors->sbfontColor ) ? $cta_data->customizer->sbfontColors->sbfontColor : '';
	$sbBg             = isset( $cta_data->customizer->sbBgColors->sbBgColor ) ? $cta_data->customizer->sbBgColors->sbBgColor : '';
	$sbBorderRadius   = isset( $cta_data->customizer->sbBorderRadius ) ? $cta_data->customizer->sbBorderRadius : '';
	$sbfontHoverColor = isset( $cta_data->customizer->sbfontColors->sbfontHoverColor ) ? $cta_data->customizer->sbfontColors->sbfontHoverColor : '';
	$sbBgHoverColor   = isset( $cta_data->customizer->sbBgColors->sbBgHoverColor ) ? $cta_data->customizer->sbBgColors->sbBgHoverColor : '';

	?>
	<div class="wlof-main-wrapper">
		<div class="wlof-title-wrap">
			<h2 class='wlof-title'><?php echo esc_html( $wheel_title ); ?></h2>
			<span class='wlof-published-on'>
				<?php esc_html_e( 'Assessment taken on: ', 'wheel-of-life' ); ?>
				<time datetime='<?php echo get_the_date( 'c' ); ?>' itemprop='datePublished'><?php echo get_the_date(); ?></time>
			</span>
		</div>
		<div class='wlof-life-sbwl'>
			<div id='submission-chart' data-chartData='<?php echo json_encode( $chart_data ); ?>' data-chartOption='<?php echo json_encode( $chart_options ); ?>' data-reportLink='<?php echo esc_url( get_the_permalink() ); ?>' data-reportTitle='<?php echo esc_attr( get_the_title() ); ?>' data-chartType='<?php echo esc_attr( $chart_type ); ?>' data-wheelID='<?php echo absint( $wheel_id ); ?>'>
			</div>
		</div>
		<?php if ( $cta_title != '' ) { ?>
		<div class="wheeloflife-cta-container">
		<div
			class="wheeloflife-cta-wrapper <?php echo $bg_type === 'image' ? 'has-overlay' : ''; ?>"
			style="background: <?php echo $cta_background . '; padding:' . $cta_padding . ';margin:' . $cta_margin . '; --cta-overlay-color:' . $cta_overlay_color; ?>"
			align="<?php echo $cta_align; ?>"
		>
			<h2
				class="wheeloflife-cta-title"
				style="font-size: <?php echo $cta_data->customizer->fontSize . 'px; color:' . $cta_data->customizer->fontColor; ?>"
			>
				<?php echo $cta_title; ?>
			</h2>
			<div
				class="wheeloflife-cta-description"
				style="font-size: <?php echo $cta_data->customizer->descFontSize . 'px; color:' . $cta_data->customizer->descFontColor; ?>"
			>
				<?php echo $cta_desc; ?>
			</div>
			<div class="wheeloflife-cta-btns">
			<?php if ( ! empty( $pb_label ) && ! empty( $pb_link ) ) { ?>
				<a
					href="<?php echo $pb_link; ?>"
					rel="noexternal noopener
					<?php
					echo $link_nofollow == true ? 'nofollow ' : '';
					echo $link_sponser == true ? 'sponsored' : '';
					?>
					"
					target="<?php echo $pb_newtab != '' ? '_blank' : ''; ?>"
					class="wheeloflife-btn primary"
					style="font-size: <?php echo $pbFontSize . '; color:' . $pbFontColor . '; background:' . $pbBg . '; border-radius:' . $pbBorderRadius . 'px'; ?>"
					onMouseOver="this.style.color='<?php echo $pbfontHoverColor; ?>';this.style.background='<?php echo $pbBgHoverColor; ?>'"
					onMouseOut="this.style.color='<?php echo $pbFontColor; ?>';this.style.background='<?php echo $pbBg; ?>'"
					<?php echo $link_download == true ? 'download ' : ''; ?>
				>
					<?php echo $pb_label; ?>
				</a>
				<?php } ?>
				<?php if ( ! empty( $sb_label ) && ! empty( $sb_link ) ) { ?>
				<a
					href="<?php echo $sb_link; ?>"
					rel="noexternal noopener
					<?php
					echo $sblink_nofollow == true ? 'nofollow ' : '';
					echo $sblink_sponser == true ? 'sponsored' : '';
					?>
					"
					target="<?php echo $sb_newtab != '' ? '_blank' : ''; ?>"
					class="wheeloflife-btn secondary"
					style="font-size: <?php echo $sbFontSize . '; color:' . $sbFontColor . '; background:' . $sbBg . '; border-radius: ' . $sbBorderRadius . 'px'; ?>"
					onMouseOver="this.style.color='<?php echo $sbfontHoverColor; ?>';this.style.background='<?php echo $sbBgHoverColor; ?>'"
					onMouseOut="this.style.color='<?php echo $sbFontColor; ?>';this.style.background='<?php echo $sbBg; ?>'"
					<?php echo $sblink_download == true ? 'download ' : ''; ?>
				>
					<?php echo $sb_label; ?>
				</a>
				<?php } ?>
			</div>
		</div>
	</div>
	</div>
			<?php
		}
}
get_footer();
