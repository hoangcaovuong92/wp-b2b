<?php 
if (!class_exists('WD_Widgets_Fields')) {
	class WD_Widgets_Fields extends WP_Widget{
		/**
		 * Refers to a single instance of this class.
		 */
		private static $instance = null;

		public static function get_instance() {
			if ( null == self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		protected $list_widget_field_default, $widget_name, $widget_desc, $widget_slug, $callback;

		public function __construct(){
			//$this->widget_init();
		}

		public function widget_init(){
			$this->init_settings();
			if (empty($this->list_widget_field_default) || empty($this->widget_name) || empty($this->widget_desc) || empty($this->widget_slug) || empty($this->callback)) return;
			$widget_setting = array(
				'name' 		=> $this->widget_name,
				'desc' 		=> $this->widget_desc,
				'slug' 	  	=> $this->widget_slug,
			);
			$widget_ops 	= array('classname' => $widget_setting['slug'], 'description' => $widget_setting['desc']);
			$control_ops 	= array('width' => 400, 'height' => 350);
			parent::__construct($widget_setting['slug'], $widget_setting['name'], $widget_ops);
		}

		public function init_settings(){
			$this->list_widget_field_default = array(
				'widget_title'		=> 'Test Widget',
				'select'			=> 'option_1',
				'multiple_select'	=> 'option_1',
				'checkbox'			=> 'option_1',
				'color'				=> '#ffffff',
				'textarea'			=> '',
				'image'				=> '',
				'hidden'			=> '',
				'class'				=> ''
			);
			$this->widget_name = esc_html__('WD - Test','wd_package');
			$this->widget_desc = esc_html__('Test Widget.','wd_package');
			$this->widget_slug = 'wd_test';
			$this->callback = 'wd_test_function';
		}

		function form( $instance ) {
			foreach ($this->list_widget_field_default as $key => $value) {
	    		${$key}   	= isset( $instance[$key] ) ? esc_attr( $instance[$key] ) : $value;
			}

            $select_arr = array(
				'option_1' => esc_html__( 'Option 1', 'wd_package' ),
				'option_2' => esc_html__( 'Option 2', 'wd_package' ),
			);
			$this->text_field(
    			esc_html__( 'Widget Title:', 'wd_package' ), 
    			$this->get_field_name( 'widget_title' ),
    			$this->get_field_id( 'widget_title' ),
    			$widget_title, 
    			''
    		);
			$this->select_field(
                esc_html__( 'Select:', 'wd_package' ), 
                $this->get_field_name( 'select' ), 
                $this->get_field_id( 'select' ), 
                $select_arr, 
                $select
			); 
			// $this->select_field_multi(
            //     esc_html__( 'Multiple Select:', 'wd_package' ), 
            //     $this->get_field_name( 'multiple_select' ), 
            //     $this->get_field_id( 'multiple_select' ), 
            //     $select_arr, 
            //     $multiple_select
			// ); 
			// $this->checkbox_field(
            //     esc_html__( 'Checkbox:', 'wd_package' ), 
            //     $this->get_field_name( 'checkbox' ), 
            //     $this->get_field_id( 'checkbox' ), 
            //     $select_arr, 
            //     $checkbox
			// ); 
			$this->color_field(
                esc_html__( 'Color:', 'wd_package' ), 
                $this->get_field_name( 'color' ), 
                $this->get_field_id( 'color' ), 
				$color,
    			''
			); 
			$this->textarea_field(
                esc_html__( 'Textarea:', 'wd_package' ), 
                $this->get_field_name( 'textarea' ),
                $this->get_field_id( 'textarea' ),
                $textarea, 
                ''
			);
			$this->image_field(	
        		esc_html__( 'Image:', 'wd_package' ), 
        		$this->get_field_name( 'image' ), 
        		$this->get_field_id( 'image' ), 
        		$image, 
        		'', 
        		''
        	); 
            $this->hidden_field(
    			$this->get_field_name( 'hidden' ),
    			$this->get_field_id( 'hidden' ),
    			$hidden
    		);
    		$this->text_field(
    			esc_html__( 'Extra class name:', 'wd_package' ), 
    			$this->get_field_name( 'class' ),
    			$this->get_field_id( 'class' ),
    			$class, 
    			esc_html__("Style particular content element differently - add a class name and refer to it in custom CSS.", 'wd_package')
    		);
	    }

	    function widget( $args, $instance ) {
            extract($args);
            $title   = (!empty($instance['widget_title'])) ? apply_filters('widget_title', $instance['widget_title'], $instance, $this->id_base) : '';
			echo $before_widget;
                if ($title) echo $before_title . $title . $after_title;
				if (function_exists($this->callback)) {
					echo call_user_func($this->callback, $instance);
				}
	        echo $after_widget;
	    }

	    function update( $new_instance, $old_instance ) {
	        $instance = $old_instance;
	        foreach ($this->list_widget_field_default as $key => $value) {
	    		$instance[$key]   = isset( $instance[$key] ) ? strip_tags( $new_instance[$key] ) : $value;
	    	}
	        return $instance;
	    }

		public function image_field($title, $field_name, $field_id, $selected = '', $default = '', $desc = ''){ ?>
			<?php $random_num 	= mt_rand(); ?>
			<p>
                <label><?php echo esc_html($title); ?></label>
				<div style="width: 200px;">
					<img id="wd_custom_image_view_<?php echo esc_attr( $random_num ); ?>" src="<?php echo ($selected && is_numeric($selected)) ? esc_url(wp_get_attachment_url($selected)) : $default; ?>"  width="100%" />
				</div>
					<input type="hidden" name="<?php echo $field_name; ?>" id="wd_custom_image_<?php echo esc_attr( $random_num ); ?>" value="<?php echo ($selected && is_numeric($selected)) ? esc_attr($selected ) : ''; ?>" />

					<a 	class="wd_media_lib_select_btn button" 
						data-type="image"
						data-image_value="wd_custom_image_<?php echo esc_attr( $random_num ); ?>" 
						data-image_preview="wd_custom_image_view_<?php echo esc_attr( $random_num ); ?>">
						<?php esc_html_e('Select Image File', 'wd_package'); ?>
					</a>

					<a 	class="wd_media_lib_clear_btn button" 
						data-image_value="wd_custom_image_<?php echo esc_attr( $random_num ); ?>" 
						data-image_preview="wd_custom_image_view_<?php echo esc_attr( $random_num ); ?>" 
						data-image_default="<?php echo esc_url($default); ?>">
						<?php esc_html_e('Reset', 'wd_package'); ?>
					</a>
        		<span style="display: block;" class="wd-widget-description"><?php echo esc_attr($desc); ?></span>
            </p>
			<?php 
		}

		public function select_field($title, $field_name, $field_id, $list = array(), $selected = 0, $desc = ''){ ?>
			<p>
				<label for="<?php echo esc_attr($field_id); ?>"><?php echo esc_html($title); ?></label>
				<select class="widefat" name="<?php echo esc_attr($field_name); ?>" id="<?php echo esc_attr($field_id); ?>">
					<?php if (count($list) > 0): ?>
						<?php foreach( $list as $key => $value ){ ?>
							<option value="<?php echo esc_attr($key); ?>" <?php echo ($selected==$key)?'selected':'' ?> ><?php echo esc_attr($value); ?></option>
						<?php } ?>
					<?php endif ?>
				</select>
				<span style="clear: both;" class="wd-widget-description"><?php echo esc_attr($desc); ?></span>
			</p>
			<?php 
		}

		public function select_field_multi($title, $field_name, $field_id, $list = array(), $selected = array(), $desc = ''){ ?>
			<p>
				<label for="<?php echo esc_attr($field_id); ?>"><?php echo esc_html($title); ?></label>
				<?php 
				printf (
	                '<select multiple="multiple" name="%s" id="%s" class="widefat" size="15" style="margin-bottom:10px">',
	                $field_name,
	                $field_id
				);
				
				if ($selected && !is_array($selected)) {
					$selected = array($selected);
				}

	            // Each individual option
	            foreach( $list as $id => $name) {
	                printf(
	                    '<option value="%s" %s style="margin-bottom:3px;">%s</option>',
	                    $id,
	                    in_array( $id, $selected) ? 'selected="selected"' : '',
	                    $name
	                );
	            }
				echo '</select>'; ?>
				<span style="clear: both;" class="wd-widget-description"><?php echo esc_attr($desc); ?></span>
			</p>
			<?php 
		}

		public function checkbox_field($title, $field_name, $field_id, $list = '', $selected = 0, $desc = ''){  ?>
			<p>
				<label for="<?php echo esc_attr($field_id); ?>"><?php echo esc_html($title); ?></label>
				<?php 
				$checked = '';
				foreach($list as $id => $name) {
					if ($selected) {
						if (is_array($selected)) {
							$checked = in_array($id, $selected) ? 'checked' : '';
						}else{
							$checked = $id == $selected ? 'checked' : '';
						}
					} ?>
			    	<input id="<?php echo $field_id; ?>" name="<?php echo $field_name; ?>[]" type="checkbox" value="<?php echo $id; ?>" <?php echo $checked; ?> /> <?php echo $name; ?>
			    <?php } ?>
	            <span style="clear: both;" class="wd-widget-description"><?php echo esc_attr($desc); ?></span>
			</p>
			<?php 
		}

		public function hidden_field($field_name, $field_id, $value = ''){  ?>
			<p>
	            <input class="widefat" id="<?php echo esc_attr($field_id); ?>" name="<?php echo esc_attr($field_name); ?>" type="hidden" value="<?php echo $value; ?>" />
			</p>
			<?php 
		}

		public function color_field($title, $field_name, $field_id, $value = '', $desc = ''){  ?>
			<p>
				<label for="<?php echo esc_attr($field_id); ?>"><?php echo esc_html($title); ?></label>
	            <input class="widefat wd_colorpicker_select" id="<?php echo esc_attr($field_id); ?>" name="<?php echo esc_attr($field_name); ?>" type="text" value="<?php echo $value; ?>" />
	            <span style="clear: both;" class="wd-widget-description"><?php echo esc_attr($desc); ?></span>
			</p>
			<?php 
		}

		public function text_field($title, $field_name, $field_id, $value = '', $desc = ''){  ?>
			<p>
				<label for="<?php echo esc_attr($field_id); ?>"><?php echo esc_html($title); ?></label>
	            <input class="widefat" id="<?php echo esc_attr($field_id); ?>" name="<?php echo esc_attr($field_name); ?>" type="text" value="<?php echo $value; ?>" />
	            <span style="clear: both;" class="wd-widget-description"><?php echo esc_attr($desc); ?></span>
			</p>
			<?php 
		}

		public function textarea_field($title, $field_name, $field_id, $value = '', $desc = ''){  ?>
			<p>
				<label for="<?php echo esc_attr($field_id); ?>"><?php echo esc_html($title); ?></label>
				<textarea class="widefat" rows="5" cols="20" id="<?php echo esc_attr($field_id); ?>" name="<?php echo esc_attr($field_name); ?>"><?php echo $value; ?></textarea>
	            <span style="clear: both;" class="wd-widget-description"><?php echo esc_attr($desc); ?></span>
			</p>
			<?php 
		}
	}
	WD_Widgets_Fields::get_instance();
}