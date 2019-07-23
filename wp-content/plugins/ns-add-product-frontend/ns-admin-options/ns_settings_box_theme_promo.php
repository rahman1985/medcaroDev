<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="nsbigboxtheme<?php echo $ns_style; ?>">
	<div class="titlensbigbox<?php echo $ns_style; ?>">
		<h4><?php 
			_e('JOIN NS CLUB', $ns_text_domain) 
			?>
		</h4>
	</div>
	<div class="contentnsbigbox">
		<a href="<?php echo $link_promo_theme; ?>"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>img/ns_banner_membership-500.png" class="imgnsbigbox"></a>
		<p> 
			<?php 
			_e('-Instant access to all plugins and all themes<br/>', $ns_text_domain);
			_e('-All future plugins and themes are included<br/>', $ns_text_domain);
			_e('-Unlimited license for all NS themes<br/>', $ns_text_domain);
			_e('-Unlimited download for each products<br/>', $ns_text_domain);
			_e('-Super fast support<br/>', $ns_text_domain);
			_e('-Regular update<br/>', $ns_text_domain);
			?>
		</p>
		<a href="<?php echo $link_promo_theme; ?>" class="linkBigBoxNS" target="_blank">
			<div class="buttonNsbigbox<?php echo $ns_style; ?>">
				<?php 
				_e('DISCOVER MORE', $ns_text_domain);
				?>
			</div>
		</a>
	</div>
</div>