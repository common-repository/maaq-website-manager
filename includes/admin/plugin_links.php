<?php

function maaq__register_docs_url($links)
{
	// Register the settings link.
	$links[] = sprintf(
		'<a href="%s" aria-label="%s">%s</a>',
		esc_url(
			add_query_arg(
				['page' => 'maaq'],
				admin_url('options-general.php')
			)
		),
		esc_attr__('Go to Maaq Settings page', 'maaq-website-manager'),
		esc_html__('Settings', 'maaq-website-manager')
	);

	// Register the docs link.
	$links[] = sprintf(
		'<a href="%1$s" aria-label="%2$s" target="_blank" rel="noopener noreferrer">%3$s</a>',
		esc_url(
			add_query_arg(
				[
					'utm_content'  => 'Documentation',
					'utm_medium'   => 'all-plugins',
					'utm_source'   => 'WordPress',
				],
				'https://maaq.app/documentation//'
			)
		),
		esc_attr__('Read the documentation', 'maaq-website-manager'),
		esc_html__('Docs', 'maaq-website-manager')
	);

	return $links;
}
add_filter('plugin_action_links_' . MAAQ_PLUGIN_BASENAME, 'maaq__register_docs_url');
