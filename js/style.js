( function( blocks, editor, element, hooks, components ) {
	var el = element.createElement;
	var InnerBlocks = editor.InnerBlocks;
	var InspectorControls = editor.InspectorControls;
	var ToggleControl = components.ToggleControl;
	var PanelBody = components.PanelBody;

	blocks.registerBlockStyle("core/paragraph", {
		name: "bp_intro",
		label: "イントロ",
	});

	// Refer : https://developer.wordpress.org/block-editor/developers/richtext/
	blocks.registerBlockType( 'bp-blocks/bp-subheader', {
		title: 'bpサブヘッダー',
		icon: 'format-quote',
		discription: '見出しの直前に使うことで、サブヘッドで装飾することができます',
		category: 'common',
		attributes: {
			content: {
				type: 'string',
				source: 'html',
				selector: 'div',
			},
		},
	 
		edit: function( props ) {
			return element.createElement( editor.RichText, {
				tagName: 'div',  // The tag here is the element output and editable in the admin
				className: props.className,
				value: props.attributes.content, // Any existing content, either from the database or an attribute default
				onChange: function( content ) {
					props.setAttributes( { content: content } ); // Store updated content as a block attribute
				},
			} );
		},
	 
		save: function( props ) {
			return element.createElement( editor.RichText.Content, {
				tagName: 'div', value: props.attributes.content // Saves <h2>Content added in the editor...</h2> to the database for frontend display
			} );
		},

		transforms: {
			from: [
				{
					type: 'block',
					blocks: [ 'core/paragraph' ],
					transform: function ( attributes ) {
						return blocks.createBlock( 'bp-blocks/bp-subheader', {
							content: attributes.content,
						} );
					},
				},
			]
		}
	} );

	/**
	 *  BPコンテナ用
	 * Refer: https://wordpress.stackexchange.com/questions/338108/wrap-gutenberg-block-div-in-other-elements-extra-html
	 */


	blocks.registerBlockType( 'bp-blocks/bp-container', {
		title: 'bpコンテナ',
		icon: 'align-center',
		discription: '幅一杯のセクションを作ることができます。文章のメリハリを出すために利用します。',
		category: 'layout',
		attributes: {
			removeMargin: {
				type: 'boolean',
				default: false
			}
		},
		edit: function( props ) {
		  return [
				el(
					'div',
					{ className: props.className },
					el( InnerBlocks )
				),
				el ( InspectorControls, {},
					el(PanelBody, {
						title: '表示設定',
						initialOpen: true
					},
						el (
							ToggleControl,
							{
								label: '上下の余白削除',
								checked: props.attributes.removeMargin,
								onChange: (value) => {
									props.setAttributes( { removeMargin: value } );
								},
							}
						)
					)
				)
			];
		},
		save: function( props ) {
		  return el(
			'div',
			{ className: props.className },
			el( InnerBlocks.Content ),
		  );
		}
	} );

	// div の入れ子を実現するためにフックを使う
	var modifySaveHtml = function(block, props, attributes) {
		if (props.name !== "bp-blocks/bp-container") {
			return block;
		}

		let rm_class = '';
		if ( attributes.removeMargin == true ) {
			rm_class = ' bp-rm-margin';
		}

		return el(
			"div",
				{ className: "container-wrapper" + rm_class },
				el("div", { className: "container-content" }, block)
			);
	};
	wp.hooks.addFilter(
		"blocks.getSaveElement",
		"bp-blocks/bp-container",
		modifySaveHtml
	);

	var modifyEditHtml = function(BlockEdit) {
		return function(props) {
			if (props.name !== "bp-blocks/bp-container") {
				return el( BlockEdit, props );
			}

			let rm_class = '';
			if ( props.attributes.removeMargin == true ) {
				rm_class = ' bp-rm-margin';
			}

			return el(			
				"div",
				{ className: "container-wrapper" + rm_class },
				el(
					"div",
					{ className: "container-content" },
					el(
						BlockEdit,
						props
					)
				)
			);
		};
	};
	hooks.addFilter(
		"editor.BlockEdit",
		"bp-blocks/bp-container",
		modifyEditHtml
	);
}(
	window.wp.blocks,
	window.wp.blockEditor,
	window.wp.element,
	window.wp.hooks,
	window.wp.components
));
