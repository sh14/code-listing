(function () {
	const { registerBlockType } = wp.blocks;
	const { useBlockProps, InspectorControls } = wp.blockEditor;
	const { PanelBody, TextControl, TextareaControl } = wp.components;
	const { Fragment } = wp.element;

	registerBlockType('oi/code-block', {
		title: 'Code Listing',
		icon: 'editor-code',
		category: 'formatting',
		attributes: {
			content: {
				type: 'string',
				default: '',
			},
			dataLang: {
				type: 'string',
				default: '',
			},
			dataLangCaption: {
				type: 'string',
				default: '',
			},
		},
		edit({ attributes, setAttributes }) {
			const blockProps = useBlockProps();

			return wp.element.createElement(
				Fragment,
				null,
				wp.element.createElement(
					InspectorControls,
					null,
					wp.element.createElement(
						PanelBody,
						{ title: 'Block attributes', initialOpen: true },
						wp.element.createElement(TextControl, {
							label: 'Language',
							value: attributes.dataLang,
							onChange: (val) => setAttributes({ dataLang: val }),
						}),
						wp.element.createElement(TextControl, {
							label: 'Caption',
							value: attributes.dataLangCaption,
							onChange: (val) => setAttributes({ dataLangCaption: val }),
						})
					)
				),
				wp.element.createElement(
					'div',
					blockProps,
					wp.element.createElement(TextareaControl, {
						label: 'Type code here',
						value: attributes.content,
						onChange: (val) => setAttributes({ content: val }),
						rows: 8,
					})
				)
			);
		},
		save() {
			return null;
		}
	});
})();
