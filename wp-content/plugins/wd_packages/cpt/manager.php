<?php 
if (!class_exists('WD_Package_Metabox')) {
	class WD_Package_Metabox{
		/**
		 * Refers to a single instance of this class.
		 */
        private static $instance = null;
        
        // Ensure construct function is called only once
		private static $called = false;

		public static function get_instance() {
			if ( null == self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
        }

		public function __construct(){
            // Ensure construct function is called only once
			if ( static::$called ) return;
            static::$called = true;

            $this->constant();
            $this->load_template();

            add_action('admin_enqueue_scripts', array($this, 'backend_libs'));
			add_action('wp_enqueue_scripts', array($this,'frontend_libs'));
        }
        protected function constant(){
			define('WD_PACKAGE_METABOX'		,   plugin_dir_path( __FILE__ ) );
			define('WD_PACKAGE_METABOX_URI'	,   plugins_url( '', __FILE__ ) );
        }
        
        public function load_template(){
            $template_file = array(
                'header', 
                'footer', 
                'banner', 
                'templates'
            );
            foreach($template_file as $file){
				if( file_exists(WD_PACKAGE_METABOX."/register/{$file}.php") ){
					require_once WD_PACKAGE_METABOX."/register/{$file}.php";
				}	
			}
        }

        
        public function frontend_libs(){
			wp_register_style('wd-advance-post-type-inline-css', 	WD_PACKAGE_METABOX_URI.'/css/inline.css');
		}

        public function backend_libs(){
			wp_enqueue_script( 'clipboard-polyfill.promise-js', WD_PACKAGE_METABOX_URI.'/js/clipboard-polyfill.promise.js', array('jquery'), false, true);
			wp_enqueue_script( 'wd-metabox-script-js', WD_PACKAGE_METABOX_URI.'/js/wd_script.js', array('jquery'), false, true);
		}
        
        public function get_text_field($data = array()){
            $default = array(
                "title" => "",
                "desc" => "",
                "placeholder" => "",
                "field_name" => "no_name_available",
                "value" => "",
            );
            $data = wp_parse_args($data, $default); ?>
            <tr>
                <th scope="row">
                    <label><?php echo $data['title']; ?>:</label>
                    <p class="description"><?php echo $data['desc']; ?></p>
                </th>
                <td><input type="text" class="wd-full-width" 
                    name="<?php echo $data['field_name']; ?>" 
                    value="<?php echo htmlspecialchars($data['value']); ?>" 
                    placeholder="<?php echo $data['placeholder']; ?>"/>
                </td>
            </tr>
            <?php
        }

        public function get_shortcode_field($data = array()){
            $default = array(
                "title" => "",
                "desc" => "",
                "placeholder" => "",
                "shortcode" => "",
                "args" => array(),
            );
            $data = wp_parse_args($data, $default);
            $random_id = 'wd-shortcode-field-'.mt_rand();
            $shortcode = '';
            if (count($data['args']) && shortcode_exists($data['shortcode'])) {
                $shortcode .= '['.$data['shortcode'];
                foreach ($data['args'] as $key => $value) {
                    if ($value) {
                        $shortcode .= ' '.$key.'='.$value;
                    }
                }
                $shortcode .= ']';  
            } ?>
            <tr>
                <th scope="row">
                    <label><?php echo $data['title']; ?>:</label>
                    <p class="description"><?php echo $data['desc']; ?></p>
                </th>
                <td><input readonly type="text" id="<?php echo esc_attr($random_id); ?>" class="wd-full-width"  
                    value="<?php echo $shortcode; ?>" 
                    placeholder="<?php echo $data['placeholder']; ?>"/>
                    <a href="#" data-target_id="<?php echo esc_attr($random_id); ?>" class="wd-copy-text">
                        <?php echo __('Copy' ,'wd_packages'); ?>
                    </a>
                    <span class="wd-copy-status" style="display:none;"><i><?php echo __('(Copying...)' ,'wd_packages'); ?></i></span>
                </td>
            </tr>
            <?php
        }

        public function get_textarea_field($data = array()){
            $default = array(
                "title" => "",
                "desc" => "",
                "placeholder" => "",
                "field_name" => "no_name_available",
                "value" => "",
            );
            $data = wp_parse_args($data, $default); ?>
            <tr>
                <th scope="row">
                    <label><?php echo $data['title']; ?>:</label>
                    <p class="description"><?php echo $data['desc']; ?></p> 
                </th>
                <td><textarea class="wd-full-width" 
                    name="<?php echo $data['field_name']; ?>" 
                    rows="10"
                    placeholder="<?php echo $data['placeholder']; ?>"><?php echo $data['value']; ?></textarea>
                </td>
            </tr>
            <?php
        }

        public function get_editor_field($data = array()){
            $default = array(
                "title" => "",
                "desc" => "",
                "placeholder" => "",
                "field_name" => "no_name_available",
                "value" => "",
            );
            $data = wp_parse_args($data, $default);
            $setting = array(
                'textarea_rows' => 4,
                'media_buttons' => false,
            );
            ?>
            <tr>
                <th scope="row">
                    <label><?php echo $data['title']; ?>:</label>
                    <p class="description"><?php echo $data['desc']; ?></p> 
                </th>
                <td>
                    <!-- <textarea class="wd-textarea-editor" name="<?php //echo $data['field_name']; ?>" rows="10"><?php //echo $data['value']; ?></textarea> -->
                    <?php wp_editor( $data['value'], $data['field_name'], $setting); ?>
                </td>
            </tr>
            <?php
        }

        public function get_select_field($data = array()){
            $default = array(
                "title" => "",
                "desc" => "",
                "placeholder" => "",
                "field_name" => "no_name_available",
                "options" => "",
                "value" => "",
            );
            $data = wp_parse_args($data, $default); ?>
            <tr>
                <th scope="row">
                    <label><?php echo $data['title']; ?>:</label>
                    <p class="description"><?php echo $data['desc']; ?></p>
                </th>
                <td>
                    <select name="<?php echo $data['field_name']; ?>"">
                        <?php if ($data['options']) { ?>
                            <?php foreach ($data['options'] as $key => $value): ?>
                                <?php $selected = selected($data['value'], $key, false); ?>
                                <option value="<?php echo esc_html($key) ?>" <?php echo esc_attr($selected) ?>><?php echo esc_html($value) ?></option>
                            <?php endforeach; ?>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <?php
        }

        public function get_radio_field($data = array()){
            var_dump($data);
            $default = array(
                "title" => "",
                "desc" => "",
                "field_name" => "no_name_available",
                "options" => "",
                "value" => "",
            );
            $data = wp_parse_args($data, $default); ?>
            <tr>
                <th scope="row">
                    <label><?php echo $data['title']; ?>:</label>
                    <p class="description"><?php echo $data['desc']; ?></p>
                </th>
                <td>
                    <?php if ($data['options']) { ?>
                        <div class="wd-radio-list">
                            <?php foreach ($data['options'] as $key => $value): ?>
                                <?php 
                                $checked = (is_array($data['value']) && in_array($key, $data['value'])) ? "checked" : "";
                                $random_id 	= mt_rand();
                                ?>
                                <div class="wd-radio-item">
                                    <input type="radio" id="wd-radio-<?php echo $random_id; ?>" 
                                        name="<?php echo $data['field_name']; ?>" 
                                        value="<?php echo esc_html($key) ?>"
                                        <?php echo $checked; ?> />
                                    <label for="wd-radio-<?php echo $random_id; ?>"><?php echo esc_html($value) ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php } ?>
                </td>
            </tr>
            <?php
        }
        
        public function get_checkbox_field($data = array()){
            $default = array(
                "title" => "",
                "desc" => "",
                "field_name" => "no_name_available",
                "options" => "",
                "value" => "",
            );
            $data = wp_parse_args($data, $default); ?>
            <tr>
                <th scope="row">
                    <label><?php echo $data['title']; ?>:</label>
                    <p class="description"><?php echo $data['desc']; ?></p>
                </th>
                <td>
                    <?php if ($data['options']) { ?>
                        <div class="wd-checkbox-list">
                            <?php foreach ($data['options'] as $key => $value): ?>
                                <?php 
                                $checked = (is_array($data['value']) && in_array($key, $data['value'])) ? "checked" : "";
                                $random_id 	= mt_rand();
                                ?>
                                <div class="wd-checkbox-item">
                                    <input type="checkbox" id="wd-checkbox-<?php echo $random_id; ?>" 
                                        name="<?php echo $data['field_name']; ?>[]" 
                                        value="<?php echo esc_html($key) ?>"
                                        <?php echo $checked; ?> />
                                    <label for="wd-checkbox-<?php echo $random_id; ?>"><?php echo $value ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php } ?>
                </td>
            </tr>
            <?php
        }

        public function get_qrcode_field($data = array()){
            $default = array(
                "title" => "",
                "desc" => "",
                "placeholder" => "",
                "field_name" => "no_name_available",
                "value" => array(
                    'qr_type' => 'url',
                    'qr_content' => ''
                ),
            );
            $random_id 	= mt_rand();
            $data = wp_parse_args($data, $default);
            $qr_type = $data['value']['qr_type'];
            $qr_content = $data['value']['qr_content'];
            ?>
            <tr>
                <th scope="row">
                    <label><?php echo $data['title']; ?>:</label>
                    <p class="description"><?php echo $data['desc']; ?></p>
                </th>
                <td class="wd-qrcode-wrap">
                    <div class="wd-qrcode-col" style="display: none;">
                        <label><?php esc_html_e('Type','wd_package'); ?>:</label>
                        <!-- <select name="<?php echo $data['field_name']; ?>[qr_type]">
                            <option value="url" <?php echo ($qr_type === 'url') ? 'selected' : ''; ?>><?php esc_html_e('URL','wd_package'); ?></option>
                            <option value="text" <?php echo ($qr_type === 'text') ? 'selected' : ''; ?>><?php esc_html_e('TEXT','wd_package'); ?></option>
                            <option value="email" <?php echo ($qr_type === 'email') ? 'selected' : ''; ?>><?php esc_html_e('EMAIL','wd_package'); ?></option>
                            <option value="sms" <?php echo ($qr_type === 'sms') ? 'selected' : ''; ?>><?php esc_html_e('SMS','wd_package'); ?></option>
                            <option value="contact" <?php echo ($qr_type === 'contact') ? 'selected' : ''; ?>><?php esc_html_e('CONTACT','wd_package'); ?></option>
                            <option value="content" <?php echo ($qr_type === 'content') ? 'selected' : ''; ?>><?php esc_html_e('CONTENT','wd_package'); ?></option>
                        </select> -->
                    </div>
                    <input type="hidden" name="<?php echo $data['field_name']; ?>[qr_type]" value="url">
                    <div class="wd-qrcode-col">
                        <label style="display: none;"><?php esc_html_e('CONTENT','wd_package'); ?>:</label>
                        <input type="text" class="wd-full-width" 
                                name="<?php echo $data['field_name']; ?>[qr_content]" 
                                value="<?php echo htmlspecialchars($qr_content); ?>" 
                                placeholder="<?php echo $data['placeholder']; ?>"/>
                    </div>
                </td>
            </tr>
            <?php
        }

        public function get_image_field($data = array()){
            $default = array(
                "title" => "",
                "desc" => "",
                "placeholder" => "",
                "field_name" => "no_name_available",
                "value" => "",
                "default" => WD_THEME_IMAGES."/default.jpg",
            );
            $random_id 	= mt_rand();
            $data = wp_parse_args($data, $default);
            ?>
            <tr>
                <th scope="row">
                    <label><?php echo $data['title']; ?>:</label>
                    <p class="description"><?php echo $data['desc']; ?></p>
                </th>
                <td>
                    <img style="width: auto; display: block; max-width: 100%;" class="wd-image-preview" id="image-preview-<?php echo $random_id; ?>" src="<?php echo ($data['value'] && is_numeric($data['value'])) ? esc_url(wp_get_attachment_url($data['value'])) : $data['default']; ?>"  width="100%" />
                    <input type="hidden" name="<?php echo $data['field_name']; ?>" id="<?php echo 'image-value-'.$random_id; ?>" value="<?php echo ($data['value'] && is_numeric($data['value'])) ? esc_attr($data['value'] ) : ""; ?>" />

                    <a 	class="wd_media_lib_select_btn button button-primary button-large" 
                        data-type="image"
                        data-image_value="<?php echo 'image-value-'.$random_id; ?>" 
                        data-image_preview="image-preview-<?php echo $random_id; ?>">
                        <?php esc_html_e('Select Image File','wd_package'); ?>
                    </a>

                    <a 	class="wd_media_lib_clear_btn button" 
                        data-image_value="<?php echo 'image-value-'.$random_id; ?>" 
                        data-image_preview="image-preview-<?php echo $random_id; ?>" 
                        data-image_default=<?php echo $data['default']; ?>>
                        <?php esc_html_e('Reset','wd_package'); ?>
                    </a>
                </td>
            </tr>
            <?php
        }

        public function get_file_field($data = array()){
            $default = array(
                "title" => "",
                "desc" => "",
                "placeholder" => "",
                "button_title" => esc_html__( 'Select File', 'wd_package' ),
                "field_name" => "no_name_available",
                "value" => "",
                "default" => WD_THEME_IMAGES."/default.jpg",
            );
            $random_id 	= mt_rand();
            $data = wp_parse_args($data, $default);
            ?>
            <tr>
                <th scope="row">
                    <label><?php echo $data['title']; ?>:</label>
                    <p class="description"><?php echo $data['desc']; ?></p>
                </th>
                <td>
                    <input readonly type="text" class="wd-full-width"
                            name="<?php echo $data['field_name']; ?>" 
                            id="<?php echo 'image-value-'.$random_id; ?>" 
                            value="<?php echo esc_url($data['value'] ) ?>" />

                    <a 	class="wd_media_lib_select_btn button button-primary button-large" 
                        data-type="video"
                        data-image_value="<?php echo 'image-value-'.$random_id; ?>" 
                        data-image_preview="image-preview-<?php echo $random_id; ?>">
                        <?php echo $data['button_title']; ?>
                    </a>

                    <a 	class="wd_media_lib_clear_btn button" 
                        data-image_value="<?php echo 'image-value-'.$random_id; ?>" 
                        data-image_preview="image-preview-<?php echo $random_id; ?>" 
                        data-image_default=<?php echo $data['default']; ?>>
                        <?php esc_html_e('Reset','wd_package'); ?>
                    </a>
                </td>
            </tr>
            <?php
        }

        public function get_map_field($data = array()){
            $default = array(
                "title" => "",
                "desc" => "",
                "placeholder" => "",
                "field_name" => "no_name_available",
                "value" => array(
                    "lat" => 10.762622, 
                    "lng" => 106.660172, 
                    "address" => "" 
                ),
            );
            $data = wp_parse_args($data, $default);
            $lat = $data['value']['lat'];
            $lng = $data['value']['lng'];
            $address = $data['value']['address'];
            ?>
            <tr>
                <th scope="row">
                    <label><?php echo $data['title']; ?>:</label>
                    <p class="description"><?php echo $data['desc']; ?></p>
                </th>
                <td>
                    <div class="wd-map-select-location">

                        <div class="wd-location_data">
                            <input type="hidden" id="wd-location_data-lat" name="<?php echo $data['field_name']; ?>[lat]" value="<?php echo $lat; ?>"/>
                            <input type="hidden" id="wd-location_data-lng" name="<?php echo $data['field_name']; ?>[lng]" value="<?php echo $lng; ?>"/>
                            <input type="hidden" id="wd-location_data-address" name="<?php echo $data['field_name']; ?>[address]" value="<?php echo htmlspecialchars($address); ?>"/>
                        </div>
                        
                        <div class="pac-card" id="pac-card">
                            <div>
                                <div id="title">
                                    <?php echo __( 'Location Search', 'wd_package' ); ?>
                                </div>
                                <div id="type-selector" class="pac-controls" style="display: none;">
                                    <input type="radio" name="type" id="changetype-all" checked="checked">
                                    <label for="changetype-all"><?php echo __( 'All', 'wd_package' ); ?></label>
                                    <input type="radio" name="type" id="changetype-establishment">
                                    <label for="changetype-establishment"><?php echo __( 'Establishments', 'wd_package' ); ?></label>
                                    <input type="radio" name="type" id="changetype-address">
                                    <label for="changetype-address"><?php echo __( 'Addresses', 'wd_package' ); ?></label>
                                    <input type="radio" name="type" id="changetype-geocode">
                                    <label for="changetype-geocode"><?php echo __( 'Geocodes', 'wd_package' ); ?></label>
                                </div>
                                <div id="strict-bounds-selector" class="pac-controls" style="display: none;">
                                    <input type="checkbox" id="use-strict-bounds" value="">
                                    <label for="use-strict-bounds"><?php echo __( 'Strict Bounds', 'wd_package' ); ?></label>
                                </div>
                            </div>
                            <div id="pac-container">
                                <input id="pac-input" type="text" value="<?php echo $address; ?>" placeholder="<?php echo $data['placeholder']; ?>">
                            </div>
                        </div>
                        <div id="select_location_map"></div>
                        <div id="infowindow-content">
                            <img src="" width="16" height="16" id="place-icon">
                            <span id="place-name" class="title"></span><br>
                            <span id="place-address"></span>
                        </div>
                    </div>
                </td>
            </tr>
            <?php 
                /**
                * package: google_map
                * var: api_key 		
                * var: zoom 	
                */
                $google_map_package = get_option('wd-theme-options-packages', array());
                if (!count($google_map_package) || !is_array($google_map_package)) return;
                extract($google_map_package['google_map']); 
                
                $google_map_url = "//maps.googleapis.com/maps/api/js";
                $google_map_url = add_query_arg( array(
                    'key' => $api_key,
                    'libraries' => 'places',
                    'callback' => 'google_map_admin_script'
                ), $google_map_url );

            ?>
            
            <script>
                if (typeof google_map_admin_script != 'function') {
                    function google_map_admin_script() {
                        var lat = <?php echo $lat; ?>,
                            lng = <?php echo $lng; ?>;
                        // This example requires the Places library. Include the libraries=places
                        // parameter when you first load the API. For example:
                        // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
                        var latlng = new google.maps.LatLng(lat, lng);
                        var map = new google.maps.Map(document.getElementById('select_location_map'), {
                            center: latlng,
                            zoom: <?php echo $zoom; ?>,
                        });

                        var geocoder = new google.maps.Geocoder();


                        var card = document.getElementById('pac-card');
                        var input = document.getElementById('pac-input');
                        var types = document.getElementById('type-selector');
                        var strictBounds = document.getElementById('strict-bounds-selector');

                        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);

                        var autocomplete = new google.maps.places.Autocomplete(input);

                        // Bind the map's bounds (viewport) property to the autocomplete object,
                        // so that the autocomplete requests use the current map bounds for the
                        // bounds option in the request.
                        autocomplete.bindTo('bounds', map);

                        // Set the data fields to return when the user selects a place.
                        autocomplete.setFields(
                            ['address_components', 'geometry', 'icon', 'name']);

                        var infowindow = new google.maps.InfoWindow();
                        var infowindowContent = document.getElementById('infowindow-content');
                        infowindow.setContent(infowindowContent);

                        var marker = new google.maps.Marker({
                            map: map,
                            anchorPoint: new google.maps.Point(0, -29),
                            position: latlng,
                        });
                        var place = autocomplete.getPlace();

                        autocomplete.addListener('place_changed', function() {
                            infowindow.close();
                            marker.setVisible(false);
                            var place = autocomplete.getPlace();
                            
                            // Set info to save
                            document.getElementById('wd-location_data-lat').value = place.geometry.location.lat();
                            document.getElementById('wd-location_data-lng').value = place.geometry.location.lng();
                            document.getElementById('wd-location_data-address').value = converterSpecialCharacter(document.getElementById('pac-input').value);

                            if (!place.geometry) {
                                // User entered the name of a Place that was not suggested and
                                // pressed the Enter key, or the Place Details request failed.
                                window.alert("No details available for input: '" + place.name + "'");
                                return;
                            }

                            // If the place has a geometry, then present it on a map.
                            if (place.geometry.viewport) {
                                map.fitBounds(place.geometry.viewport);
                            } else {
                                map.setCenter(place.geometry.location);
                                map.setZoom(17); // Why 17? Because it looks good.
                            }
                            marker.setPosition(place.geometry.location);
                            marker.setVisible(true);

                            var address = '';
                            if (place.address_components) {
                                address = [
                                    (place.address_components[0] && place.address_components[0].short_name || ''),
                                    (place.address_components[1] && place.address_components[1].short_name || ''),
                                    (place.address_components[2] && place.address_components[2].short_name || '')
                                ].join(' ');
                            }

                            infowindowContent.children['place-icon'].src = place.icon;
                            infowindowContent.children['place-name'].textContent = place.name;
                            infowindowContent.children['place-address'].textContent = address;
                            infowindow.open(map, marker);
                        });

                        function converterSpecialCharacter(str) {
                            var i = str.length,
                                aRet = [];
                            while (i--) {
                                var iC = str[i].charCodeAt();
                                if (iC < 65 || iC > 127 || (iC>90 && iC<97)) {
                                    aRet[i] = '&#'+iC+';';
                                } else {
                                    aRet[i] = str[i];
                                }
                            }
                            return aRet.join('');
                        }

                        // Sets a listener on a radio button to change the filter type on Places
                        // Autocomplete.
                        function setupClickListener(id, types) {
                            var radioButton = document.getElementById(id);
                            radioButton.addEventListener('click', function() {
                                autocomplete.setTypes(types);
                            });
                        }

                        setupClickListener('changetype-all', []);
                        setupClickListener('changetype-address', ['address']);
                        setupClickListener('changetype-establishment', ['establishment']);
                        setupClickListener('changetype-geocode', ['geocode']);

                        document.getElementById('use-strict-bounds')
                            .addEventListener('click', function() {
                                console.log('Checkbox clicked! New state=' + this.checked);
                                autocomplete.setOptions({
                                    strictBounds: this.checked
                                });
                            });
                    }
                }
                </script>
            <script src="<?php echo $google_map_url; ?>"></script>
        <?php 
        }
	}
	WD_Package_Metabox::get_instance();
}