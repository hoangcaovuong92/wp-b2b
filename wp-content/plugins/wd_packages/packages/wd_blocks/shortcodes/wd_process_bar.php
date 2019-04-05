<?php
/**
 * Shortcode: wd_process_bar
 */

if (!function_exists('wd_process_bar_function')) {
    function wd_process_bar_function($atts) {
        extract(shortcode_atts(array(
            'process'     	=> '',
			'fullwidth_mode' => false,
            'class'     	=> ''
        ),$atts));
        if (!function_exists('vc_param_group_parse_atts')) return;
		$process = vc_param_group_parse_atts( $process );

		//Fullwidth mode class (gutenberg)
		$class .= ($fullwidth_mode) ? ' alignfull' : '';
		
        ob_start(); ?>
        	<?php if ($process): ?>
        		<div class="wd-shortcode wd-shortcode-process-bar <?php echo esc_attr($class); ?>">
					<?php foreach($process as $item){ ?>
						<div class="wd-single-bar">
							<p class="wd-label-bar"><?php echo $item['label']; ?> <span><?php echo $item['value']; ?>%</span></p>
							<div class="wd-blank-bar">
								<span class="wd-bar" style="width:<?php echo $item['value']; ?>%"></span>
							</div>
						</div>
					<?php } ?>
				</div>
        	<?php endif ?>
		<?php  //endif 
		$output = ob_get_clean();
		wp_reset_query();
		return $output;
	}
}

if (!function_exists('wd_process_bar_vc_map')) {
	function wd_process_bar_vc_map() {
		return array(
			'name' 				=> esc_html__("WD - Process Bar", 'wd_package'),
			'base' 				=> 'wd_process_bar',
			'description' 		=> esc_html__("Process Bar", 'wd_package'),
			'category' 			=> esc_html__("WD - Content", 'wd_package'),
			'icon'        		=> 'icon-wpb-graph',
			"params" 			=> array(
				array(
					'type' => 'param_group',
					'value' => '',
					'param_name' => 'process',
					// Note params is mapped inside param-group:
					'params' => array(
						array(
							'type' => 'textfield',
							'value' => '',
							'heading' => 'Label',
							'param_name' => 'label',
						),
						array(
							'type' => 'textfield',
							'value' => '',
							'heading' => 'Value',
							'param_name' => 'value',
						)
					)
				),
				array(
					'type' 			=> 'textfield',
					'class' 		=> '',
					'heading' 		=> esc_html__("Extra class name", 'wd_package'),
					'description'	=> esc_html__("Style particular content element differently - add a class name and refer to it in custom CSS.", 'wd_package'),
					'admin_label' 	=> true,
					'param_name' 	=> 'class',
					'value' 		=> ''
				)
			)
		);
	}
}