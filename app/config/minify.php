<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// output path where the compiled files will be stored
$config['assets_dir'] = 'themes/default/';

// where to look for css files
$config['css_dir'] = 'themes/default/css';

// where to look for js files
$config['js_dir'] = 'themes/default/js';

// compression engine setting
$config['compression_engine'] = array(
    'css' => 'minify', // minify || cssmin
    'js'  => 'closurecompiler' // jsmin || closurecompiler || jsminplus
);

/* End of file config.php */
/* Location: ./application/config/minify.php */
