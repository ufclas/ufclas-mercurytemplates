/**
 * Extend Post Intro Block with social sharing controls
 */
(function(wp) {
    const { addFilter } = wp.hooks;
    const { createHigherOrderComponent } = wp.compose;
    const { Fragment } = wp.element;
    const { InspectorControls } = wp.blockEditor;
    const { PanelBody, PanelRow, ToggleControl } = wp.components;

    // Add attributes
    addFilter(
        'blocks.registerBlockType',
        'ufclas/post-intro-attributes',
        function(settings, name) {
            if (name !== 'create-block/single-post-intro') {
                return settings;
            }

            // Add social sharing attributes if they don't exist
            if (!settings.attributes.showFacebook) {
                settings.attributes = {
                    ...settings.attributes,
                    showFacebook: { type: 'boolean', default: true },
                    showTwitter: { type: 'boolean', default: true },
                    showEmail: { type: 'boolean', default: true },
                    showLinkedin: { type: 'boolean', default: true },
                    showBluesky: { type: 'boolean', default: true }
                };
            }

            return settings;
        }
    );

    // Add controls to editor
    const withInspectorControls = createHigherOrderComponent((BlockEdit) => {
        return (props) => {
            if (props.name !== 'create-block/single-post-intro') {
                return wp.element.createElement(BlockEdit, props);
            }

            const { attributes, setAttributes } = props;
            const { showFacebook, showTwitter, showEmail, showLinkedin, showBluesky } = attributes;

            // Check if we're editing a post (not a page or other post type)
            const postType = wp.data.select('core/editor').getCurrentPostType();
            const isPost = postType === 'post';

            return wp.element.createElement(
                Fragment,
                null,
                wp.element.createElement(BlockEdit, props),
                wp.element.createElement(
                    InspectorControls,
                    null,
                    wp.element.createElement(
                        PanelBody,
                        { title: 'Social Sharing Options', initialOpen: false },
                        // Show notice only for posts
                        isPost && wp.element.createElement(
                            'div',
                            {
                                style: {
                                    marginBottom: '16px',
                                    padding: '12px',
                                    backgroundColor: '#f0f0f0',
                                    borderLeft: '4px solid #007cba',
                                    fontSize: '12px',
                                    lineHeight: '1.4'
                                }
                            },
                            wp.element.createElement(
                                'strong',
                                null,
                                'Note: '
                            ),
                            'Make sure all desired social buttons are also enabled in the post\'s Social Sharing meta box (in the sidebar). Both settings must be enabled for buttons to appear.'
                        ),
                        wp.element.createElement(
                            PanelRow,
                            null,
                            wp.element.createElement(
                                ToggleControl,
                                {
                                    label: 'Facebook',
                                    checked: showFacebook,
                                    onChange: function(value) { setAttributes({ showFacebook: value }) }
                                }
                            )
                        ),
                        wp.element.createElement(
                            PanelRow,
                            null,
                            wp.element.createElement(
                                ToggleControl,
                                {
                                    label: 'Twitter',
                                    checked: showTwitter,
                                    onChange: function(value) { setAttributes({ showTwitter: value }) }
                                }
                            )
                        ),
                        wp.element.createElement(
                            PanelRow,
                            null,
                            wp.element.createElement(
                                ToggleControl,
                                {
                                    label: 'Email',
                                    checked: showEmail,
                                    onChange: function(value) { setAttributes({ showEmail: value }) }
                                }
                            )
                        ),
                        wp.element.createElement(
                            PanelRow,
                            null,
                            wp.element.createElement(
                                ToggleControl,
                                {
                                    label: 'LinkedIn',
                                    checked: showLinkedin,
                                    onChange: function(value) { setAttributes({ showLinkedin: value }) }
                                }
                            )
                        ),
                        wp.element.createElement(
                            PanelRow,
                            null,
                            wp.element.createElement(
                                ToggleControl,
                                {
                                    label: 'Bluesky',
                                    checked: showBluesky,
                                    onChange: function(value) { setAttributes({ showBluesky: value }) }
                                }
                            )
                        )
                    )
                )
            );
        };
    }, 'withInspectorControls');

    addFilter(
        'editor.BlockEdit',
        'ufclas/post-intro-controls',
        withInspectorControls
    );
})(window.wp);