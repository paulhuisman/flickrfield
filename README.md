Flickr field
=============

## Description

This is an add-on for the Advanced Custom Fields WordPress plugin that adds a dropdown field to select a Flickr set from a specific Flickr user.

**Contributors**:

* Paul Huisman	([paulhuisman-online.nl](http://www.paulhuisman-online.nl))

## Notice

- This add-on needs [ACF](http://www.advancedcustomfields.com/) 


## Installation

1.Download or clone the acf-address-field repo to your plugin or theme:
* https://github.com/GCX/acf-address-field/zipball/master or
* git clone git://github.com/GCX/acf-taxonomy-field.git acf-ddress-field

2. Include the address-field.php file:

`include_once( rtrim( dirname( __FILE__ ), '/' ) . '/acf-address-field/address-field.php' );`

3. Register the field 

register_field($name, $path). Where $path is the direct path to your theme (add-on subdirectory optional)

`if(function_exists('register_field'))
{
	register_field('flickr_set', dirname(__File__) . '/your-ACF-addon-subdirectory/flickr_set.php');
}`

## Changelog

= 1.0 =
* Initial setup.

## To Do
- Also cache retrieved sets in a wp_transient.