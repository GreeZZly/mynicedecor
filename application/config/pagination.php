<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	// $config['base_url'] = '/main/products/'.$cat_id;
	// $config['total_rows'] = count($data['count_prod']);
	$config['uri_segment'] = 4;
	// $config['per_page'] = $lim; 
	$config['full_tag_open'] = '<div class="pagi">';
	$config['full_tag_close'] = '</div>';
	$config['first_link'] = 'Первая';
	$config['last_link'] = 'Последняя';
	$config['cur_tag_open'] = '<span class="active">';
	$config['cur_tag_close'] = '</span>';
?>