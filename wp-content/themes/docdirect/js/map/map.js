/* global document */

jQuery(document).ready(function () {
    "use strict";
	var dir_latitude	 = scripts_vars.dir_latitude;
	var dir_longitude	= scripts_vars.dir_longitude;
	var dir_map_type	 = scripts_vars.dir_map_type;
	var dir_close_marker		  = scripts_vars.dir_close_marker;
	var dir_cluster_marker		= scripts_vars.dir_cluster_marker;
	var dir_map_marker			= scripts_vars.dir_map_marker;
	var dir_cluster_color		 = scripts_vars.dir_cluster_color;
	var dir_zoom				  = scripts_vars.dir_zoom;
	var dir_map_scroll				  = scripts_vars.dir_map_scroll;
	var user_status	= scripts_vars.user_status;
	var fav_message	= scripts_vars.fav_message;
	var fav_nothing	= scripts_vars.fav_nothing;
	
	
	//Click Function for Map Banner
    jQuery(".search_banner").on('click', function (event) {
        event.stopPropagation();
		var _this	= jQuery(this);
		_this.append("<i class='fa fa-refresh fa-spin'></i>");
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: jQuery('.directory-map').serialize() + '&action=docdirect_get_map_directory',
			dataType: "json",
			success: function (response) {
				//Call map init
				docdirect_init_map_script( response );
				_this.find('i').remove();
				jQuery('.tg-banner-content').fadeOut(2000);
      			jQuery('.show-search').fadeIn(2000);
			}
	   }); 
    });
	
	//Search View 1 form submission
	jQuery(".sc-dir-search-v1 .form-searchdoctors").submit(function (event) {
		  event.preventDefault();
		  jQuery('.search_banner').trigger('click');//triger search
	});
	
    jQuery(".show-search").on('click', function (event) {
        event.preventDefault();
        jQuery('.tg-banner-content').fadeIn(1000);
        jQuery(this).fadeOut(500);
		jQuery(".infoBox").hide();
    });
	
	//Swap Titles
	jQuery(document).on('click','.current-directory',function(){	
		jQuery(this).parents('ul').find('li').removeClass('active');
		jQuery(this).addClass('active');
		var dir_name	= jQuery(this).data('dir_name');
		var id	= jQuery(this).data('id');
		jQuery(this).parents('.tg-banner-content').find('em.current_directory').html(dir_name);
		
		if( Z_Editor.elements[id] ) {
			var load_subcategories = wp.template( 'load-subcategories' );
			var data = [];
			data['childrens']	 = Z_Editor.elements[id];
			data['parent']	    = dir_name;
			var _options		= load_subcategories(data);
			jQuery( '.subcats' ).html(_options);
		}

	});
	
	//Prepare Subcatgories
	jQuery(document).on('change','.directory_type', function (event) {
		var id		  = jQuery('option:selected', this).attr('id');		
		var dir_name	= jQuery(this).find(':selected').data('dir_name');
		
		if( jQuery( '.dynamic-title' ).length ){
			jQuery( '.dynamic-title' ).html(dir_name);
		}
		
		if( Z_Editor.elements[id] ) {
			var load_subcategories = wp.template( 'load-subcategories' );
			var data = [];
			data['childrens']	 = Z_Editor.elements[id];
			data['parent']	    = dir_name;
			var _options	= load_subcategories(data);
			jQuery( '.subcats' ).html(_options);
		} else{
			var load_subcategories = wp.template( 'load-subcategories' );
			var data = [];
			var _options	= load_subcategories(data);
			jQuery( '.subcats' ).html(_options);
		}
	});
	
	//Prepare Subcatgories
	jQuery(document).on('change','.sort_by, .order_by, .per_page', function (event) {
		jQuery(".form-refinesearch, .search-result-form").submit();
	});
	
	//Change View
	jQuery(document).on('click','.tg-listing-views a.list, .tg-listing-views a.grid', function (event) {
		var current_class	= jQuery(this).attr('class');
		jQuery(this).parents('ul.tg-listing-views').find('li').removeClass('active');
		jQuery(this).parent('li').addClass('active');
		
		jQuery(this).parents('.tg-doctors-list').find('.tg-view').addClass('tg-list-view').removeClass('tg-grid-view');
		if( current_class == 'grid' ){
			jQuery(this).parents('.tg-doctors-list').find('.tg-view').addClass('tg-grid-view').removeClass('tg-list-view');
		}
	});
		
});

//Init Map Scripts
function docdirect_init_map_script( _data_list ){
	var dir_latitude	 = scripts_vars.dir_latitude;
	var dir_longitude	= scripts_vars.dir_longitude;
	var dir_map_type	 = scripts_vars.dir_map_type;
	var dir_close_marker		  = scripts_vars.dir_close_marker;
	var dir_cluster_marker		= scripts_vars.dir_cluster_marker;
	var dir_map_marker			= scripts_vars.dir_map_marker;
	var dir_cluster_color		 = scripts_vars.dir_cluster_color;
	var dir_zoom				  = scripts_vars.dir_zoom;
	var dir_map_scroll			= scripts_vars.dir_map_scroll;
	var gmap_norecod			  = scripts_vars.gmap_norecod;
	var map_styles			    = scripts_vars.map_styles;


	if( _data_list.status == 'found' ){
		var response_data	= _data_list.users_list;
	    if( typeof(response_data) != "undefined" && response_data !== null ) {
			var dir_latitude    = response_data[0].latitude;
			var dir_longitude	= response_data[0].longitude;
			var location_center = new google.maps.LatLng(dir_latitude,dir_longitude);
		} else {
			var location_center = new google.maps.LatLng(dir_latitude,dir_longitude);
		}
	} else{
		var location_center = new google.maps.LatLng(dir_latitude,dir_longitude);
	}
	
	if(dir_map_type == 'ROADMAP'){
		var map_id = google.maps.MapTypeId.ROADMAP;
	} else if(dir_map_type == 'SATELLITE'){
		var map_id = google.maps.MapTypeId.SATELLITE;
	} else if(dir_map_type == 'HYBRID'){
		var map_id = google.maps.MapTypeId.HYBRID;
	} else if(dir_map_type == 'TERRAIN'){
		var map_id = google.maps.MapTypeId.TERRAIN;
	} else {
		var map_id = google.maps.MapTypeId.ROADMAP;
	}
	
	var scrollwheel	   = true;
	var lock		   = 'lock';
	
	if( dir_map_scroll == 'false' ){
		scrollwheel	= false;
		lock		   = 'unlock';
		
	}
	
	var mapOptions = {
		center: location_center,
		zoom: parseInt(dir_zoom),
		mapTypeId: map_id,
		scaleControl: true,
		scrollwheel: false,
		disableDefaultUI: true,
		draggable:scrollwheel
	}
	
	var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
	
	var styles = docdirect_get_map_styles(map_styles);
	if(styles != ''){
		var styledMap = new google.maps.StyledMapType(styles, {name: 'Styled Map'});
		map.mapTypes.set('map_style', styledMap);
		map.setMapTypeId('map_style');
	}
		
	var bounds = new google.maps.LatLngBounds();

	//Zoom In
	if(  document.getElementById('doc-mapplus') ){ 
		 google.maps.event.addDomListener(document.getElementById('doc-mapplus'), 'click', function () {      
		   var current= parseInt( map.getZoom(),10 );
		   current++;
		   if(current>20){
			   current=20;
		   }
		   map.setZoom(current);
		   jQuery(".infoBox").hide();
		});  
	}
	
	//Zoom Out
	if(  document.getElementById('doc-mapminus') ){ 
		google.maps.event.addDomListener(document.getElementById('doc-mapminus'), 'click', function () {      
			var current= parseInt( map.getZoom(),10);
			current--;
			if(current<0){
				current=0;
			}
			map.setZoom(current);
			jQuery(".infoBox").hide();
		});  
	}
	
	//Lock Map
	if( document.getElementById('doc-lock') ){ 
		google.maps.event.addDomListener(document.getElementById('doc-lock'), 'click', function () {
			if(lock == 'lock'){
				map.setOptions({ 
						scrollwheel: false,
						draggable: false 
					}
				);
				
				jQuery("#doc-lock").html('<i class="fa fa-lock" aria-hidden="true"></i>');
				lock = 'unlock';
			}else if(lock == 'unlock'){
				map.setOptions({ 
						scrollwheel: false,
						draggable: true 
					}
				);
				jQuery("#doc-lock").html('<i class="fa fa-unlock" aria-hidden="true"></i>');
				lock = 'lock';
			}
		});
	}
	//
	
	if( _data_list.status == 'found' && typeof(response_data) != "undefined" && response_data !== null ){
		jQuery('#gmap-noresult').html('').hide(); //Hide No Result Div
		var markers = new Array();
		var info_windows = new Array();
		var clusterMarker = [];

		var spiderConfig = {
			 markersWontMove: true, 
			 markersWontHide: true, 
			 keepSpiderfied: true, 
			 circleSpiralSwitchover: 40 
		};
		
		// Create OverlappingMarkerSpiderfier instsance
		var markerSpiderfier = new OverlappingMarkerSpiderfier(map, spiderConfig);
		
		for (var i=0; i < response_data.length; i++) {
			
			markers[i] = new google.maps.Marker({
				position: new google.maps.LatLng(response_data[i].latitude,response_data[i].longitude),
				map: map,
				icon: response_data[i].icon,
				title: response_data[i].title,
				animation: google.maps.Animation.DROP,
				visible: true
			});
			
			bounds.extend(markers[i].getPosition());
			var boxText = document.createElement("div");
			boxText.className = 'directory-detail';
			var innerHTML = "";
			boxText.innerHTML += response_data[i].html.content;
			
			var myOptions = {
				content: boxText,
				disableAutoPan: true,
				maxWidth: 0,
				alignBottom: true,
				pixelOffset: new google.maps.Size( -220, -70 ),
				zIndex: null,
				closeBoxMargin: "0 0 -16px -16px",
				closeBoxURL: dir_close_marker,
				infoBoxClearance: new google.maps.Size( 1, 1 ),
				isHidden: false,
				pane: "floatPane",
				enableEventPropagation: false
			};
		
			var ib = new InfoBox( myOptions );
			attachInfoBoxToMarker( map, markers[i], ib );
			markerSpiderfier.addMarker(markers[i]);  // adds the marker to the spiderfier
		}
		
		var markerClustererOptions = {
			ignoreHidden: true,
			maxZoom: 15,
			styles: [{
				textColor: scripts_vars.dir_cluster_color,
				url: scripts_vars.dir_cluster_marker,
				height: 48,
				width: 48
			}]
		};
		
		// Create cluster	
		new MarkerClusterer(map, markers, markerClustererOptions);
		
		//Set center from theme settings
		if( scripts_vars.center_point === 'enable' ) {
			var dir_latitude	 = scripts_vars.dir_latitude;
			var dir_longitude	 = scripts_vars.dir_longitude;
			currCenter  = new google.maps.LatLng(dir_latitude,dir_longitude);
			google.maps.event.trigger(map, 'resize');
			map.setCenter(currCenter);
		}
		
	} else{
		jQuery('#gmap-noresult').html(gmap_norecod).show();
	}
}
//Assign Info window to marker
function attachInfoBoxToMarker( map, marker, infoBox ){
	google.maps.event.addListener( marker, 'spider_click', function(){
		var scale = Math.pow( 2, map.getZoom() );
		var offsety = ( (100/scale) || 0 );
		var projection = map.getProjection();
		var markerPosition = marker.getPosition();
		var markerScreenPosition = projection.fromLatLngToPoint( markerPosition );
		var pointHalfScreenAbove = new google.maps.Point( markerScreenPosition.x, markerScreenPosition.y - offsety );
		var aboveMarkerLatLng = projection.fromPointToLatLng( pointHalfScreenAbove );
		map.setCenter( aboveMarkerLatLng );
		
		jQuery(".infoBox").hide();
		infoBox.open( map, marker );
		
		infoBox.addListener("domready", function() {
			jQuery('.tg-map-marker').on('click', '.add-to-fav', function (event) {
				event.preventDefault();
				
				var user_status	= scripts_vars.user_status;
				var fav_message	= scripts_vars.fav_message;
				var fav_nothing	= scripts_vars.fav_nothing;
	
				if( user_status == 'false' ){
					jQuery.sticky(fav_message, {classList: 'important', speed: 200, autoclose: 7000});
					return false;	
				}
				
				var _this	= jQuery(this);
				var wl_id	= _this.data('wl_id');
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
							_this.removeClass('tg-like add-to-fav');
							_this.addClass('tg-dislike');
						} else {
							jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
						}
					}
			   });
			});
		});
	});
}


//Init detail page Map Scripts
function docdirect_init_detail_map_script( _data_list ){
	var dir_latitude	 = scripts_vars.dir_latitude;
	var dir_longitude	= scripts_vars.dir_longitude;
	var dir_map_type	 = scripts_vars.dir_map_type;
	var dir_close_marker		  = scripts_vars.dir_close_marker;
	var dir_cluster_marker		= scripts_vars.dir_cluster_marker;
	var dir_map_marker			= scripts_vars.dir_map_marker;
	var dir_cluster_color		 = scripts_vars.dir_cluster_color;
	var dir_zoom				  = scripts_vars.dir_zoom;;
	var dir_map_scroll			= scripts_vars.dir_map_scroll;
	var gmap_norecod			  = scripts_vars.gmap_norecod;
	var map_styles			    = scripts_vars.map_styles;


	if( _data_list.status == 'found' ){
		var response_data	= _data_list.users_list;
	    if( typeof(response_data) != "undefined" && response_data !== null ) {
			var location_center = new google.maps.LatLng(response_data[0].latitude,response_data[0].longitude);
		} else {
				var location_center = new google.maps.LatLng(dir_latitude,dir_longitude);
		}
	} else{
		var location_center = new google.maps.LatLng(dir_latitude,dir_longitude);
	}

	
	if(dir_map_type == 'ROADMAP'){
		var map_id = google.maps.MapTypeId.ROADMAP;
	} else if(dir_map_type == 'SATELLITE'){
		var map_id = google.maps.MapTypeId.SATELLITE;
	} else if(dir_map_type == 'HYBRID'){
		var map_id = google.maps.MapTypeId.HYBRID;
	} else if(dir_map_type == 'TERRAIN'){
		var map_id = google.maps.MapTypeId.TERRAIN;
	} else {
		var map_id = google.maps.MapTypeId.ROADMAP;
	}
	
	var scrollwheel	= true;
	var lock		   = 'unlock';
	
	if( dir_map_scroll == 'false' ){
		scrollwheel	= false;
		lock		   = 'lock';
	}
	
	var mapOptions = {
		center: location_center,
		zoom: parseInt( dir_zoom ),
		mapTypeId: map_id,
		scaleControl: true,
		scrollwheel: scrollwheel,
		disableDefaultUI: true
	}
	
	var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
	
	var styles = docdirect_get_map_styles(map_styles);
	if(styles != ''){
		var styledMap = new google.maps.StyledMapType(styles, {name: 'Styled Map'});
		map.mapTypes.set('map_style', styledMap);
		map.setMapTypeId('map_style');
	}
		
	var bounds = new google.maps.LatLngBounds();
	
	//Zoom In
	if(  document.getElementById('doc-mapplus') ){ 
		 google.maps.event.addDomListener(document.getElementById('doc-mapplus'), 'click', function () {      
		   var current	= parseInt( map.getZoom(),10 );
		   current++;
		   if(current>20){
			   current=20;
		   }
		   map.setZoom(current);
		   jQuery(".infoBox").hide();
		});  
	}
	
	//Zoom Out
	if(  document.getElementById('doc-mapminus') ){ 
		google.maps.event.addDomListener(document.getElementById('doc-mapminus'), 'click', function () {      
			var current	= parseInt( map.getZoom(),10);
			current--;
			if(current<0){
				current=0;
			}
			map.setZoom(current);
			jQuery(".infoBox").hide();
		});  
	}
	
	//Lock Map
	if( document.getElementById('doc-lock') ){ 
		google.maps.event.addDomListener(document.getElementById('doc-lock'), 'click', function () {
			if(lock == 'lock'){
				map.setOptions({ 
						scrollwheel: true,
						draggable: true 
					}
				);
				
				jQuery("#doc-lock").html('<i class="fa fa-unlock-alt" aria-hidden="true"></i>');
				lock = 'unlock';
			}else if(lock == 'unlock'){
				map.setOptions({ 
						scrollwheel: false,
						draggable: false 
					}
				);
				
				jQuery("#doc-lock").html('<i class="fa fa-lock" aria-hidden="true"></i>');
				lock = 'lock';
			}
		});
	}

	//
	if( _data_list.status == 'found' && typeof(response_data) != "undefined" && response_data !== null ){
		jQuery('#gmap-noresult').html('').hide(); //Hide No Result Div
		var markers = new Array();
		var info_windows = new Array();
		
		for (var i=0; i < response_data.length; i++) {
			markers[i] = new google.maps.Marker({
				position: new google.maps.LatLng(response_data[i].latitude,response_data[i].longitude),
				map: map,
				icon: response_data[i].icon,
				title: response_data[i].title,
				animation: google.maps.Animation.DROP,
				visible: true
			});
		
			bounds.extend(markers[i].getPosition());
			
			var boxText = document.createElement("div");
			
			boxText.className = 'directory-detail';
			var innerHTML = "";
			boxText.innerHTML += response_data[i].html.content;
			
			var myOptions = {
				content: boxText,
				disableAutoPan: true,
				maxWidth: 0,
				alignBottom: true,
				pixelOffset: new google.maps.Size( 65, 15 ),
				zIndex: null,
				infoBoxClearance: new google.maps.Size( 1, 1 ),
				isHidden: false,
				closeBoxURL: dir_close_marker,
				pane: "floatPane",
				enableEventPropagation: false
			};
		
			var ib = new InfoBox( myOptions );
			attachInfoBoxToMarker( map, markers[i], ib );
			ib.open(map,markers[i]);

		}
		
		map.fitBounds(bounds);
		
		var listener = google.maps.event.addListener(map, "idle", function() { 
			  if (map.getZoom() > 16) {
				  map.setZoom(parseInt( dir_zoom )); 
			  	  google.maps.event.removeListener(listener); 
			  }
		});

		/* Marker Clusters */
		var markerClustererOptions = {
			ignoreHidden: true,
			styles: [{
				textColor: scripts_vars.dir_cluster_color,
				url: scripts_vars.dir_cluster_marker,
				height: 48,
				width: 48
			}]
		};
		
		var markerClusterer = new MarkerClusterer( map, markers, markerClustererOptions );
	} else{
		jQuery('#gmap-noresult').html(gmap_norecod).show();
	}
}
//Set Spiderfy Markers
function MapSpiderfyMarkers(markers){
    for (var i = 0; i < markers.length; i++) {
        if(typeof oms !== 'undefined'){
           oms.addMarker(markers[i]);
        }
    }
}