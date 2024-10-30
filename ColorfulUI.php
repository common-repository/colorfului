<?php
/*
Plugin Name: ColorfulUI
Plugin URI: http://kureyama-guitar.com/main/en/colorfului/
Description: you can get colorfully ui . that can generate randam color ui.  
Author: katsuaki kureyama 
Version: 0.2
Author URI: https://kureyama-guitar.com/main/en/
*/

class ColorfulUI {
     var $table_name;
    function __construct() {
  //  global $wpdb;
  //      $this->table_name = $wpdb->prefix . 'colorfull_ui_meta';
  //      register_activation_hook (__FILE__, array($this, 'colorfullui_activate'));

      add_action('admin_menu', array($this, 'add_pages'));
    }
    function add_pages() {
      add_menu_page('Colorful UI','Colorful UI',  'level_8', __FILE__, array($this,'show_option_page'), '', 26);
    }
    
 
 /*   
    function colorfullui_activate() {
    global $wpdb;
  
    $colorfullui_db_version = '1.0';
    $installed_ver = get_option( 'colorfullui_meta_version' );
    if( $installed_ver != $colorfullui_db_version ) {
        $sql = "CREATE TABLE " . $this->table_name . " (
              meta_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
              post_id bigint(20) UNSIGNED DEFAULT '0' NOT NULL,
              class_name varchar(255),
              UNIQUE KEY meta_id (meta_id)
            )
            CHARACTER SET 'utf8';";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        update_option('colorfullui_db_meta', $colorfullui_db_version);
    }
}
    */
    
function show_option_page() {
    
   
    // validate : check type
    if ( isset($_POST['colorfului'])&&is_string($_POST['colorfului']['classname'])&&is_numeric($_POST['colorfului']['transparency'])&&is_numeric($_POST['colorfului']['insertbyfooter'])) {
    
        check_admin_referer('colorfullyuis');
            
        $opt = [];
            
                $class = sanitize_text_field( $_POST['colorfului']['classname'] );       
                $class = sanitize_html_class($class);
                $opt['classname'] = $class;
    
                $transparency = sanitize_text_field( $_POST['colorfului']['transparency']);
                $opt['transparency'] = $transparency;
    
                $insertbyfooter = sanitize_text_field($_POST['colorfului']['insertbyfooter']);
                $opt['insertbyfooter'] = $insertbyfooter;
    
         update_option('colorfului_options', $opt);
        ?>
        <div class="updated fade"><p><strong><?php _e(' setting saved.'); ?></strong>
        </p></div>
         <?php }  ?>
       
    <div class="wrap">
    <div id="options-general"><br /></div><h2>ColorSetting</h2>
        <form action="" method="post">
            <?php
            wp_nonce_field('colorfullyuis');
    
            $opt = get_option('colorfului_options');
            
            $class = isset($opt['classname'])?esc_attr($opt['classname']): null;
            $transparency = isset($opt['transparency'])? esc_attr($opt['transparency']):null;
           
            $insertbyfooter = (isset($opt['insertbyfooter']))? esc_attr($opt['insertbyfooter']):1; 
            ?> 
            <table class="form-table" style="text-aligin:center">
                <tr valign="top">
                    <th scope="row"><label for="input">Select UI</label></th>
                    <td> 
                          <pre>
                 you can select html elements from class name.
                 you input text below. 

                 if you see move by category li , you can copy & paste this.
                      for example 
                             
                        menu-item
                              or 
                        cat-item  
                        
　      　this is general class name for list tag (li) of wordpress .
  　   
                menu-item is list of menu .
          　  cat-item is list of category. 
    　       
             but if not move , you should get class name form sorce code by own.
  
                    </pre>
              <br>
              <input name="colorfului[classname]" type="text" id="inputtext" value="<?php echo (!isset($class))?'menu-item':$class; ?>"  class="regular-text" />
</td>
</tr>
<tr>
<th>transparency</th>
<td>

<pre>
you can decided transparency.
</pre>

<br>
              <input name="colorfului[transparency]"  type="number" id="inputnumber"  step="0.01" max="1"  value="<?php  echo (!isset($transparency))?'1':$transparency; ?>" class="regular-text" />
              </td>
              </tr>
<tr>
<th> insert code to footer</th>
<td>
<pre>
        on is insert to all footer . 
                if you need ,
                you can insert code by  short cord .

         short code is
 <code><b>do_shortcode('[colorful_ui]');</d> </code>
</pre>
on :<INPUT type="radio" name="colorfului[insertbyfooter]" value="1" <?php echo ($insertbyfooter==1)?"checked":""; ?> >
off:<INPUT type="radio" name="colorfului[insertbyfooter]" value="2" <?php echo ($insertbyfooter==2)?"checked":""; ?> >

</td>
</tr>              
</table>
            <p class="submit"><input type="submit" name="Submit" class="button-primary" value="save" /></p>
        </form>
    <!-- /.wrap --></div>
    <div>
    if you need support,  please let me know .<br>
    <a href="https://kureyama-guitar.com/main/en/requestforworks/" target="_blank">https://kureyama-guitar.com/main/en/requestforworks/</a>
    </div>
    <?php
       }
    }
$colorfulUI = new ColorfulUI();

/* create shortcode*/
function colorful_ui_shortcode($arg) {
    $opt = get_option('colorfului_options');
    
    if(is_string($opt['classname']))
    $classname = esc_attr(esc_js($opt['classname']));
    
    if(is_numeric($opt['transparency']))
    $transparency = esc_attr(esc_js($opt['transparency']));
    
    $script = '<script>
jQuery(function(){

/*
   var liList = jQuery( ".'. $classname .'").children();
   var num =liList.length;
   for(var i=0;i <num;i++){
	  liList[i].style.backgroundColor = colorGen();
	}
*/
	
	  var liList = jQuery( ".'. $classname .'").each(function(){
	        jQuery(this).css("background-color" ,colorGen());    
	  });

	function colorGen(){ 
		var r =  Math.floor(Math.random()*255);
		var g =  Math.floor(Math.random()*255);
		var b =  Math.floor(Math.random()*255);
		var a = '.$transparency.';
               return "rgba("+r+","+g+","+b+","+a+")";
    }
});
</script>
';

return $script;
}
add_shortcode('colorful_ui', 'colorful_ui_shortcode');

  
  if(!empty(get_option('colorfului_options'))){
    
   $insertbyfooter = (is_numeric(get_option('colorfului_options')['insertbyfooter']))?get_option('colorfului_options')['insertbyfooter']:0;
   
   
   if($insertbyfooter == 1){
    

function coloruful_ui_footer_script() { ?>
        <?php  echo do_shortcode('[colorful_ui]'); ?>
        <?php }
    add_action( 'wp_footer', 'coloruful_ui_footer_script' );
    }

}


