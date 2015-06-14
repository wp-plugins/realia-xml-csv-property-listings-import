#Realia Add-On

##Custom fields used by theme: 

* `_property_id`: If unset, will default to post_id

* `_property_title`: Optional title, used in widgets and properties grid & rows layout

* `_property_landlord`: takes the post_id of the landlord

* `_property_agencies`: takes an array of agency post_ids

* `_property_agents`: takes an array of agent post_ids

* `_property_custom_text`: used instead of price.

* `_property_price`: if this and `_property_custom_text` are set then price will be ignored in some places of the UI.

* `_property_price_suffix`: suffix added to price

* `_property_bathrooms`: number of bathrooms

* `_property_hide_baths`: 1 for hide, unset for do not hide

* `_property_bedrooms`: number of bedrooms

* `_property_hide_beds`: 1 for hide, unset for do not hide

* `_property_area`

* `_property_location_search`: If the API key is entered in WPAI will attempt to request the lat/long data from Google. If the API key field is empty this field will be deleted.

* `_property_latitude/longitude`: The data imported to these fields will be discarded unless -
	
	* _property_longitude, _property_latitude, _property_location_search are updateable
	* _property_location_search and API key are not empty

Example data: -118.48804200000001

* `_property_featured`: 1 for featured, unset for not featured

* `_property_reduced`: 1 for reduced, unset for not reduced

* `_property_slider_image`: the attachment URL of the slider image.

* `slide_template`: takes the meta_id of the desired RevSlider Slider Template Slide (say that 10 times fast). If the meta_id imported does not exist then the theme will use 'default', but the stored value will remain.

* `_property_meta_fields`: stores every set custom field as a serialized array:
	```
	Array
	(
	    [0] => _property_id
	    [1] => _property_title
	    [2] => _property_landlord
	    [3] => _property_agencies
	    [4] => _property_agents
	    [5] => _property_custom_text
	    [6] => _property_price
	    [7] => _property_price_suffix
	    [8] => _property_bathrooms
	    [9] => _property_bedrooms
	    [10] => _property_area
	    [11] => _property_latitude
	    [12] => _property_longitude
	    [13] => _property_featured
	    [14] => _property_reduced
	)
	```


