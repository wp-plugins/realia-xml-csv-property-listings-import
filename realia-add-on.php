<?php

/*
Plugin Name: WP All Import - Realia Add-On
Plugin URI: http://www.wpallimport.com/
Description: Supporting imports into the Realia theme.
Version: 1.0
Author: Soflyy
*/


include "rapid-addon.php";

$realia_addon = new RapidAddon('Realia Add-On', 'realia_addon');

$realia_addon->disable_default_images();

$realia_addon->import_images( 'property_images', 'Property Images' );

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

$realia_addon->set_import_function('realia_addon_import');

$realia_addon->admin_notice(); 

$realia_addon->run(
	array(
		"themes" => array("Realia"),
		"post_types" => array("property")
	)
);

function realia_addon_import($post_id, $data, $import_options) {

	global $realia_addon;

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

			// delete empty _property_ postmeta
			if (strpos($key,'_property_') !== false && empty($value)) {

				delete_post_meta($post_id, $key);

			} elseif (strpos($key,'_property_') !== false && $key != '_property_meta_fields') {

				$property_meta_fields[] = $key;

			}
		}

		update_post_meta($post_id, '_property_meta_fields', $property_meta_fields);	

	}

}

function property_images( $post_id, $attachment_id, $image_filepath, $import_options ) {

	$current_images = get_post_meta( $post_id, '_property_slides', true );

	$images_array = array();

	foreach ( $current_images as $image ) {
		
		$images_array[][imgurl] = $image[imgurl];

	}

	$image_url = wp_get_attachment_url( $attachment_id );

	$images_array[][imgurl] = $image_url;

    update_post_meta( $post_id, '_property_slides', $images_array );
    
}




