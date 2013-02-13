<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
	'login/doLogin/*' => array(
		array(
			'field' => 'UserName',
			'label' => 'User Name',
			'rules' => 'trim|required|callback__valid_login'
		),
		array(
			'field' => 'Password',
			'label' => 'Password',
			'rules' => 'trim|required|callback__valid_login'
		)
	),
	'explicit/adminproducts' => array(
		array(
			'field' => 'RequestType',
			'label' => '',
			'rules' => 'trim'),
		array(
			'field' => 'ProductNumber',
			'label' => '',
			'rules' => 'trim|callback__product_exists'),
		array(
			'field' => 'ProductDescription',
			'label' => 'Description',
			'rules' => 'trim'
		),
		array(
			'field' => 'ProductLink',
			'label' => 'Link',
			'rules' => 'trim'),
		array(
			'field' => 'ProductImage',
			'label' => 'Image',
			'rules' => 'callback__is_valid_image_resource'
		)
	)
);

/* End of file form_validation.php */
/* Location: ./system/application/config/form_validation.php */