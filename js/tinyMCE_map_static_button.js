(function() {
    tinymce.PluginManager.add('comomapStaticButton', function( editor, url ) {
        editor.addButton( 'comomapStaticButton', {
            text: tinyMCE_map.button_name,
            icon: false,
            onclick: function() {
				
				var styleOptions = jQuery.parseJSON(tinyMCE_map.map_style_select_options);
				editor.windowManager.open( {
					title: tinyMCE_map.button_title,
					body: [
                        {
                            type: 'textbox',
                            name: 'mapid',
                            label: 'Map ID',
                            value: ''
                        },
						{
                            type: 'textbox',
                            name: 'markers',
                            label: 'Markers',
                            value: '1'
                        },
						{
                            type   : 'listbox',
                            name   : 'mapstyle',
                            label  : 'Map Style',
                            values : styleOptions
                        },
						
						// Static Map Options
						/*{
                            type: 'textbox',
                            name: 'mapscape',
                            label: 'Map Scale (for Static Maps)',
                            value: '1'
                        },
						{
                            type: 'textbox',
                            name: 'width',
                            label: 'Map Width (for Static Maps)',
                            value: '400'
                        },
						{
                            type: 'textbox',
                            name: 'height',
                            label: 'Map Height (for Static Maps)',
                            value: '400'
                        },*/
						/*{
                            type   : 'colorpicker',
                            name   : 'markercolor',
                            label  : 'Icon Color (for Static Maps)'
                        },*/
						
						// Dynamic Map Options
						{
                            type: 'textbox',
                            name: 'address',
                            label: 'Label (Separate multiple with "|")',
                            value: ''
                        },
						{
                            type: 'textbox',
                            name: 'address',
                            label: 'Address (Separate multiple with "|")',
                            value: ''
                        },
						{
                            type: 'textbox',
                            name: 'lat',
                            label: 'Latitude (Separate multiple with "|")',
                            value: ''
                        },
						{
                            type: 'textbox',
                            name: 'long',
                            label: 'Longitude (Separate multiple with "|")',
                            value: ''
                        },
						{
                            type: 'textbox',
                            name: 'centerlat',
                            label: 'Center Latitude (if different)',
                            value: ''
                        },
						{
                            type: 'textbox',
                            name: 'centerlong',
                            label: 'Center Longiture (if different)',
                            value: ''
                        },
						{
                            type: 'textbox',
                            name: 'link',
                            label: 'Link (Separate multiple with "|")',
                            value: ''
                        },
						{
                            type: 'textbox',
                            name: 'phone',
                            label: 'Phone (Separate multiple with "|")',
                            value: ''
                        },
						{
                            type: 'textbox',
                            name: 'zoom',
                            label: 'Zoom (1 to 20)',
                            value: '15'
                        },
						
						// Map Icon Image
						{
                            type: 'button',
                            name: 'icon-btn',
                            label: 'Map Icon (WP image ID)',
                            text: tinyMCE_map.icon_button_title,
                            classes: 'logo_upload_button',
                        },
						{
                            type: 'textbox',
                            name: 'icon',
                            value: '',
                            classes: 'img_input_image',
							style: 'display: none;'
                        },
						
						// Map Logo Image
						{
                            type: 'button',
                            name: 'logo-btn',
                            label: 'Map Logo (WP image ID)',
                            text: tinyMCE_map.logo_button_title,
                            classes: 'logo_upload_button',
                        },
						{
                            type: 'textbox',
                            name: 'logo',
                            value: '',
                            classes: 'logo_input_image',
							style: 'display: none;'
                        }
                    ],
					
					//[comomap maps=# OF MAPS maptype=STATIC/DYNAMIC mapid=MAP_ELEMENT_ID scale=1/2 width=WIDTH height=HEIGHT address=STREET_ADDRESS lat=LATTITUDE long=LONGITUDE centerlat=CENTER_LATITUDE centerlong=CENTER_LONGITUDE link=GOOGLE_LINK phone=PHONE zoom=ZOOM style=1-10 icon=icon markercolor=COLOR]
					
                    onsubmit: function( e ) {
						
						var mapid = (e.data.mapid ? ' mapid='+e.data.mapid : '');
						var markers = (e.data.markers ? ' markers='+e.data.markers : '');
						var mapstyle = (e.data.mapstyle ? ' mapstyle='+e.data.mapstyle : '');
						var address = (e.data.address ? ' address=\''+e.data.address +'\'' : '');
						var lat = (e.data.lat ? ' lat=\''+e.data.lat +'\'' : '');
						var long = (e.data.long ? ' long=\''+e.data.long +'\'' : '');
						var centerlat = (e.data.centerlat ? ' centerlat=\''+e.data.centerlat +'\'' : '');
						var centerlong = (e.data.centerlong ? ' centerlong=\''+e.data.centerlong +'\'' : '');
						var link = (e.data.link ? ' link=\''+e.data.link +'\'' : '');
						var phone = (e.data.phone ? ' phone=\''+e.data.phone +'\'' : '');
						var zoom = (e.data.zoom ? ' zoom='+e.data.zoom : '');
						var icon = (e.data.icon ? ' icon='+e.data.icon : '');
						var logo = (e.data.logo ? ' logo='+e.data.logo : '');
						
                        editor.insertContent( '[comomap maps=1 '+ mapid + markers + mapstyle + address + lat + long + centerlat + centerlong + link + phone + zoom + icon + logo +']');
                    }
                });
            },
        });
    });
})();
jQuery(document).ready(function($){
    
	// Map Icon Upload
	$(document).on('click', '.mce-icon_upload_button', upload_icon_tinymce);
    function upload_icon_tinymce(e) {
        e.preventDefault();
        var $input_field = $('.mce-icon_input_image');
        var custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Add Icon Image',
            button: {
                text: 'Add Icon Image'
            },
            multiple: false
        });
        custom_uploader.on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            $input_field.val(attachment.id);
        });
        custom_uploader.open();
    }
	
	// Logo Upload Button
	$(document).on('click', '.mce-logo_upload_button', upload_logo_tinymce);
    function upload_logo_tinymce(e) {
        e.preventDefault();
        var $input_field = $('.mce-logo_input_image');
        var custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Add Logo Image',
            button: {
                text: 'Add Logo Image'
            },
            multiple: false
        });
        custom_uploader.on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            $input_field.val(attachment.id);
        });
        custom_uploader.open();
    }
	
});