/**
 * WPDANCE FRAMEWORK 2018.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */
(function (blocks, editor, components, i18n, element, data, apiFetch) {
	//console.log(components);
	//console.log(editor);

	const el = element.createElement; //Create Element
	const { __ } = i18n; //Translate
	const { registerBlockType } = blocks;
	const { 
		ServerSideRender, //Preview
		PanelBody, //Block setting Panel
		Button, //Button field settings
		TextControl, //Text field settings
		TextareaControl, //Textarea field settings
		SelectControl, //Select field settings
		RadioControl, //Radio field settings
		RangeControl, //Range field settings
		ToggleControl, //Toggle field settings
		ColorPicker, //Color picker field settings
		DatePicker, //Date picker field settings
		DateTimePicker //Date time picker field settings
	} = components;
	const { withSelect } = data;
	const { 
		BlockControls, //Block toolbar
		InspectorControls, //Block settings
		RichText, //Editable Field
		MediaUpload, //Upload image field
		AlignmentToolbar //Alignment Toolbar for text field
	} = editor;

	//WD API
	var WD_API = {
		posts: [{
			value: -1, 
			label: 'Loading...'
		}],
		post_categories: [{
			value: -1, 
			label: 'Loading...'
		}],
		products: [{
			value: -1, 
			label: 'Loading...'
		}],
		product_categories: [{
			value: -1, 
			label: 'Loading...'
		}],
	};
	//Get list posts
	apiFetch({path: "/wd-rest/v1/get_posts"}).then(posts => {
		WD_API.posts = posts.response;
		return WD_API.posts;
	});

	//Get list post categories
	apiFetch({path: "/wd-rest/v1/get_post_categories"}).then(post_categories => {
		WD_API.post_categories = post_categories.response;
		return WD_API.post_categories;
	});

	//Get list products
	apiFetch({path: "/wd-rest/v1/get_products"}).then(products => {
		WD_API.products = products.response;
		return WD_API.products;
	});

	//Get list product categories
	apiFetch({path: "/wd-rest/v1/get_product_categories"}).then(product_categories => {
		WD_API.product_categories = product_categories.response;
		return WD_API.product_categories;
	});

	var WDBlock = wd_block; //Infomation pass from PHP
	//console.log(WDBlock);
	registerBlockType(WDBlock.name, { // The name of our block. Must be a string with prefix. Example: my-plugin/my-custom-block.
		title: WDBlock.title, // The title of our block.
		description: WDBlock.desc, // The description of our block.
		icon: WDBlock.icon, // Dashicon icon for our block. Custom icons can be added using inline SVGs.
		category: WDBlock.category, // The category of the block.
		attributes: WDBlock.attributes,  // Necessary for saving block content.

		edit: function (props) {
			var attributes = props.attributes;
			var ToolbarElements = []; // Display controls when the block is clicked on.
			var BlockSettingElements = {}; //Add form fields to block setting panel
			var InterfaceElements = []; //Add HTML to interface
			var BlockGroup = {}; //List of block group

			//Display preview icon (boolean)
			var preview = (typeof(attributes.preview) != undefined && attributes.preview) ? attributes.preview : false;
			
			//Select image button on block setting panel
			var onImageRender = function (obj) {
				return el(Button, {
						className : 'editor-post-featured-image__toggle',
						style: {
							marginBottom: '10px',
						},
						onClick: obj.open
					},
					// Add Dashicon for media upload button.
					el('svg', {
							className: 'dashicon dashicons-edit',
							width: '20',
							height: '20'
						},
						el('path', {
							d: 'M2.25 1h15.5c.69 0 1.25.56 1.25 1.25v15.5c0 .69-.56 1.25-1.25 1.25H2.25C1.56 19 1 18.44 1 17.75V2.25C1 1.56 1.56 1 2.25 1zM17 17V3H3v14h14zM10 6c0-1.1-.9-2-2-2s-2 .9-2 2 .9 2 2 2 2-.9 2-2zm3 5s0-6 3-6v10c0 .55-.45 1-1 1H5c-.55 0-1-.45-1-1V8c2 0 3 4 3 4s1-3 3-3 3 2 3 2z'
						})
					))
			}

			//Select image button on toolbar
			var onImageRenderToolbar = function (obj) {
				return el(Button, {
						className : 'components-icon-button components-toolbar__control',
						onClick: obj.open
					},
					// Add Dashicon for media upload button.
					el('svg', {
							className: 'dashicon dashicons-edit',
							width: '20',
							height: '20'
						},
						el('path', {
							d: 'M2.25 1h15.5c.69 0 1.25.56 1.25 1.25v15.5c0 .69-.56 1.25-1.25 1.25H2.25C1.56 19 1 18.44 1 17.75V2.25C1 1.56 1.56 1 2.25 1zM17 17V3H3v14h14zM10 6c0-1.1-.9-2-2-2s-2 .9-2 2 .9 2 2 2 2-.9 2-2zm3 5s0-6 3-6v10c0 .55-.45 1-1 1H5c-.55 0-1-.45-1-1V8c2 0 3 4 3 4s1-3 3-3 3 2 3 2z'
						})
					))
			}

			//Display title on Interface
			var displayTitle = function (title, content) {
				return WDBlock.show_current_value && content ? el('p', {
						style: {
							margin: '3px 0',
							fontSize: '13px',
							lineHeight: '1.5',
						}
					}, 
					'-' + title + ': ',
					el('span', {
							style: {
								fontWeight: 'bold',
								padding: '1px 7px',
								marginLeft: '5px',
								background: '#ffffff',
								border: '1px solid #ddd',
							},
						},
						content
					)
				) : '';
			}

			//Convert text to variable name
			var converterString = function (string) {
				return string.replace(" ", "").toLowerCase();
			}

			//Parameter analysis from php
			if (typeof WDBlock.attributes !== 'undefined'){
				for (let fieldName in WDBlock.attributes) {
					if (!fieldName || fieldName === 'className') continue;
					var fieldData = WDBlock.attributes[fieldName];
					if (typeof fieldData === 'undefined') continue;
					//Field display status - check required field if exist
					var display = (fieldData.required) ? eval('"' + attributes[fieldData.required[0]] + '"' + fieldData.required[1] + '"' + fieldData.required[2] + '"') : true;
					
					//If display field, register attribute field otherwise unset
					if (display) {
						attributes[fieldName] = attributes[fieldName] || attributes[fieldName] == '' || attributes[fieldName] == 0 ? attributes[fieldName] : fieldData.default;
					}else{
						attributes[fieldName] = null;
					}

					var group = fieldData.group;
					if (group) {
						group = converterString(group);
						if (typeof(BlockGroup[group]) === 'undefined') {
							BlockGroup[group] = fieldData.group;
							BlockSettingElements[group] = [];
						}
					}

					switch(fieldData.control) {
						case 'MediaUpload':
							var imageIDField = fieldData.img_id;
							if (display) {
								//Add select image icon to toolbar when click block
								fieldData.show_on_toolbar && ToolbarElements.push(
									el('div', {
											className: 'components-toolbar'
										},
										el(MediaUpload, {
											onSelect: function (media) {
												props.setAttributes({
													[fieldName]: media.url,
													[imageIDField]: media.id,
												})
											},
											type: 'image',
											render: onImageRenderToolbar
										})
									)
								);

								//Add form fields to block setting panel
								BlockSettingElements[group].push(
									el('p', {
										class: 'components-base-control__label'
										}, fieldData.title
									),
									el(MediaUpload, {
											onSelect: function (media) {
												props.setAttributes({
													[fieldName]: media.url,
													[imageIDField]: media.id,
												})
											},
											type: 'image',
											render: onImageRender
										}, 
									),
									attributes[fieldName] && el('div', {
											className: attributes[imageIDField] ? 'wd-image-active' : 'wd-image-inactive',
											style: attributes[imageIDField] ? {
												backgroundImage: 'url(' + attributes[fieldName] + ')',
												width: '100%',
												height: '200px',
												backgroundSize: 'cover',
												marginBottom: '10px',
												position: 'relative',
											} : {}
										},
										el(Button, {
												className: '',
												style: {
													position: 'absolute',
													top: '5px',
													right: '5px',
													color: '#ffffff',
													fontSize: '15px',
													width: '30px',
													height: '30px',
													borderRadius: '50%',
													background: 'rgba(0, 0, 0 , .6)',
													display: 'flex',
													justifyContent: 'center',
													alignItems: 'center',
												},
												onClick: function () {
													props.setAttributes({
														[fieldName]: null,
														[imageIDField]: null,
													})
												},
											},
											el('i', {
												className: 'fa fa-times'
											})
										)
									),
								);

								//Add HTML to interface
								WDBlock.show_current_value && attributes[fieldName] && InterfaceElements.push(
									el('p', {
											style: {
												margin: '0',
												fontSize: '13px',
												lineHeight: '1.5',
											}
										}, 
										'-' + WDBlock.attributes[imageIDField].title + ': ',
										el('a', {
												href: attributes[fieldName],
												target: '_blank'
											},
											attributes[imageIDField]
										),
									)
								);
							} //End if display status}
							break;
						case 'TextControl':
							if (display) {
								//Add HTML to interface
								BlockSettingElements[group].push(
									el(TextControl, {
										type: fieldData.type,
										label: fieldData.title,
										value: attributes[fieldName],
										onChange: function (newValue) {
											props.setAttributes({
												[fieldName]: newValue
											})
										}
									})
								);
								InterfaceElements.push(
									displayTitle(fieldData.title, attributes[fieldName])
								);
							}
							break;
						case 'RangeControl':
							if (display) {
								//Add HTML to interface
								BlockSettingElements[group].push(
									el(RangeControl, {
										label: fieldData.title,
										value: attributes[fieldName],
										min: fieldData.min,
										max: fieldData.max,
										onChange: function (newValue) {
											props.setAttributes({
												[fieldName]: newValue
											})
										}
									})
								);
								InterfaceElements.push(
									displayTitle(fieldData.title, attributes[fieldName])
								);
							}
							break;
						case 'ToggleControl':
							if (display) {
								//Add HTML to interface
								BlockSettingElements[group].push(
									el(ToggleControl, {
										label: fieldData.title,
										checked: attributes[fieldName],
										onChange: function (newValue) {
											props.setAttributes({
												[fieldName]: newValue
											})
										}
									})
								);
							}
							break;
						case 'TextareaControl':
							if (display) {
								//Add HTML to interface
								BlockSettingElements[group].push(
									el(TextareaControl, {
										type: fieldData.type,
										label: fieldData.title,
										value: attributes[fieldName],
										onChange: function (newValue) {
											props.setAttributes({
												[fieldName]: newValue
											})
										}
									})
								);
								InterfaceElements.push(
									displayTitle(fieldData.title, attributes[fieldName])
								);
							}
							break;
						case 'RichText':
							if (display) {
								//Add HTML to interface
								InterfaceElements.push(
									el(RichText, {
										tagName: fieldData.selector,
										placeholder: fieldData.title,
										keepPlaceholderOnFocus: true,
										value: attributes[fieldName],
										onChange: function (newValue) {
											props.setAttributes({
												[fieldName]: newValue
											})
										}
									}),
								);
							}
							break;
						case 'AlignmentToolbar':
							if (display) {
								//Add Alignment Toolbar icons to toolbar when click block
								!preview && ToolbarElements.push(
									el(AlignmentToolbar, { // Display alignment toolbar within block controls.
										value: attributes[fieldName],
										onChange: function (newValue) {
											props.setAttributes({
												[fieldName]: newValue
											})
										}
									})
								);
							}
							break;
						case 'ServerSideRender':
							if (display) {
								//Add switch preview icon to toolbar when click block
								ToolbarElements.push(
									el(Button, {
										className: '',
										style: {},
										onClick: function () {
											props.setAttributes({
												[fieldName]: !attributes[fieldName],
											})
										},
									},
									el('i', {
										style: {
											fontSize: '20px'
										},
										className: attributes[fieldName] ? 'fa fa-eye-slash' : 'fa fa-eye'
									})
								));
							} //End if display status}
							break;
						case 'API':
							if (display) {
								//Add form fields to block setting panel
								BlockSettingElements[group].push(
									el(SelectControl, {
										label: fieldData.title,
										value: attributes[fieldName],
										options: WD_API[fieldData.type],
										onChange: function (newValue) {
											props.setAttributes({
												[fieldName]: newValue
											})
										}
									}),
								);
								//Add HTML to interface
								InterfaceElements.push(
									displayTitle(fieldData.title, attributes[fieldName])
								);
							}
							break;
						case 'SelectControl':
							if (display) {
								//Add form fields to block setting panel
								BlockSettingElements[group].push(
									el(SelectControl, {
										label: fieldData.title,
										value: attributes[fieldName],
										options: WDBlock.attributes[fieldName].options,
										onChange: function (newValue) {
											props.setAttributes({
												[fieldName]: newValue
											})
										}
									}),
								);
								//Add HTML to interface
								InterfaceElements.push(
									displayTitle(fieldData.title, attributes[fieldName])
								);
							}
							break;
						case 'RadioControl':
							if (display) {
								//Add form fields to block setting panel
								BlockSettingElements[group].push(
									el(RadioControl, {
										label: fieldData.title,
										value: attributes[fieldName],
										selected: attributes[fieldName],
										options: WDBlock.attributes[fieldName].options,
										onChange: function (newValue) {
											props.setAttributes({
												[fieldName]: newValue
											})
										}
									}),
								);
								//Add HTML to interface
								InterfaceElements.push(
									displayTitle(fieldData.title, attributes[fieldName])
								);
							}
							break;
						case 'ColorPicker':
							if (display) {
								//Add form fields to block setting panel
								BlockSettingElements[group].push(
									el(ColorPicker, {
										label: fieldData.title,
										color: attributes[fieldName],
										disableAlpha: fieldData.disableAlpha,
										onChangeComplete: function (newValue) {
											props.setAttributes({
												// [fieldName]: newValue.hex
												[fieldName]: 'rgba(' + newValue.rgb.r + ', ' + newValue.rgb.g + ', ' + newValue.rgb.b + ', ' + newValue.rgb.a + ')'
											})
										},
									}),
								);
								//Add HTML to interface
								InterfaceElements.push(
									displayTitle(fieldData.title, attributes[fieldName])
								);
							}
							break;
						case 'DatePicker':
							if (display) {
								//Add form fields to block setting panel
								BlockSettingElements[group].push(
									el(DatePicker, {
										label: fieldData.title,
										selected: attributes[fieldName],
										onChange: function (newValue) {
											props.setAttributes({
												[fieldName]: newValue
											})
										},
									}),
								);
								//Add HTML to interface
								InterfaceElements.push(
									displayTitle(fieldData.title, attributes[fieldName])
								);
							}
							break;
					}
				}
			}

			var PanelBodyContent = [];
			var i = 1;
			for (let panel in BlockSettingElements) {
				PanelBodyContent.push(
					el(PanelBody, {
							title: BlockGroup[panel],
							className: '',
							initialOpen: i == 1 ? true : false
						},
						BlockSettingElements[panel]
					)
				);
				i++;
			}

			var return_content = [];
			if (preview) {
				return_content = [
					el(ServerSideRender, {
						block: WDBlock.name,
						attributes: attributes
					}),
					el(BlockControls, {
							key: 'controls'
						}, // Display controls when the block is clicked on.
						ToolbarElements
					),
					el(InspectorControls, {
							key: 'inspector'
						}, // Display the block options in the inspector panel.
						PanelBodyContent
					),
				];
			}else{
				return_content = [
					el(BlockControls, {
							key: 'controls'
						}, // Display controls when the block is clicked on.
						ToolbarElements
					),
					el(InspectorControls, {
							key: 'inspector'
						}, // Display the block options in the inspector panel.
						PanelBodyContent,
					),
					//Display on content page when edit
					el('div', {
							className: 'wd-block-wrap ' + props.className,
							style: {
								border: '1px dashed #ddd',
								padding: '10px',
								// textAlign: attributes.alignment ? attributes.alignment : 'inherit',
								textAlign: 'center',
								fontFamily: '-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif',
								background: 'rgba(139,139,150,.1)',
							}
						},
						el('h4', {
								style: {
									margin: 0
								}
							}, 
							WDBlock.title
						),
						el('p', {
								style: {
									margin: 0,
									borderBottom: WDBlock.show_current_value ? '1px solid #e2e4e7' : 'none',
									fontSize: '14px',
									marginBottom: WDBlock.show_current_value ? '10px' : '0',
								}
							},
							WDBlock.desc
						),
						InterfaceElements
					)
				];
			}

			return return_content;
		},

		save: function (props) {
			return null;
		}
	})
})(
	window.wp.blocks,
	window.wp.editor,
	window.wp.components,
	window.wp.i18n,
	window.wp.element,
	window.wp.data,
	window.wp.apiFetch
)