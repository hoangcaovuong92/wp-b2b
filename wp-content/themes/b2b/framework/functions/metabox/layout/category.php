<?php 
if (is_admin() && class_exists('Tax_Meta_Class')) {
	/*
	* configure taxonomy custom fields
	*/
	$config = array(
	   'id' => 'wd_layout_meta_box',                    // meta box id, unique per meta box
	   'title' => 'Custom Layout',                      // meta box title
	   'pages' => array('product_cat'),     			// taxonomy name, accept categories, post_tag and custom taxonomies
	   'context' => 'normal',                           // where the meta box appear: normal (default), advanced, side; optional
	   'fields' => array(),                             // list of meta fields (can be added by field arrays)
	   'local_images' => true,                          // Use local or hosted images (meta box images for add/remove)
	   'use_with_theme' => true                         //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
	);
	 
	/*
	* Initiate your taxonomy custom fields
	*/
	 
	$taxonomy_meta = new Tax_Meta_Class($config);

	$html_header 		= 	apply_filters('wd_filter_header_choices', array('value_return' => 'name'));
	$html_footer 		= 	apply_filters('wd_filter_footer_choices', array('value_return' => 'name'));

	/*
	* Add fields 
	*/
	 
	//text field
	//$taxonomy_meta->addText('text_field_id',array('name'=> 'My Text '));
	//textarea field
	//$taxonomy_meta->addTextarea('textarea_field_id',array('name'=> 'My Textarea '));
	//checkbox field
	//$taxonomy_meta->addCheckbox('checkbox_field_id',array('name'=> 'My Checkbox '));
	//select field
	$taxonomy_meta->addSelect(	'_wd_custom_header', 
								$html_header, 
								array('name'=> 'Header', 'std'=> array('')));
	$taxonomy_meta->addSelect(	'_wd_custom_footer', 
								$html_footer, 
								array('name'=> 'Footer', 'std'=> array('')));
	$taxonomy_meta->addSelect(	'custom_layout', 
							   	array(	'0'=> 'Default', 
							   			'0-0-0'=> 'Fullwidth', 
							   			'0-0-1'=> 'Right Sidebar', 
							   			'1-0-0'=> 'Left Sidebar', 
							   			'1-0-1'=> 'Left & Right Sidebar',
							   		), 
							   	array('name'=> 'Layout', 'std'=> array('0')));
	/*$taxonomy_meta->addSelect(	'custom_breadcrumb', 
								array(	'breadcrumb_default'	=> 'Default (Customize)', 
							   			'breadcrumb_banner'		=> 'Background Image', 
							   			'no_breadcrumb'			=> 'No Breadcrumb',
						   			),
								array('name'=> 'Breadcrumb Layout', 'std'=> array('breadcrumb_default')));*/
	//radio field
	//$taxonomy_meta->addRadio('radio_field_id',array('radiokey1'=>'Radio Value1','radiokey2'=>'Radio Value2'),array('name'=> 'My Radio Filed', 'std'=> array('radionkey2')));
	//date field
	//$taxonomy_meta->addDate('date_field_id',array('name'=> 'My Date '));
	//Color field
	//$taxonomy_meta->addColor('color_field_id',array('name'=> 'My Color '));
	//Image field
	//$taxonomy_meta->addImage('image_field_id',array('name'=> 'My Image '));
	//file upload field
	//$taxonomy_meta->addFile('breadcrumb_image',array('name'=> 'Image Breadcrumb'));
	//wysiwyg field
	$taxonomy_meta->addWysiwyg('custom_content',array('name'=> 'Custom Content'));
	//taxonomy field
	//$taxonomy_meta->addTaxonomy('taxonomy_field_id',array('taxonomy' => 'category'),array('name'=> 'My Taxonomy '));
	//posts field
	//$taxonomy_meta->addPosts('posts_field_id',array('post_type' => 'post'),array('name'=> 'My Posts '));
	 
	/*
	* To Create a reapeater Block first create an array of fields
	* use the same functions as above but add true as a last param
	*/
	 
	/*$repeater_fields[] = $taxonomy_meta->addText('re_text_field_id',array('name'=> 'My Text '),true);
	$repeater_fields[] = $taxonomy_meta->addTextarea('re_textarea_field_id',array('name'=> 'My Textarea '),true);
	$repeater_fields[] = $taxonomy_meta->addCheckbox('re_checkbox_field_id',array('name'=> 'My Checkbox '),true);
	$repeater_fields[] = $taxonomy_meta->addImage('image_field_id',array('name'=> 'My Image '),true);*/
	 
	/*
	* Then just add the fields to the repeater block
	*/
	 
	//repeater block
	//$taxonomy_meta->addRepeaterBlock('re_',array('inline' => true, 'name' => 'This is a Repeater Block','fields' => $repeater_fields));
	 
	/*
	* Don't Forget to Close up the meta box deceleration
	*/
	//Finish Taxonomy Extra fields Deceleration
	$taxonomy_meta->Finish();
}
?>