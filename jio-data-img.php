<?php 
/*
    Plugin name: Asynkrone billeder
    Plugin URI: https://github.com/JeppeSigaard/jio-data-img
    Description: Indlæs billeder i din blogs indholdsfelt når de befinder sig i din viewport.
    Version: 0.1.0
    Author: Jeppe Sigaard
    Author URI: http://jeppe.io
*/

require plugin_dir_path( __FILE__ ) . 'simple_html_dom.php';
$jio_data_img = new jio_data_img();

class jio_data_img {
    
  public function __construct() {

    add_action('wp_enqueue_scripts',array( $this, 'data_img_script'), 10, 9);
    add_filter( 'the_content', array( $this, 'insert_data_img' ), 10, 9 );

  }

  public function data_img_script(){
    wp_enqueue_script("jquery");
    wp_enqueue_script('jio-data-img-script', plugin_dir_url( __FILE__ ) . 'script.js' , array('jquery'), false, true);
  }

  public function insert_data_img($content) {
    if($content !== ''){
      $blank_url = plugin_dir_url(__FILE__) . 'blank.svg';

      $html = str_get_html($content);
      foreach($html->find('img') as $img) {

        $img_src = $img->getAttribute('src');
        $img_srcset = $img->getAttribute('srcset');
        $img_sizes = $img->getAttribute('sizes');

        $img->removeAttribute('srcset');
        $img->removeAttribute('sizes');
        $img->setAttribute('src',$blank_url);
        $img->setAttribute('data-src',$img_src);
        $img->setAttribute('data-srcset',$img_srcset);
        $img->setAttribute('data-sizes',$img_sizes);
      }

      return $html;
    }
  }
}