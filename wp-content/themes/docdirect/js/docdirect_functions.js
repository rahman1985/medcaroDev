var signin_reset;
var signup_reset;

jQuery(document).ready(function ($) {
	"use strict";
	var user_status	= scripts_vars.user_status;
	var fav_message	= scripts_vars.fav_message;
	var fav_nothing	= scripts_vars.fav_nothing;
	var site_key	   = scripts_vars.site_key;
	var site_rtl	   = scripts_vars.site_rtl;
	var calendar_locale	   = scripts_vars.calendar_locale;
	var calendar_format	   = scripts_vars.calendar_format;

	var loder_html	= '<div class="docdirect-site-wrap"><div class="docdirect-loader"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div>';
	
	//Parralax issue
	jQuery(window).trigger('resize.px.parallax');
	
	//Calender language
	if( calendar_locale  && calendar_locale != null){
		jQuery.datetimepicker.setLocale(calendar_locale);
		moment.locale(calendar_locale);
	}
	/* ---------------------------------------
				Swap Lost passowrd
	 --------------------------------------- */
	jQuery('.tg-forgot-password').on('click', function () {
		// Load up a new modal...
		jQuery('.tg-user-lp-model').modal('show');
		jQuery('.tg-user-modal').modal('hide');
	});
	
	jQuery('.try-again-lp').on('click', function () {
		// Load up a new modal...
		jQuery('.tg-user-lp-model').modal('hide');
		jQuery('.tg-user-modal').modal('show');
	});
	
	
	/* ---------------------------------------
				Lost password Ajax
	 --------------------------------------- */
	jQuery('.do-forgot-form').on('click', '.do-lp-button', function (event) {
		event.preventDefault();
		var _this = jQuery(this);
		var _email = jQuery('.do-forgot-form').find('.psemail').val();
	
		jQuery('body').append(loder_html);
	
		if (!(docdirect_isValidEmailAddress(_email))) {
			jQuery('body').find('.docdirect-site-wrap').remove();
			jQuery.sticky(scripts_vars.valid_email, {classList: 'important', speed: 200, autoclose: 5000});
			return false;
		}
	
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: jQuery('.do-forgot-form').serialize() + '&action=docdirect_ajax_lp',
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.docdirect-site-wrap').remove();
				if (response.type == 'success') {
					jQuery('.do-forgot-form').get(0).reset();
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000, position: 'top-right', });
					jQuery('.tg-user-lp-model').modal('hide');
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
				}
			}
		});
	});

	/* ---------------------------------------
				Reset password Ajax
	 --------------------------------------- */
	jQuery('.do-reset-form').on('click', '.do-reset-button', function (event) {
		event.preventDefault();
		var _this = jQuery(this);
		var _pass = jQuery('.do-reset-form').find('.resetnew').val();
	
		jQuery('body').append(loder_html);
	
		if (_pass === '') {
			jQuery('body').find('.docdirect-site-wrap').remove();
			jQuery.sticky(scripts_vars.password_require, {classList: 'important', speed: 200, autoclose: 5000});
			return false;
		}
	
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: jQuery('.do-reset-form').serialize() + '&action=docdirect_ajax_reset_password',
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.docdirect-site-wrap').remove();
				if (response.type == 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000, position: 'top-right', });
					jQuery('.do-reset-form').get(0).reset();
					window.location.replace(response.redirect_url);
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
				}
			}
		});
	});
	//Prices list toggles
	jQuery('.prices-list-wrap .tg-panelcontent').hide();
	jQuery('.prices-list-wrap .tg-accordion .tg-accordionheading:first').addClass('tg-active').next().slideDown('slow');
	jQuery('.prices-list-wrap .tg-accordion .tg-accordionheading').on('click',function() {
		if(jQuery(this).next().is(':hidden')) {
			jQuery('.prices-list-wrap .tg-accordion .tg-accordionheading').removeClass('tg-active').next().slideUp('slow');
			jQuery(this).toggleClass('tg-active').next().slideDown('slow');
		}
	});
	//Chosen Form reset in search filters form
	jQuery(document).on('click','.tg-btn-reset',function(e){
		e.preventDefault();
		var _this	= jQuery(this);
		_this.parents('form').find('select').val('').trigger('chosen:updated');
	});
	
	/*
	 * @Invite users
	 * @return{}
	*/
	jQuery(document).on('click','.invite-users',function(e){
		e.preventDefault();
		var _this 	= jQuery(this);
		
		var serialize_data	= _this.parents('.tg-modalmanageteam').find('.tg-themeform').serialize();
		var dataString = serialize_data+'&action=docdirect_invite_users';
		jQuery('body').append(loder_html);
		
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType:"json",
			success: function(response) {
				jQuery('body').find('.docdirect-site-wrap').remove();
				
				if( response.type === 'error' ) {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
				} else{
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
					_this.parents('.tg-modalmanageteam').find('.tg-themeform').get(0).reset();
				}
			}
		});
		return false;
	});
	//Menu
	function collapseMenu(){
		jQuery('.doc-navigation ul li.menu-item-has-children').prepend('<span class="doc-dropdowarrow"><i class="fa fa-plus"></i></span>');
		jQuery('.doc-navigation ul li.menu-item-has-children span').on('click', function() {
			jQuery(this).next().next().slideToggle(300);
			jQuery(this).parent('li.menu-item-has-children').toggleClass('doc-open');
		});
	}
	collapseMenu();
	//Preloader
	jQuery(window).load(function() {
		jQuery(".preloader-outer").delay(2000).fadeOut();
		jQuery(".pins").delay(1500).fadeOut("slow");
	});
	//toggle more info
	jQuery(document).on('click', '.see-more-info a', function (event) {
		var _this	= jQuery(this);
		var _data	= _this.parents('.tg-innetworkinsurrance, .tg-kurmerken').find('ul');
		if(_data.is(':visible')) {
			_data.slideUp();
			_this.find('span i').removeClass('fa-minus').addClass('fa-plus');
		} else {
			_data.slideDown();
			_this.find('span i').removeClass('fa-plus').addClass('fa-minus');
		}
		event.stopPropagation();
	});
	
	//SVG Inject
	if ($(".geo-locate-me").length) {
		$(".geo-locate-me").svgInject();
	};
	/*if ($(".verified-user").length) {
		$(".verified-user").svgInject();
	};*/
	//Toogle Radius Search
	jQuery(document).on('click','.geodistance',function(){
		jQuery('.geodistance_range').toggle();
	});
	/*--------------------------------------
			DOCTOR'S GALLERY
	--------------------------------------*/
	$("#tg-photosgallery").owlCarousel({
		items:3,
		rtl: site_rtl,
		nav: true,
		dots: false,
		autoplay: true,
		navText : ["<i class='tg-prev fa fa-angle-left'></i>", "<i class='tg-next fa fa-angle-right'></i>"],
		responsive:{
			568:{items:1},
			991:{items:2},
			1199:{items:3},
		}
	});
	/*--------------------------------------
			PRETTY PHOTO GALLERY
	--------------------------------------*/
	$("a[data-rel]").each(function () {
		$(this).attr("rel", $(this).data("rel"));
	});
	$("a[data-rel^='prettyPhoto']").prettyPhoto({
		animation_speed: 'normal',
		theme: 'dark_square',
		slideshow: 3000,
		autoplay_slideshow: false,
		social_tools: false,
		show_title: false  /* true/false */
	});

	/*--------------------------------------
			GRAPH
	--------------------------------------*/
	
	/*--------------------------------------
			THEME ACCORDION
	--------------------------------------*/
	$('#accordion .tg-panel-heading').on('click',function () {
		$('.tg-panel-heading').removeClass('active');
		$(this).parents('.tg-panel-heading').addClass('active');
		$('.panel').removeClass('active');
		$(this).parent().addClass('active');
	});
	
	/*
	 * @Contact Form
	 * @return{}
	*/
	jQuery('.contact_wrap').on('click','.contact_now',function(e){
		e.preventDefault();
		var $this 	= jQuery(this);
		
		var success	= $this.parents('.form-data').data('success');
		var error	= $this.parents('.form-data').data('error');
		var email	= $this.parents('.form-data').data('email');
		
		var serialize_data	= $this.parents('.contact_wrap').find('.contact_form').serialize();
		var dataString = serialize_data+'&success='+success+'&error='+error+'&email='+email+'&action=docdirect_submit_contact';
		
		$this.parents('.contact_wrap').find('.message_contact').html('').hide();
		$this.parents('.contact_wrap').find('.message_contact').removeClass('alert-success');
		$this.parents('.contact_wrap').find('.message_contact').removeClass('alert-danger');
		jQuery('body').append(loder_html);
		
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType:"json",
			success: function(response) {
				jQuery('body').find('.docdirect-site-wrap').remove();
				
				jQuery('.message_contact').show();
				if( response.type == 'error' ) {
					$this.parents('.contact_wrap').find('.message_contact').addClass('alert alert-danger').show();
					$this.parents('.contact_wrap').find('.message_contact').html(response.message);
				} else{
					$this.parents('.contact_wrap').find('.contact_form').get(0).reset();
					$this.parents('.contact_wrap').find('.message_contact').addClass('alert alert-success').show();
					$this.parents('.contact_wrap').find('.message_contact').html(response.message);
				}
			}
		});
		
		return false;
		
	});
	
	/*
	 * @Make Review
	 * @return{}
	*/
	jQuery(document).on('click','.make-review',function(e){
		e.preventDefault();
		var $this 	= jQuery(this);
		
		var serialize_data	= $this.parents('.form-review').serialize();
		var dataString = serialize_data+'&action=docdirect_make_review';
		
		jQuery('.message_contact').html('').hide();
		jQuery('.message_contact').removeClass('alert-success');
		jQuery('.message_contact').removeClass('alert-danger');
		
		jQuery('body').append(loder_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType:"json",
			success: function(response) {
				jQuery('body').find('.docdirect-site-wrap').remove();
				jQuery('.message_contact').show();
				if( response.type == 'error' ) {
					jQuery('.message_contact').addClass('alert alert-danger').show();
					jQuery('.message_contact').html(response.message);
				} else{
					jQuery('.message_contact').addClass('alert alert-success').show();
					jQuery('.message_contact').html(response.message);
					if( response.html == 'refresh' ){
						window.location.reload();
					}
					$this.parents('.form-review').find('.contact_form').get(0).reset();
					
				}
			}
		});
		
		return false;
		
	});
	
	/*--------------------------------------
			SCHEDULE SLIDER
	--------------------------------------*/
	jQuery("#tg-schedule-slider").owlCarousel({
		items:1,
		nav:true,
		rtl: site_rtl,
		loop: true,
		dots: false,
		autoplay: true,
		navText : ["<i class='tg-prev fa fa-angle-left'></i>", "<i class='tg-next fa fa-angle-right'></i>"],
	});
	
	/*
	 * @Contact Form
	 * @return{}
	*/
	jQuery('.doc-contact').on('click','.contact_me',function(e){
		e.preventDefault();
		var $this 	= jQuery(this);
		
		var serialize_data	= $this.parents('.doc-contact').find('.contact_form').serialize();
		var dataString = serialize_data+'&action=docdirect_submit_me';
		$this.append("<i class='fa fa-refresh fa-spin'></i>");

		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType:"json",
			success: function(response) {
				$this.find('i').remove();
				jQuery('.message_contact').show();
				if (response.type == 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000,position: 'top-right',});
					$this.parents('.doc-contact').find('.contact_form').get(0).reset();
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
				}
			}
		});
		
		return false;
		
	});
	
	/*
	 * @Report User
	 * @return{}
	*/
	jQuery('.doc-claim').on('click','.report_now',function(e){
		e.preventDefault();
		var _this 	= jQuery(this);
		
		var serialize_data	= jQuery('.claim_form').serialize();
		var dataString = serialize_data+'&action=docdirect_submit_claim';
		_this.append("<i class='fa fa-refresh fa-spin'></i>");

		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType:"json",
			success: function(response) {
				_this.find('i').remove();
				if (response.type == 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000,position: 'top-right',});
					_this.parents('.doc-claim').find('.claim_form').get(0).reset();
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
				}
			}
		});
		
		return false;
		
	});
	
	/* ---------------------------------------
     rtegistration Ajax
     --------------------------------------- */
	jQuery('.do-registration-form').on('click', '.do-register-button', function (event) {
		event.preventDefault();
		var _this	= jQuery(this);
		_this.append('<i class="fa fa-refresh fa-spin"></i>');
		
		jQuery('.registration-message').html('').hide();
		
		var _authenticationform	= _this.parents('form.do-registration-form').serialize();
		
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: _authenticationform + '&action=docdirect_user_registration',
			dataType: "json",
			success: function (response) {
				_this.find('i.fa-spin').remove();
				jQuery('.registration-message').show();
				
				if (response.type == 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000,position: 'top-right',});
					if( response.profile_url ){
						jQuery('body').append(loder_html);
						window.location.replace(response.profile_url);
					} else{
						window.location.reload();
					}
				} else {
					if( scripts_vars.captcha_settings === 'enable' ) {
						grecaptcha.reset(signup_reset);
					}
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
				}
			}
	   });
	});
	
	/* ---------------------------------------
     Login Ajax
     --------------------------------------- */
	jQuery('.do-login-form').on('click', '.do-login-button', function (event) {
		event.preventDefault();
		var _this	= jQuery(this);
		_this.append('<i class="fa fa-refresh fa-spin"></i>');
		jQuery('.login-message').html('').hide();
		
		var _serialize	= _this.parents('form.do-login-form').serialize();
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: _serialize + '&action=docdirect_ajax_login',
			dataType: "json",
			success: function (response) {
				_this.find('i.fa-spin').remove();
				
				jQuery('.login-message').show();
				if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000,position: 'top-right',});
					//window.location.reload();
					jQuery('body').append(loder_html);
					window.location.replace(response.url);
				} else {
					if( scripts_vars.captcha_settings === 'enable' ) {
						grecaptcha.reset(signin_reset);
					}
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
				}
			}
	   });
	});
	
	/* ---------------------------------------
     Add to favorites
     --------------------------------------- */
	jQuery(document).on('click', '.add-to-fav', function (event) {
		event.preventDefault();
		
		if( user_status == 'false' ){
			jQuery.sticky(fav_message, {classList: 'important', speed: 200, autoclose: 7000});
			return false;	
		}
		
		var _this	= jQuery(this);
		var wl_id	= _this.data('wl_id');
		var view_type	= _this.data('view_type');
		_this.append('<i class="fa fa-refresh fa-spin"></i>');
		_this.addClass('loading');
		
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: 'wl_id='+wl_id+'&action=docdirect_update_wishlist',
			dataType: "json",
			success: function (response) {
				_this.removeClass('loading');
				_this.find('i.fa-spin').remove();
				jQuery('.login-message').show();
				if (response.type == 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000,position: 'top-right',});
					if( view_type === 'v2' ){
						_this.removeClass('doc-notfavorite');
					} else{ 
						_this.removeClass('tg-like add-to-fav');
						_this.addClass('tg-dislike');
					}
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
				}
			}
	   });
	});
	
	/* ---------------------------------------
     Add to favorites
     --------------------------------------- */
	jQuery(document).on('click', '.remove-wishlist', function (event) {
		event.preventDefault();
		var _this	= jQuery(this);
		var wl_id	= _this.data('wl_id');
		_this.append('<i class="fa fa-refresh fa-spin"></i>');
		_this.addClass('loading');
		
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: 'wl_id='+wl_id+'&action=docdirect_remove_wishlist',
			dataType: "json",
			success: function (response) {
				_this.removeClass('loading');
				_this.find('i.fa-spin').remove();
				
				if (response.type == 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000,position: 'top-right',});
					_this.parents('#wishlist-'+wl_id).remove();
					if( jQuery( '.tg-lists .tg-list' ).length < 1 ){
						jQuery( '.tg-lists' ).html('<div class="tg-list"><p>'+fav_nothing+'</div>');
					}
					
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
				}
			}
	   });
	});
	
	/* ---------------------------------------
     Remove Team Member
     --------------------------------------- */
	jQuery(document).on('click', '.remove-team-member', function (event) {
		event.preventDefault();
		var _this	= jQuery(this);
		var _id	= _this.parents('li').data('id');
		
		$.confirm({
			'title': scripts_vars.delete_teams,
			'message': scripts_vars.delete_teams_message,
			'buttons': {
				'Yes': {
					'class': 'blue',
					'action': function () {
						jQuery('body').append(loder_html);
		
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: 'id='+_id+'&action=docdirect_remove_team_member',
							dataType: "json",
							success: function (response) {
								jQuery('body').find('.docdirect-site-wrap').remove();
								if (response.type == 'success') {
									jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000,position: 'top-right',});
									_this.parents('#team-'+_id).remove();
									if( jQuery( '.tg-teammembers-wrap li' ).length < 1 ){
										jQuery( '.tg-teammembers-wrap' ).html('<li class="no-team-item"><div class="tg-list"><p>'+scripts_vars.no_team+'</div></li>');
									}
									
								} else {
									jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
								}
							}
					   });
					}
				},
				'No': {
					'class': 'gray',
					'action': function () {
						return false;
					}	// Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
	});
	
	//Rating bars
	try {
		jQuery('#tg-userskill').appear(function () {
			jQuery('.tg-skillholder').each(function () {
				jQuery(this).find('.tg-skillbar').animate({
					width: jQuery(this).attr('data-percent')
				}, 2500);
			});
		});
	} catch (err) {}
	
	/*--------------------------------------
			Release 4.0
	--------------------------------------*/
	//Prepare Subcatgories
	jQuery(document).on('change','.doc-formsearchwidget select.directory_type', function (event) {
		var id		  = jQuery('option:selected', this).attr('id');		
		var dir_name	= jQuery(this).find(':selected').data('dir_name');


		if ( id === undefined || id === null) {
			jQuery( '.directory-parent-spcialities' ).html('');
		}

		if( Z_Editor.elements[id] ) {
			var load_subcategories = wp.template( 'load-subcategories' );
			var data = [];
			data['childrens']	 = Z_Editor.elements[id];
			var _options		 = load_subcategories(data);
			jQuery( '.specialities-search-wrap' ).html(_options);
		}
	});
	
	//Load more items
	var size_li = jQuery(".doc-lan-filter .doc-checkbox").size();
    var x = 3;
    jQuery('.doc-lan-filter .doc-checkbox:lt('+x+')').show();
    jQuery('.doc-lan-filter').click(function () {
        x= (x+5 <= size_li) ? x+5 : size_li;
        jQuery('.doc-lan-filter .doc-checkbox:lt('+x+')').show();
    });
    jQuery('#showLess').click(function () {
        x=(x-5<0) ? 3 : x-5;
        jQuery('#myList li').not(':lt('+x+')').hide();
    });
	
	//add comment likes
	jQuery(document).on('click', '.do-like-me', function (event) {
		event.preventDefault();
		var _this	= jQuery(this);
		var like_id		= _this.data('like_id');
		
		_this.append('<i class="fa fa-spinner fa-spin"></i>');
		_this.addClass('loading');
		
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: 'like_id='+like_id+'&action=docdirect_set_likes',
			dataType: "json",
			success: function (response) {
				_this.find('.fa.fa-spinner').remove();
				
				if (response.type == 'success') {
					_this.replaceWith(response.html);
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
				}
			}
	   });
	});
	
	
	jQuery(document).on('click', '.subscribe_me', function (event) {
		event.preventDefault();
		var _this = jQuery(this);
		jQuery('body').append(loder_html);
		jQuery.ajax({
			type: 'POST',
			url: scripts_vars.ajaxurl,
			data: jQuery('.doc-formnewsletter').serialize() + '&action=subscribe_mailchimp',
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.docdirect-site-wrap').remove();
				
				if( response.type == 'error' ) {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 7000});
				} else{
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 7000});
				}
			}
		});
	});

});


//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//:::                                                                         :::
//:::  This routine calculates the distance between two points (given the     :::
//:::  latitude/longitude of those points). It is being used to calculate     :::
//:::  the distance between two locations using GeoDataSource (TM) prodducts  :::
//:::                                                                         :::
//:::  Definitions:                                                           :::
//:::    South latitudes are negative, east longitudes are positive           :::
//:::                                                                         :::
//:::  Passed to function:                                                    :::
//:::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :::
//:::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :::
//:::    unit = the unit you desire for results                               :::
//:::           where: 'M' is statute miles (default)                         :::
//:::                  'K' is kilometers                                      :::
//:::                  'N' is nautical miles                                  :::
//:::                                                                         :::
//:::  Worldwide cities and other features databases with latitude longitude  :::
//:::  are available at http://www.geodatasource.com                          :::
//:::                                                                         :::
//:::  For enquiries, please contact sales@geodatasource.com                  :::
//:::                                                                         :::
//:::  Official Web site: http://www.geodatasource.com                        :::
//:::                                                                         :::
//:::               GeoDataSource.com (C) All Rights Reserved 2015            :::
//:::                                                                         :::
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
function _get_distance(lat1, lon1, lat2, lon2, unit) {
	var radlat1 = Math.PI * lat1/180
	var radlat2 = Math.PI * lat2/180
	var theta = lon1-lon2
	var radtheta = Math.PI * theta/180
	var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
	dist = Math.acos(dist)
	dist = dist * 180/Math.PI
	dist = dist * 60 * 1.1515
	if (unit=="K") { dist = dist * 1.609344 }
	if (unit=="N") { dist = dist * 0.8684 }
	return dist
}

// get rounded value
function _get_round(value, decimals) {
  return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
}

//recaptcha
var DocDirectCaptchaCallback = function() {
	signin_reset 	= grecaptcha.render('recaptcha_signin', {'sitekey' : scripts_vars.site_key });
	signup_reset 	= grecaptcha.render('recaptcha_signup', {'sitekey' : scripts_vars.site_key });
};
	
/* -------------------------------------
 SVG Injector
 -------------------------------------- */
 		
;(function($,window,document,undefined){var pluginName='svgInject';function Plugin(element,options){this.element=element;this._name=pluginName;this.init();}
Plugin.prototype={init:function(){$(this.element).css('visibility','hidden');this.swapSVG(this.element);},swapSVG:function(el){var imgURL=$(el).attr('src');var imgID=$(el).attr('id');var imgClass=$(el).attr('class');var imgData=$(el).clone(true).data();var dimensions={w:$(el).attr('width'),h:$(el).attr('height')};$.get(imgURL,function(data){var svg=$(data).find('svg');if(typeof imgID!==undefined){svg=svg.attr('id',imgID);}
if(typeof imgClass!==undefined){var cls=(svg.attr('class')!==undefined)?svg.attr('class'):'';svg=svg.attr('class',imgClass+' '+cls+' replaced-svg');}
$.each(imgData,function(name,value){svg[0].setAttribute('data-'+name,value);});svg=svg.removeAttr('xmlns:a');var ow=parseFloat(svg.attr('width'));var oh=parseFloat(svg.attr('height'));if(dimensions.w&&dimensions.h){$(svg).attr('width',dimensions.w);$(svg).attr('height',dimensions.h);}
else if(dimensions.w){$(svg).attr('width',dimensions.w);$(svg).attr('height',(oh/ow)*dimensions.w);}
else if(dimensions.h){$(svg).attr('height',dimensions.h);$(svg).attr('width',(ow/oh)*dimensions.h);}
$(el).replaceWith(svg);var js=new Function(svg.find('script').text());js();});}};$.fn[pluginName]=function(options){return this.each(function(){if(!$.data(this,'plugin_'+pluginName)){$.data(this,'plugin_'+pluginName,new Plugin(this,options));}});};})(jQuery,window,document);

/* -------------------------------------
 Map Styles
 -------------------------------------- */
function docdirect_get_map_styles(style){
		
	var styles = '';
	if(style == 'view_1'){
		var styles = [{"featureType":"administrative.country","elementType":"geometry","stylers":[{"visibility":"simplified"},{"hue":"#ff0000"}]}];
	}else if(style == 'view_2'){
		var styles = [{"featureType":"water","elementType":"all","stylers":[{"hue":"#7fc8ed"},{"saturation":55},{"lightness":-6},{"visibility":"on"}]},{"featureType":"water","elementType":"labels","stylers":[{"hue":"#7fc8ed"},{"saturation":55},{"lightness":-6},{"visibility":"off"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"hue":"#83cead"},{"saturation":1},{"lightness":-15},{"visibility":"on"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"hue":"#f3f4f4"},{"saturation":-84},{"lightness":59},{"visibility":"on"}]},{"featureType":"landscape","elementType":"labels","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"off"}]},{"featureType":"road","elementType":"geometry","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"on"}]},{"featureType":"road","elementType":"labels","stylers":[{"hue":"#bbbbbb"},{"saturation":-100},{"lightness":26},{"visibility":"on"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"hue":"#ffcc00"},{"saturation":100},{"lightness":-35},{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"hue":"#ffcc00"},{"saturation":100},{"lightness":-22},{"visibility":"on"}]},{"featureType":"poi.school","elementType":"all","stylers":[{"hue":"#d7e4e4"},{"saturation":-60},{"lightness":23},{"visibility":"on"}]}];
	}else if(style == 'view_3'){
		var styles = [{"featureType":"water","stylers":[{"saturation":43},{"lightness":-11},{"hue":"#0088ff"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"hue":"#ff0000"},{"saturation":-100},{"lightness":99}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"color":"#808080"},{"lightness":54}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#ece2d9"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#ccdca1"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#767676"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]},{"featureType":"poi","stylers":[{"visibility":"off"}]},{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#b8cb93"}]},{"featureType":"poi.park","stylers":[{"visibility":"on"}]},{"featureType":"poi.sports_complex","stylers":[{"visibility":"on"}]},{"featureType":"poi.medical","stylers":[{"visibility":"on"}]},{"featureType":"poi.business","stylers":[{"visibility":"simplified"}]}];
	
	}else if(style == 'view_4'){
		var styles = [{"elementType":"geometry","stylers":[{"hue":"#ff4400"},{"saturation":-68},{"lightness":-4},{"gamma":0.72}]},{"featureType":"road","elementType":"labels.icon"},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"hue":"#0077ff"},{"gamma":3.1}]},{"featureType":"water","stylers":[{"hue":"#00ccff"},{"gamma":0.44},{"saturation":-33}]},{"featureType":"poi.park","stylers":[{"hue":"#44ff00"},{"saturation":-23}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"hue":"#007fff"},{"gamma":0.77},{"saturation":65},{"lightness":99}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"gamma":0.11},{"weight":5.6},{"saturation":99},{"hue":"#0091ff"},{"lightness":-86}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"lightness":-48},{"hue":"#ff5e00"},{"gamma":1.2},{"saturation":-23}]},{"featureType":"transit","elementType":"labels.text.stroke","stylers":[{"saturation":-64},{"hue":"#ff9100"},{"lightness":16},{"gamma":0.47},{"weight":2.7}]}];
	
	}else if(style == 'view_5'){
		var styles = [{"featureType":"water","elementType":"geometry","stylers":[{"color":"#e9e9e9"},{"lightness":17}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#ffffff"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":16}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":21}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#dedede"},{"lightness":21}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"lightness":16}]},{"elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#333333"},{"lightness":40}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#f2f2f2"},{"lightness":19}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#fefefe"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#fefefe"},{"lightness":17},{"weight":1.2}]}];
	
	}else if(style == 'view_6'){
		var styles = [{"featureType":"landscape","stylers":[{"hue":"#FFBB00"},{"saturation":43.400000000000006},{"lightness":37.599999999999994},{"gamma":1}]},{"featureType":"road.highway","stylers":[{"hue":"#FFC200"},{"saturation":-61.8},{"lightness":45.599999999999994},{"gamma":1}]},{"featureType":"road.arterial","stylers":[{"hue":"#FF0300"},{"saturation":-100},{"lightness":51.19999999999999},{"gamma":1}]},{"featureType":"road.local","stylers":[{"hue":"#FF0300"},{"saturation":-100},{"lightness":52},{"gamma":1}]},{"featureType":"water","stylers":[{"hue":"#0078FF"},{"saturation":-13.200000000000003},{"lightness":2.4000000000000057},{"gamma":1}]},{"featureType":"poi","stylers":[{"hue":"#00FF6A"},{"saturation":-1.0989010989011234},{"lightness":11.200000000000017},{"gamma":1}]}];
	} else{
		var styles = [{"featureType":"administrative.country","elementType":"geometry","stylers":[{"visibility":"simplified"},{"hue":"#ff0000"}]}];
	}
	return styles;
}

/* -------------------------------------
 Validate Amount
 -------------------------------------- */
function validateAmount(_this) {
	if (isNaN(jQuery.trim(jQuery(_this).val()))) {
		jQuery(_this).val("");

	} else {
		var amt = jQuery(_this).val();
		if (amt != '') {
			if (amt.length > 16) {
				amt = amt.substr(0, 16);
				jQuery(_this).val(amt);
			}
			//amount = amt;
			return true;
		} else {
			//amount = gloAmount;
			return true;
		}
	}
}

//Convert date formate for min and max date
function _change_date_format(dateStr) {
	var calendar_format	= scripts_vars.calendar_format;
	if( calendar_format === 'd-m-Y' ){
		var parts = dateStr.split("-");
		var _date	= parts[2]+'-'+parts[1]+'-'+parts[0];
		return _date;
	} else{
		return dateStr;
	}  
}

/* -------------------------------------
 Init Full Width Sections
 -------------------------------------- */
builder_full_width_section(); //Init Sections
var $ = window.jQuery;
$(window).off("resize.sectionSettings").on("resize.sectionSettings", builder_full_width_section);
function builder_full_width_section() {
	var $sections = jQuery('.main-page-wrapper .stretch_section');
	jQuery.each($sections, function (key, item) {
		var _sec = jQuery(this);
		var _sec_full = _sec.next(".section-current-width");
		_sec_full.length || (_sec_full = _sec.parent().next(".section-current-width"));
		var _sec_margin_left = parseInt(_sec.css("margin-left"), 10);
		var _sec_margin_right = parseInt(_sec.css("margin-right"), 10);
		var offset = 0 - _sec_full.offset().left - _sec_margin_left;
		var width = jQuery(window).width();
		if (_sec.css({
			position: "relative",
			left: offset,
			"box-sizing": "border-box",
			width: jQuery(window).width()
		}), !_sec.hasClass("stretch_data")) {
			var padding = -1 * offset;
			0 > padding && (padding = 0);
			var paddingRight = width - padding - _sec_full.width() + _sec_margin_left + _sec_margin_right;
			0 > paddingRight && (paddingRight = 0), _sec.css({
				"padding-left": padding + "px",
				"padding-right": paddingRight + "px"
			})
		}
	});
}

/**
 * Mega Menu
 */
jQuery(function ($) {

	function hoverIn() {
		var a = $(this);
		var nav = a.closest('.tg-navigation');
		var mega = a.find('.mega-menu');
		var offset = rightSide(nav) - leftSide(a);
		mega.width(Math.min(rightSide(nav), columns(mega)*325));
		mega.css('left', Math.min(0, offset - mega.width()));
	}

	function hoverOut() {
	}

	function columns(mega) {
		var columns = 0;
		mega.children('.mega-menu-row').each(function () {
			columns = Math.max(columns, $(this).children('.mega-menu-col').length);
		});
		return columns;
	}

	function leftSide(elem) {
		return elem.offset().left;
	}

	function rightSide(elem) {
		return elem.offset().left + elem.width();
	}

	$('.tg-navigation .menu-item-has-mega-menu').hover(hoverIn, hoverOut);
	
});

//Get URL params
var docdirectGetUrlParameter = function getUrlParameter(sParam,is_array) {
	var sPageURL = decodeURIComponent(window.location.search.substring(1)),
		sURLVariables = sPageURL.split('&'),
		sParameterName,
		i;
	
	var array_data	= [];
	for (i = 0; i < sURLVariables.length; i++) {
		sParameterName = sURLVariables[i].split('=');
		
		if( is_array === 'yes' ) {
			if (sParameterName[0] === sParam) {
				sParameterName[1] === undefined ? true :  array_data.push(sParameterName[1]);
			}
		} else{
			return sParameterName[1] === undefined ? true :  sParameterName[1];
		}
	}
	
	return array_data;
};

/* -------------------------------------
 Form Serilization
 -------------------------------------- */
// Serialize Function
$.fn.serializeObject=function(){"use strict";var a={},b=function(b,c){var d=a[c.name];"undefined"!=typeof d&&d!==null?$.isArray(d)?d.push(c.value):a[c.name]=[d,c.value]:a[c.name]=c.value};return $.each(this.serializeArray(),b),a};


/*!
 * jQuery Cookie Plugin v1.4.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2006, 2014 Klaus Hartl
 * Released under the MIT license
 */
(function (factory) {
	if (typeof define === 'function' && define.amd) {
		// AMD (Register as an anonymous module)
		define(['jquery'], factory);
	} else if (typeof exports === 'object') {
		// Node/CommonJS
		module.exports = factory(require('jquery'));
	} else {
		// Browser globals
		factory(jQuery);
	}
}(function ($) {

	var pluses = /\+/g;

	function encode(s) {
		return config.raw ? s : encodeURIComponent(s);
	}

	function decode(s) {
		return config.raw ? s : decodeURIComponent(s);
	}

	function stringifyCookieValue(value) {
		return encode(config.json ? JSON.stringify(value) : String(value));
	}

	function parseCookieValue(s) {
		if (s.indexOf('"') === 0) {
			// This is a quoted cookie as according to RFC2068, unescape...
			s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
		}

		try {
			// Replace server-side written pluses with spaces.
			// If we can't decode the cookie, ignore it, it's unusable.
			// If we can't parse the cookie, ignore it, it's unusable.
			s = decodeURIComponent(s.replace(pluses, ' '));
			return config.json ? JSON.parse(s) : s;
		} catch(e) {}
	}

	function read(s, converter) {
		var value = config.raw ? s : parseCookieValue(s);
		return $.isFunction(converter) ? converter(value) : value;
	}

	var config = $.cookie = function (key, value, options) {

		// Write

		if (arguments.length > 1 && !$.isFunction(value)) {
			options = $.extend({}, config.defaults, options);

			if (typeof options.expires === 'number') {
				var days = options.expires, t = options.expires = new Date();
				t.setMilliseconds(t.getMilliseconds() + days * 864e+5);
			}

			return (document.cookie = [
				encode(key), '=', stringifyCookieValue(value),
				options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
				options.path    ? '; path=' + options.path : '',
				options.domain  ? '; domain=' + options.domain : '',
				options.secure  ? '; secure' : ''
			].join(''));
		}

		// Read

		var result = key ? undefined : {},
			// To prevent the for loop in the first place assign an empty array
			// in case there are no cookies at all. Also prevents odd result when
			// calling $.cookie().
			cookies = document.cookie ? document.cookie.split('; ') : [],
			i = 0,
			l = cookies.length;

		for (; i < l; i++) {
			var parts = cookies[i].split('='),
				name = decode(parts.shift()),
				cookie = parts.join('=');

			if (key === name) {
				// If second argument (value) is a function it's a converter...
				result = read(cookie, value);
				break;
			}

			// Prevent storing a cookie that we couldn't decode.
			if (!key && (cookie = read(cookie)) !== undefined) {
				result[name] = cookie;
			}
		}

		return result;
	};

	config.defaults = {};

	$.removeCookie = function (key, options) {
		// Must not alter options, thus extending a fresh object...
		$.cookie(key, '', $.extend({}, options, { expires: -1 }));
		return !$.cookie(key);
	};

}));

$('#check_user').on('change', function () {
	var selectVal = $("#check_user option:selected").val();
	if (selectVal == "3084") {
		$('.hospital-types').show();
	} else {
		$('.hospital-types').hide();
	}
});

// $('#check_user').on('change', function () {
// 	var selectVal = $("#check_user option:selected").val();
// 	if (selectVal == "3084") {
// 		$('.hospital-types').show();
// 		$('.hospital-name').hide();
// 	} else if (selectVal == "126") {
// 		$('.hospital-types').hide();
// 		$('.hospital-name').show();
// 	} else {
// 		$('.hospital-types').hide();
// 		$('.hospital-name').hide();
// 	}
// });