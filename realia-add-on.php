<?php

/*
Plugin Name: WP All Import - Realia Add-On
Plugin URI: http://www.wpallimport.com/
Description: Import properties into Realia. Supports Realia theme 3.x, 4.x, and the Realia plugin.
Version: 2.0.2
Author: Soflyy
*/


include "rapid-addon.php";

if ( ! function_exists( 'is_plugin_active' ) ) {

	require_once ABSPATH . 'wp-admin/includes/plugin.php';

}

function realia_version( $len = 1 ) {

	$realia_version = wp_get_theme();

	if ( $realia_version->get( 'Name' ) == 'Realia' ) { 

		$realia_version = substr( $realia_version->get( 'Version' ), 0, $len);

	} elseif ( is_plugin_active('realia/realia.php') ) {

		$realia_version = 4;

	} else {

		$realia_version = null;

	}

	return $realia_version;

}

$realia_addon = new RapidAddon('Realia Add-On', 'realia_addon');

$realia_addon->import_images( 'property_images', 'Property Images' );

if ( realia_version() < 4 ) {

	$realia_addon->add_field('_property_price', 'Price', 'text');

	$realia_addon->add_field('_property_price_suffix', 'Price Suffix', 'text');

	$realia_addon->add_field('_property_bathrooms', 'Bathrooms', 'text');

	$realia_addon->add_field('_property_bedrooms', 'Bedrooms', 'text');

	$realia_addon->add_field('_property_area', 'Area', 'text');

	$realia_addon->add_field('_property_landlord', 'Landlord', 'text');

	$realia_addon->add_field('_property_agencies', 'Agencies', 'text', null, 'Separate multiple agencies with a comma');

	$realia_addon->add_field('_property_agents', 'Agents', 'text', null, 'Separate multiple agents with a comma');

	$realia_addon->add_field('_property_slider_image', 'Slider Image', 'image');

	$realia_addon->add_field(
		'location_settings',
		'Property Map Location',
		'radio', 
		array(
			'search_by_address' => array(
				'Search by Address',
				$realia_addon->add_options( 
					$realia_addon->add_field(
						'_property_location_search',
						'Property Address',
						'text'
					),
					'Google Geocode API Settings', 
					array(
						$realia_addon->add_field(
							'address_geocode',
							'Request Method',
							'radio',
							array(
								'address_no_key' => array(
									'No API Key',
									'Limited number of requests.'
								),
								'address_google_developers' => array(
									'Google Developers API Key - <a href="https://developers.google.com/maps/documentation/geocoding/#api_key">Get free API key</a>',
									$realia_addon->add_field(
										'address_google_developers_api_key', 
										'API Key', 
										'text'
									),
									'Up to 2,500 requests per day and 5 requests per second.'
								),
								'address_google_for_work' => array(
									'Google for Work Client ID & Digital Signature - <a href="https://developers.google.com/maps/documentation/business">Sign up for Google for Work</a>',
									$realia_addon->add_field(
										'address_google_for_work_client_id', 
										'Google for Work Client ID', 
										'text'
									), 
									$realia_addon->add_field(
										'address_google_for_work_digital_signature', 
										'Google for Work Digital Signature', 
										'text'
									),
									'Up to 100,000 requests per day and 10 requests per second'
								)
							) // end Request Method options array
						) // end Request Method nested radio field 
					) // end Google Geocode API Settings fields
				) // end Google Gecode API Settings options panel
			), // end Search by Address radio field
			'search_by_coordinates' => array(
				'Enter Coordinates',
				$realia_addon->add_field(
					'_property_latitude', 
					'Latitude', 
					'text', 
					null, 
					'Example: 34.0194543'
				),
				$realia_addon->add_field(
					'_property_longitude', 
					'Longitude', 
					'text', 
					null, 
					'Example: -118.4911912'
				) // end coordinates Option panel
			) // end Search by Coordinates radio field
		) // end Property Location radio field
	);

	$realia_addon->add_options(
		null,
		'Advanced Settings',
		array(
			$realia_addon->add_field('_property_id', 'Property ID', 'text'),

			$realia_addon->add_field('_property_title', 'Optional Title', 'text', null, 'Will be used in widgets and properties grid & rows layout'),

			$realia_addon->add_field('_property_custom_text', 'Custom Text Instead of Price', 'text'),

			$realia_addon->add_field('_property_hide_baths', 'Hide Baths', 'radio', array('' => 'No', '1' => 'Yes'), '1 for Hide, unset for Do Not Hide'),

			$realia_addon->add_field('_property_hide_beds', 'Hide Beds', 'radio', array('' => 'No', '1' => 'Yes'), '1 for Hide, unset for Do Not Hide'),

			$realia_addon->add_field('_property_featured', 'Featured', 'radio', array('' => 'No', '1' => 'Yes'), '1 for Featured, unset for Not Featured'),

			$realia_addon->add_field('_property_reduced', 'Reduced', 'radio', array('' => 'No', '1' => 'Yes'), '1 for Reduced, unset for Not Reduced'),

			$realia_addon->add_field('slide_template', 'Slide Template', 'text', null, 'The meta_id of the desired slide template')

	) );

} elseif ( realia_version() >= 4 ) {

	$realia_addon->import_images( 'property_plans', 'Floor Plans' );

	// pricing
	$realia_addon->add_title('Pricing');

	$realia_addon->add_field('property_price', 'Price', 'text', null, 'Enter amount without currency.');

	$realia_addon->add_field('property_price_prefix', 'Price Prefix', 'text', null, 'Any text shown before price (for example "from").');

	$realia_addon->add_field('property_price_suffix', 'Price Suffix', 'text', null, 'Any text shown after price (for example "per night").');

	$realia_addon->add_field('property_price_custom', 'Custom Text Instead of Price', 'text', null, 'Any text instead of price (for example "by agreement"). Prefix and Suffix will be ignored.');

	// attributes
	$realia_addon->add_title('Attributes');

	$realia_addon->add_field('property_rooms', 'Rooms', 'text');

	$realia_addon->add_field('property_beds', 'Beds', 'text');

	$realia_addon->add_field('property_baths', 'Baths', 'text');

	$realia_addon->add_field('property_garages', 'Garages', 'text');

	$realia_addon->add_field('property_home_area', 'Home Area', 'text', null, 'In unit set in settings.');

	$realia_addon->add_field('property_lot_dimensions', 'Lot Dimensions', 'text', null, 'e.g. 20x30, 20x30x40, 20x30x40x50');

	$realia_addon->add_field('property_lot_area', 'Lot Area', 'text', null, 'In unit set in settings.');


	// general options
	$realia_addon->add_title('General Options');

	$realia_addon->add_field('property_id', 'Property ID', 'text');

	$realia_addon->add_field('property_year_built', 'Year Built', 'text');

	$realia_addon->add_field('property_address', 'Address', 'text');

	$realia_addon->add_field('property_zip', 'Zip', 'text');

	$realia_addon->add_field('property_featured', 'Featured', 'radio', array('' => 'No', 'on' => 'Yes'));

	$realia_addon->add_field('property_sticky', 'Sticky', 'radio', array('' => 'No', 'on' => 'Yes'));

	$realia_addon->add_field('property_reduced', 'Reduced', 'radio', array('' => 'No', 'on' => 'Yes'));

	$realia_addon->add_field('property_sold', 'Sold', 'radio', array('' => 'No', 'on' => 'Yes'));

	$realia_addon->add_field('property_contract', 'Contract', 'radio', array(
		'' => 'None',
		'RENT' => 'Rent',
		'SALE' => 'Sale'
	));

	$realia_addon->add_field('slide_template', 'Slide Template', 'text', null, 'The meta_id of the desired slide template');

	$realia_addon->add_field('property_video', 'Video Link', 'text');

	$realia_addon->add_text('<span style="display:block;color:#aaa;font-style:italic;margin-top:-10px;margin-bottom:15px">For more information about embeding videos and video links support please read this <a href="http://codex.wordpress.org/Embeds">article</a>.</span>');

	$realia_addon->add_field('property_slider_image', 'Image for Slider', 'image', null, 'Use large images which has at least 1920px width and 1080px height.');

	$realia_addon->add_field('property_agents', 'Agents', 'text', null, 'Separate multiple agents with a comma.');

	$realia_addon->add_field(
		'location_settings',
		'Property Location',
		'radio', 
		array(
			'search_by_address' => array(
				'Search by Address',
				$realia_addon->add_options( 
					$realia_addon->add_field(
						'property_location_search',
						'Property Address',
						'text'
					),
					'Google Geocode API Settings', 
					array(
						$realia_addon->add_field(
							'address_geocode',
							'Request Method',
							'radio',
							array(
								'address_no_key' => array(
									'No API Key',
									'Limited number of requests.'
								),
								'address_google_developers' => array(
									'Google Developers API Key - <a href="https://developers.google.com/maps/documentation/geocoding/#api_key">Get free API key</a>',
									$realia_addon->add_field(
										'address_google_developers_api_key', 
										'API Key', 
										'text'
									),
									'Up to 2,500 requests per day and 5 requests per second.'
								),
								'address_google_for_work' => array(
									'Google for Work Client ID & Digital Signature - <a href="https://developers.google.com/maps/documentation/business">Sign up for Google for Work</a>',
									$realia_addon->add_field(
										'address_google_for_work_client_id', 
										'Google for Work Client ID', 
										'text'
									), 
									$realia_addon->add_field(
										'address_google_for_work_digital_signature', 
										'Google for Work Digital Signature', 
										'text'
									),
									'Up to 100,000 requests per day and 10 requests per second'
								)
							) // end Request Method options array
						) // end Request Method nested radio field 
					) // end Google Geocode API Settings fields
				) // end Google Gecode API Settings options panel
			), // end Search by Address radio field
			'search_by_coordinates' => array(
				'Enter Coordinates',
				$realia_addon->add_field(
					'property_latitude', 
					'Latitude', 
					'text', 
					null, 
					'Example: 34.0194543'
				),
				$realia_addon->add_field(
					'property_longitude', 
					'Longitude', 
					'text', 
					null, 
					'Example: -118.4911912'
				) // end coordinates Option panel
			) // end Search by Coordinates radio field
		) // end Property Location radio field
	);

	$realia_addon->add_options(
		null,
		'Valuation & Facilities',
		array(

			$realia_addon->add_field('property_valuation_keys', 'Valuation Keys', 'text', null, 'Separate multiple keys with commas.'),

			$realia_addon->add_field('property_valuation_values', 'Valuation Values', 'text', null, 'Separate multiple values with commas.'),

			$realia_addon->add_field('property_facilities_keys', 'Public Facilities Keys', 'text', null, 'Separate multiple keys with commas.'),

			$realia_addon->add_field('property_facilities_values', 'Public Facilities Values', 'text', null, 'Separate multiple values with commas.'),
	) );

}

$realia_addon->set_import_function('realia_import');

$realia_addon->admin_notice(); 

$realia_version = wp_get_theme();

if ( $realia_version->get( 'Name' ) == 'Realia' || is_plugin_active('realia/realia.php') ) { 

	$realia_addon->disable_default_images();

	$realia_addon->run(
		array(
			"post_types" => array("property")
		)	
	);

}

function realia_import($post_id, $data, $import_options) {

	global $realia_addon;

	if ( realia_version() < 4 ) {

		// all fields except for slider and image fields
		$fields = array(
			'_property_id',
			'_property_title',
			'_property_custom_text',
			'_property_price',
			'_property_price_suffix',
			'_property_bathrooms',
			'_property_hide_baths',
			'_property_bedrooms',
			'_property_hide_beds',
			'_property_area',
			'_property_featured',
			'_property_reduced'
		);
		
		// update everything in fields array
		foreach($fields as $field) {

			if ($realia_addon->can_update_meta($field, $import_options)) {

				update_post_meta($post_id, $field, $data[$field]);

			}
		}

		// update landlord, create a new one if not found
		$field = '_property_landlord';
		$post_type = 'landlord';

		if ( $realia_addon->can_update_meta( $field, $import_options ) ) {

			$post = get_page_by_title( $data[$field], 'OBJECT', $post_type );

			if ( !empty( $post ) ) {

				update_post_meta( $post_id, $field, $post->ID );

			} else {

				// insert title and attach to property
				$postarr = array(
				  'post_content'   => '',
				  'post_name'      => $data[$field],
				  'post_title'     => $data[$field],
				  'post_type'      => $post_type,
				  'post_status'    => 'publish',
				  'post_excerpt'   => ''
				);

				wp_insert_post( $postarr );

				$post = get_page_by_title( $data[$field], 'OBJECT', $post_type );

				update_post_meta( $post_id, $field, $post->ID );

			}
		}

	    // Update agent/agency.
	    // If no post matches by post_title, a new one will be created.
	    // Values stored as array of post_ids.
	    // field_name => post_type
		$fields = array(
			'_property_agencies' => 'agency',
			'_property_agents' => 'agent'
		);

		foreach ( $fields as $field => $post_type ) {

			if ( $realia_addon->can_update_meta( $field, $import_options ) ) {

				$titles = explode( ',', $data[$field] );

				$title_ids = array();

				foreach ( $titles as $title ) {

					$title = trim($title);

					$post = get_page_by_title( $title, 'OBJECT', $post_type );

					if ( !empty( $post ) ) {

						array_push($title_ids, $post->ID);

					} else {

						// insert title and attach to property
						$postarr = array(
						  'post_content'   => '',
						  'post_name'      => $title,
						  'post_title'     => $title,
						  'post_type'      => $post_type,
						  'post_status'    => 'publish',
						  'post_excerpt'   => ''
						);

						wp_insert_post( $postarr );

						$post = get_page_by_title( $title, 'OBJECT', $post_type );

						array_push($title_ids, $post->ID);

					}
				}

				update_post_meta( $post_id, $field, $title_ids );
			}
		}

	    // clear image fields to override import settings
	    $fields = array(
	    	'_property_slides',
			'_property_slider_image'
	    );

	    if ( $realia_addon->can_update_image( $import_options ) ) {

	    	foreach ($fields as $field) {

		    	delete_post_meta($post_id, $field);

		    }

	    }

		// update slider image
		$field = '_property_slider_image';

		if ($realia_addon->can_update_image($import_options) && $realia_addon->can_update_meta($field, $import_options)) {

			$image_url = wp_get_attachment_url($data[$field]['attachment_id']);

			update_post_meta($post_id, $field, $image_url);

		}

		// update slide template
		$field = 'slide_template';

		if ($realia_addon->can_update_meta($field, $import_options)) {
			
			// set $field to default if it's empty
			$data[$field] = (empty($data[$field]) ? 'default' : $data[$field]);

			update_post_meta($post_id, $field, $data[$field]);
		
		}

	    // update property location
	    $field   = '_property_location_search';

	    $address = $data[$field];

	    $lat  = $data['_property_latitude'];

	    $long = $data['_property_longitude'];
	    
	    //  build search query
	    if ( $data['location_settings'] == 'search_by_address' ) {

	    	$search = ( !empty( $address ) ? 'address=' . rawurlencode( $address ) : null );

	    } else {

	    	$search = ( !empty( $lat ) && !empty( $long ) ? 'latlng=' . rawurlencode( $lat . ',' . $long ) : null );

	    }

	    // build api key
	    if ( $data['location_settings'] == 'search_by_address' ) {
	    
	    	if ( $data['address_geocode'] == 'address_google_developers' && !empty( $data['address_google_developers_api_key'] ) ) {
	        
		        $api_key = '&key=' . $data['address_google_developers_api_key'];
		    
		    } elseif ( $data['address_geocode'] == 'address_google_for_work' && !empty( $data['address_google_for_work_client_id'] ) && !empty( $data['address_google_for_work_signature'] ) ) {
		        
		        $api_key = '&client=' . $data['address_google_for_work_client_id'] . '&signature=' . $data['address_google_for_work_signature'];

		    }

	    }

	    // if all fields are updateable and $search has a value
	    if ( $realia_addon->can_update_meta( $field, $import_options ) && $realia_addon->can_update_meta( '_property_latitude', $import_options ) && $realia_addon->can_update_meta( '_property_longitude', $import_options ) && !empty ( $search ) ) {
	        
	        // build $request_url for api call
	        $request_url = 'https://maps.googleapis.com/maps/api/geocode/json?' . $search . $api_key;

	        $curl        = curl_init();

	        curl_setopt( $curl, CURLOPT_URL, $request_url );

	        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );

	        $realia_addon->log( '- Getting location data from Geocoding API: ' . $request_url );

	        $json = curl_exec( $curl );

	        curl_close( $curl );
	        
	        // parse api response
	        if ( !empty( $json ) ) {

	            $details = json_decode( $json, true );

	            if ( $data['location_settings'] == 'search_by_address' ) {

		            $lat  = $details[results][0][geometry][location][lat];

		            $long = $details[results][0][geometry][location][lng];

		        } else {

		        	$address = $details[results][0][formatted_address];

		        }

	        }
	        
	    }
	    
	    // update location fields
	    $fields = array(
	        '_property_location_search' => $address,
	        '_property_latitude' => $lat,
	        '_property_longitude' => $long,
	    );

	    $realia_addon->log( '- Updating location data' );
	    
	    foreach ( $fields as $key => $value ) {
	        
	        if ( $realia_addon->can_update_meta( $key, $import_options ) ) {
	            
	            update_post_meta( $post_id, $key, $value );
	        
	        }
	    }

	} elseif ( realia_version() >= 4 ) {

		// all fields except for slider and image fields
		$fields = array(
			'property_price',
			'property_price_prefix',
			'property_price_suffix',
			'property_price_custom',
			'property_rooms',
			'property_beds',
			'property_baths',
			'property_garages',
			'property_home_area',
			'property_lot_dimensions',
			'property_lot_area',
			'property_id',
			'property_year_built',
			'property_address',
			'property_zip',
			'property_featured',
			'property_sticky',
			'property_reduced',
			'property_sold',
			'property_contract',
			'property_video',
		);

		// update everything in fields array
		foreach($fields as $field) {

			if ($realia_addon->can_update_meta($field, $import_options)) {

				update_post_meta($post_id, $field, $data[$field]);

			}
		}

		// update agents
		$field ='property_agents';

		$post_type = 'agent';

		if ( $realia_addon->can_update_meta( $field, $import_options ) ) {

			$titles = explode( ',', $data[$field] );

			$title_ids = array();

			foreach ( $titles as $title ) {

				$title = trim($title);

				$post = get_page_by_title( $title, 'OBJECT', $post_type );

				if ( !empty( $post ) ) {

					array_push($title_ids, $post->ID);

				} else {

					// insert title and attach to property
					$postarr = array(
					  'post_content'   => '',
					  'post_name'      => $title,
					  'post_title'     => $title,
					  'post_type'      => $post_type,
					  'post_status'    => 'publish',
					  'post_excerpt'   => ''
					);

					wp_insert_post( $postarr );

					$post = get_page_by_title( $title, 'OBJECT', $post_type );

					array_push($title_ids, $post->ID);

				}
			}

			update_post_meta( $post_id, $field, $title_ids );
		}

		// clear image fields to override import settings
		$fields = array(
			'property_gallery',
			'property_slider_image',
			'property_plans'
		);

		if ( $realia_addon->can_update_image( $import_options ) ) {

			foreach ($fields as $field) {

		    	delete_post_meta($post_id, $field);

		    }

		}

		// update slider image
		$field = 'property_slider_image';

		if ($realia_addon->can_update_image($import_options) && $realia_addon->can_update_meta($field, $import_options)) {

			$image_url = wp_get_attachment_url($data[$field]['attachment_id']);

			update_post_meta($post_id, $field, $image_url);

			update_post_meta($post_id, 'property_slider_image_id', $data[$field]['attachment_id']);

		}

		// update slide template
		$field = 'slide_template';

		if ($realia_addon->can_update_meta($field, $import_options)) {
			
			// set $field to default if it's empty
			$data[$field] = (empty($data[$field]) ? 'default' : $data[$field]);

			update_post_meta($post_id, $field, $data[$field]);
		
		}

		// update valuation and public facilities
		$fields = array(
			'property_public_facilities_group',
			'property_valuation_group'
		);

		foreach ($fields as $field) {

			if ( $realia_addon->can_update_meta( $field, $import_options ) ) {

				if ( $field == 'property_public_facilities_group' ) {

					$keys = explode(',', $data['property_facilities_keys'] );

					$values = explode(',', $data['property_facilities_values'] );

					$key_key = 'property_public_facilities_key';

					$key_value = 'property_public_facilities_value';

				} elseif ( $field == 'property_valuation_group' ) {

					$keys = explode(',', $data['property_valuation_keys'] );

					$values = explode(',', $data['property_valuation_values'] );

					$key_key = 'property_valuation_key';

					$key_value = 'property_valuation_value';

				}

				$key_names = array( $key_key, $key_value );

				$group = array();

				$i = 0;

				foreach ( $keys as $key ) {

					$group[] = array( 
						$key_key => $key,
						$key_value => $values[$i]
					);

					$i++;

				}

		        update_post_meta( $post_id, $field, $group );
			}
		}

		// update property location
		$field   = 'property_location_search';

		$address = $data[$field];

		$lat  = $data['property_map_location_latitude'];

		$long = $data['property_map_location_longitude'];

		//  build search query
		if ( $data['location_settings'] == 'search_by_address' ) {

			$search = ( !empty( $address ) ? 'address=' . rawurlencode( $address ) : null );

		} else {

			$search = ( !empty( $lat ) && !empty( $long ) ? 'latlng=' . rawurlencode( $lat . ',' . $long ) : null );

		}

		// build api key
		if ( $data['location_settings'] == 'search_by_address' ) {

			if ( $data['address_geocode'] == 'address_google_developers' && !empty( $data['address_google_developers_api_key'] ) ) {
		    
		        $api_key = '&key=' . $data['address_google_developers_api_key'];
		    
		    } elseif ( $data['address_geocode'] == 'address_google_for_work' && !empty( $data['address_google_for_work_client_id'] ) && !empty( $data['address_google_for_work_signature'] ) ) {
		        
		        $api_key = '&client=' . $data['address_google_for_work_client_id'] . '&signature=' . $data['address_google_for_work_signature'];

		    }

		}

		// if all fields are updateable and $search has a value
		if ( $realia_addon->can_update_meta( 'property_map_location', $import_options ) && $realia_addon->can_update_meta( 'property_map_location_longitude', $import_options ) && $realia_addon->can_update_meta( 'property_map_location_latitude', $import_options ) && !empty ( $search ) ) {
		    
		    // build $request_url for api call
		    $request_url = 'https://maps.googleapis.com/maps/api/geocode/json?' . $search . $api_key;

		    $curl        = curl_init();

		    curl_setopt( $curl, CURLOPT_URL, $request_url );

		    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );

		    $realia_addon->log( '- Getting location data from Geocoding API: ' . $request_url );

		    $json = curl_exec( $curl );

		    curl_close( $curl );
		    
		    // parse api response
		    if ( !empty( $json ) ) {

		        $details = json_decode( $json, true );

		        if ( $data['location_settings'] == 'search_by_address' ) {

		            $lat  = $details[results][0][geometry][location][lat];

		            $long = $details[results][0][geometry][location][lng];

		        } else {

		        	$address = $details[results][0][formatted_address];

		        }

		    }
		    
		}

		// update location fields
		$fields = array(
		    'property_map_location_latitude' => $lat,
		    'property_map_location_longitude' => $long,
		    'property_map_location' => array( 
		    	'latitude' => $lat, 
		    	'longitude' => $long 
		));

		$realia_addon->log( '- Updating location data' );

		foreach ( $fields as $key => $value ) {
		    
		    if ( $realia_addon->can_update_meta( $key, $import_options ) ) {
		        
		        update_post_meta( $post_id, $key, $value );
		    
		    }
		}
	}
}

add_action( 'pmxi_saved_post', 'update_post_meta_fields', 10, 1 );

function update_post_meta_fields( $post_id ) {
	
	$post_type = get_post_type( $post_id );

	if ( $post_type == 'property' ) {

		// build array of all set fields, whether they were imported or not
		$fields = get_post_custom($post_id);

		$property_meta_fields = array();

		foreach($fields as $key => $values) {	

			$value = $values[0];

			// delete empty property_ postmeta
			if (strpos($key,'property_') !== false && empty($value)) {

				delete_post_meta($post_id, $key);

			} elseif (strpos($key,'_property_') !== false && $key != '_property_meta_fields') {

				$property_meta_fields[] = $key;

			}
		}

		if ( realia_version() < 4 ) { 

			update_post_meta($post_id, '_property_meta_fields', $property_meta_fields);	

		}

	}

}

function property_images( $post_id, $attachment_id, $image_filepath, $import_options ) {

	if ( realia_version() < 4 ) {

		$current_images = get_post_meta( $post_id, '_property_slides', true );

		$images_array = array();

		foreach ( $current_images as $image ) {
			
			$images_array[][imgurl] = $image[imgurl];

		}

		$image_url = wp_get_attachment_url( $attachment_id );

		$images_array[][imgurl] = $image_url;

	    update_post_meta( $post_id, '_property_slides', $images_array );

	} elseif ( realia_version() >= 4 ) {

		// this is probably broken

		$current_images = get_post_meta( $post_id, 'property_gallery', true );

		$images_array = array();

		foreach ( $current_images as $id => $url ) {
			
			$images_array[$id] = $url;

		}

		$image_url = wp_get_attachment_url( $attachment_id );

		$images_array[$attachment_id] = $image_url;

	    update_post_meta( $post_id, 'property_gallery', $images_array );

	}
    
}

function property_plans( $post_id, $attachment_id, $image_filepath, $import_options ) {

	// this is probably broken

	$current_images = get_post_meta( $post_id, 'property_plans', true );

	$images_array = array();

	foreach ( $current_images as $id => $url ) {
		
		$images_array[$id] = $url;

	}

	$image_url = wp_get_attachment_url( $attachment_id );

	$images_array[$attachment_id] = $image_url;

    update_post_meta( $post_id, 'property_plans', $images_array );

}


