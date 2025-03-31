( function( wp ) {
    var registerBlockType = wp.blocks.registerBlockType;
    var el = wp.element.createElement;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var PanelBody = wp.components.PanelBody;
    var TextControl = wp.components.TextControl;
    var SelectControl = wp.components.SelectControl;
    var ServerSideRender = wp.serverSideRender;
    
    registerBlockType( 'flexible-cod/form-block', {
        title: 'Flexible COD Form',
        icon: 'cart',
        category: 'widgets',
        
        attributes: {
            product_id: {
                type: 'number',
                default: 0
            },
            template: {
                type: 'string',
                default: 'default'
            },
            title: {
                type: 'string',
                default: ''
            },
            button_text: {
                type: 'string',
                default: ''
            }
        },
        
        edit: function( props ) {
            var attributes = props.attributes;
            
            // Get templates from localized variable
            var templates = [];
            if (typeof fcodBlockData !== 'undefined' && fcodBlockData.templates) {
                templates = fcodBlockData.templates.map(function(template) {
                    return { 
                        label: template.name, 
                        value: template.id 
                    };
                });
            } else {
                templates = [
                    { label: 'Default', value: 'default' },
                    { label: 'SmartLook', value: 'smartlook' },
                    { label: 'Clean Modern', value: 'clean' }
                ];
            }
            
            // Get products from localized variable
            var products = [];
            if (typeof fcodBlockData !== 'undefined' && fcodBlockData.products) {
                products = fcodBlockData.products.map(function(product) {
                    return { 
                        label: product.title + ' (#' + product.id + ')', 
                        value: product.id 
                    };
                });
                
                // Add empty option
                products.unshift({ 
                    label: 'No product (custom form)', 
                    value: 0 
                });
            }
            
            return [
                // Inspector controls for the sidebar
                el( InspectorControls, { key: 'inspector' },
                    el( PanelBody, { title: 'Form Settings', initialOpen: true },
                        el( SelectControl, {
                            label: 'Product',
                            value: attributes.product_id,
                            options: products,
                            onChange: function( value ) {
                                props.setAttributes({ product_id: parseInt(value) });
                            }
                        }),
                        
                        el( SelectControl, {
                            label: 'Template',
                            value: attributes.template,
                            options: templates,
                            onChange: function( value ) {
                                props.setAttributes({ template: value });
                            }
                        }),
                        
                        el( TextControl, {
                            label: 'Form Title',
                            value: attributes.title,
                            placeholder: 'Use default',
                            onChange: function( value ) {
                                props.setAttributes({ title: value });
                            }
                        }),
                        
                        el( TextControl, {
                            label: 'Button Text',
                            value: attributes.button_text,
                            placeholder: 'Use default',
                            onChange: function( value ) {
                                props.setAttributes({ button_text: value });
                            }
                        })
                    )
                ),
                
                // Block preview
                el( 'div', { className: 'fcod-block-preview' },
                    el( 'div', { className: 'fcod-block-preview-content' },
                        // If block can use ServerSideRender
                        typeof ServerSideRender !== 'undefined' ? 
                            el( ServerSideRender, {
                                block: 'flexible-cod/form-block',
                                attributes: attributes
                            }) :
                            // Fallback preview if ServerSideRender isn't available
                            el( 'div', { className: 'fcod-block-placeholder' },
                                el( 'div', { className: 'fcod-block-icon' },
                                    el( 'span', { className: 'dashicons dashicons-cart' })
                                ),
                                el( 'h3', {}, 'Flexible COD Form'),
                                el( 'p', {}, 'Template: ' + attributes.template),
                                attributes.product_id > 0 ? 
                                    el( 'p', {}, 'Product ID: ' + attributes.product_id) :
                                    el( 'p', {}, 'No product selected')
                            )
                    )
                )
            ];
        },
        
        save: function() {
            // Dynamic block, rendered on server
            return null;
        }
    } );
} )( window.wp );