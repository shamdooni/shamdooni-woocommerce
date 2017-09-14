<?php
// Register and load the widget
function wpb_load_widget() {
    register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );
 
// Creating the widget 
class wpb_widget extends WP_Widget {
 
    function __construct() {
        parent::__construct(
        
        // Base ID of your widget
        'wpb_widget', 
        
        // Widget name will appear in UI
        __('ویجت نشان اعتبار شمعدونی', 'wpb_widget_domain'), 
        
        // Widget description
        array( 'description' => __( 'یک ویجیت برای نمایش نشان اعتبار شمعدونی', 'wpb_widget_domain' ), ) 
        );
    }
 
    // Creating widget front-end
    
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        $options = get_option('shamduni_rounder');

        global $wc_shamdooni_address;
        
        
        $badge = wp_remote_get( $wc_shamdooni_address . '/api/rounder/v1/badge/key/'. $options['shamduni_apikey'], array( 'timeout' => 120, 'httpversion' => '1.1' ) );
        $json = json_decode($badge['body']);
        $badge_address = $json->{'badgeAddress'};
        $badge_popup_address = $wc_shamdooni_address . '/api/site/badge/' . $json->{'userId'};
        

        $badge_html = '
            <div id="shamdooni-badge" style="text-align: center; margin: 1em 0;" >
                <a>
                    <span style="
                        display: inline-block;
                        height: 100%;
                        vertical-align: middle;
                    "></span>
                    <img src=" '. $json->{'badgeAddress'} .' " style="
                        vertical-align: middle;
                    "/>
                </a>
            </div>
            <script>
                var shamdooniBadge = document.getElementById("shamdooni-badge");            
                shamdooniBadge.onclick = function() {                    
                    window.open("'. $badge_popup_address .'", "Popup","toolbar=no, location=no, statusbar=no, menubar=no, scrollbars=1, resizable=0, width=541, height=640, top=30");
                }; 
            </script> 
        ';
        
        echo $args['before_widget'];
        echo $args['after_title'];
        echo __( $badge_html, 'wpb_widget_domain' );
        echo $args['after_widget'];
    }
} 

    
?>