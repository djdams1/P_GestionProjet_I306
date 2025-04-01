<?php

/**
 * Block Styles
 *
 * @link https://developer.wordpress.org/reference/functions/register_block_style/
 *
 * @package lavisto
 * @since 1.0.0
 */

if (function_exists('register_block_style')) {
    /**
     * Register block styles.
     *
     * @since 0.1
     *
     * @return void
     */
    function lavisto_register_block_styles()
    {
        register_block_style(
            'core/columns',
            array(
                'name'  => 'lavisto-boxshadow',
                'label' => __('Box Shadow', 'lavisto')
            )
        );

        register_block_style(
            'core/column',
            array(
                'name'  => 'lavisto-boxshadow',
                'label' => __('Box Shadow', 'lavisto')
            )
        );
        register_block_style(
            'core/column',
            array(
                'name'  => 'lavisto-boxshadow-medium',
                'label' => __('Box Shadow Medium', 'lavisto')
            )
        );
        register_block_style(
            'core/column',
            array(
                'name'  => 'lavisto-boxshadow-large',
                'label' => __('Box Shadow Large', 'lavisto')
            )
        );

        register_block_style(
            'core/group',
            array(
                'name'  => 'lavisto-boxshadow',
                'label' => __('Box Shadow', 'lavisto')
            )
        );
        register_block_style(
            'core/group',
            array(
                'name'  => 'lavisto-boxshadow-medium',
                'label' => __('Box Shadow Medium', 'lavisto')
            )
        );
        register_block_style(
            'core/group',
            array(
                'name'  => 'lavisto-boxshadow-large',
                'label' => __('Box Shadow Larger', 'lavisto')
            )
        );
        register_block_style(
            'core/image',
            array(
                'name'  => 'lavisto-boxshadow',
                'label' => __('Box Shadow', 'lavisto')
            )
        );
        register_block_style(
            'core/image',
            array(
                'name'  => 'lavisto-boxshadow-medium',
                'label' => __('Box Shadow Medium', 'lavisto')
            )
        );
        register_block_style(
            'core/image',
            array(
                'name'  => 'lavisto-boxshadow-larger',
                'label' => __('Box Shadow Large', 'lavisto')
            )
        );
        register_block_style(
            'core/image',
            array(
                'name'  => 'lavisto-image-pulse',
                'label' => __('Iamge Pulse Effect', 'lavisto')
            )
        );
        register_block_style(
            'core/image',
            array(
                'name'  => 'lavisto-boxshadow-hover',
                'label' => __('Box Shadow on Hover', 'lavisto')
            )
        );
        register_block_style(
            'core/image',
            array(
                'name'  => 'lavisto-image-hover-pulse',
                'label' => __('Hover Pulse Effect', 'lavisto')
            )
        );
        register_block_style(
            'core/image',
            array(
                'name'  => 'lavisto-image-hover-rotate',
                'label' => __('Hover Rotate Effect', 'lavisto')
            )
        );
        register_block_style(
            'core/columns',
            array(
                'name'  => 'lavisto-boxshadow-hover',
                'label' => __('Box Shadow on Hover', 'lavisto')
            )
        );

        register_block_style(
            'core/column',
            array(
                'name'  => 'lavisto-boxshadow-hover',
                'label' => __('Box Shadow on Hover', 'lavisto')
            )
        );

        register_block_style(
            'core/group',
            array(
                'name'  => 'lavisto-boxshadow-hover',
                'label' => __('Box Shadow on Hover', 'lavisto')
            )
        );

        register_block_style(
            'core/post-terms',
            array(
                'name'  => 'categories-background-with-round',
                'label' => __('Background with round corner style', 'lavisto')
            )
        );
        register_block_style(
            'core/post-title',
            array(
                'name'  => 'title-hover-primary-color',
                'label' => __('Hover: Primary color', 'lavisto')
            )
        );
        register_block_style(
            'core/post-title',
            array(
                'name'  => 'title-hover-secondary-color',
                'label' => __('Hover: Secondary color', 'lavisto')
            )
        );
        register_block_style(
            'core/button',
            array(
                'name'  => 'button-hover-primary-color',
                'label' => __('Hover: Primary Color', 'lavisto')
            )
        );
        register_block_style(
            'core/button',
            array(
                'name'  => 'button-hover-secondary-color',
                'label' => __('Hover: Secondary Color', 'lavisto')
            )
        );
        register_block_style(
            'core/button',
            array(
                'name'  => 'button-hover-primary-bgcolor',
                'label' => __('Hover: Primary color fill', 'lavisto')
            )
        );
        register_block_style(
            'core/button',
            array(
                'name'  => 'button-hover-secondary-bgcolor',
                'label' => __('Hover: Secondary color fill', 'lavisto')
            )
        );
        register_block_style(
            'core/button',
            array(
                'name'  => 'button-hover-hard-black-shadow',
                'label' => __('Hover: Black Shadow', 'lavisto')
            )
        );
        register_block_style(
            'core/button',
            array(
                'name'  => 'button-hover-hard-black-shadow-sec',
                'label' => __('Hover: Black Shadow Secondary', 'lavisto')
            )
        );

        register_block_style(
            'core/read-more',
            array(
                'name'  => 'readmore-hover-primary-color',
                'label' => __('Hover: Primary Color', 'lavisto')
            )
        );
        register_block_style(
            'core/read-more',
            array(
                'name'  => 'readmore-hover-secondary-color',
                'label' => __('Hover: Secondary Color', 'lavisto')
            )
        );
        register_block_style(
            'core/read-more',
            array(
                'name'  => 'readmore-hover-primary-fill',
                'label' => __('Hover: Primary Fill', 'lavisto')
            )
        );
        register_block_style(
            'core/read-more',
            array(
                'name'  => 'readmore-hover-secondary-fill',
                'label' => __('Hover: secondary Fill', 'lavisto')
            )
        );
        register_block_style(
            'core/read-more',
            array(
                'name'  => 'readmore-hover-black-shadow',
                'label' => __('Hover: Black Shadow', 'lavisto')
            )
        );

        register_block_style(
            'core/list',
            array(
                'name'  => 'list-style-no-bullet',
                'label' => __('List Style: Hide bullet', 'lavisto')
            )
        );
        register_block_style(
            'core/list',
            array(
                'name'  => 'hide-bullet-list-link-hover-style-primary',
                'label' => __('Hover style with primary color and hide bullet', 'lavisto')
            )
        );
        register_block_style(
            'core/list',
            array(
                'name'  => 'hide-bullet-list-link-hover-style-secondary',
                'label' => __('Hover style with secondary color and hide bullet', 'lavisto')
            )
        );

        register_block_style(
            'core/gallery',
            array(
                'name'  => 'enable-grayscale-mode-on-image',
                'label' => __('Enable Grayscale Mode on Image', 'lavisto')
            )
        );
        register_block_style(
            'core/social-links',
            array(
                'name'  => 'social-icon-border',
                'label' => __('Border Style', 'lavisto')
            )
        );
        register_block_style(
            'core/page-list',
            array(
                'name'  => 'lavisto-page-list-bullet-hide-style',
                'label' => __('Hide Bullet Style', 'lavisto')
            )
        );
        register_block_style(
            'core/categories',
            array(
                'name'  => 'lavisto-categories-bullet-hide-style',
                'label' => __('Hide Bullet Style', 'lavisto')
            )
        );
        register_block_style(
            'core/cover',
            array(
                'name'  => 'lavisto-cover-round-style',
                'label' => __('Round Corner Style', 'lavisto')
            )
        );
    }
    add_action('init', 'lavisto_register_block_styles');
}
