<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
        <title>Bootstrap Buttons Builder</title>

        <link media="all" type="text/css" href="<?php echo get_admin_url(); ?>load-styles.php?c=1&dir=ltr&load=wp-admin,media" rel="stylesheet">        
        <link id="colors-css" media="all" type="text/css" href="<?php echo get_admin_url(); ?>css/colors-fresh.css" rel="stylesheet">
        <link media="all" type="text/css" href="<?php echo BOOTSTRAPBUTTONS_URL; ?>styles/core.css" rel="stylesheet">
        <link media="all" type="text/css" href="<?php echo BOOTSTRAPBUTTONS_URL; ?>styles/panel.css" rel="stylesheet">
        <script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js'></script>
        <?php
            if(file_exists(BOOTSTRAPBUTTONS_URL.'libs/minicolors.js')){
        ?>        
        <link media="all" type="text/css" href="<?php echo BOOTSTRAPBUTTONS_URL; ?>styles/minicolors.css" rel="stylesheet">
        <script type='text/javascript' src='<?php echo BOOTSTRAPBUTTONS_URL; ?>libs/js/minicolors.js'></script>
        <?php } ?>

    </head>
    <body id="calderaShortcodeBuilder">
        <div class="toolbar">
            <span id="element"><?php

                
                $Elements = get_option('bootstrapbuttons');
                foreach($Elements as $key=>$element){
                    if($element['elementType'] != 1 && $element['elementType'] != 3){
                        unset($Elements[$key]);
                    }
                }
                if(count($Elements) === 1){
                    foreach($Elements as $ID=>$Config){
                            echo '<h2 style="float:left; margin-top: 8px;">'.$Config['name'].'</h2>';
                            echo '<input type="hidden" id="selectedelement" value="'.$ID.'" />';
                    }
                    $preload = true;
                }else{
                    $Items = array();
                    foreach($Elements as $ID=>$Config){
                            $Items[$ID] = $Config['name'];
                    }

                    echo "Shortcode: <select class=\"\" id=\"selectedelement\" onchange=\"bootstrapbuttons_loadElement();\">\n";
                    echo "<option value=\"\"></option>";
                    foreach($Items as $ID=>$Element){
                        echo "<option value=\"".$ID."\">".$Element."</option>\n";
                    }
                    echo "</select>";
                }
            ?></span>
            <div class="fbutton" style="float:right; margin-right:5px;">
                <div class="button" onclick="bootstrapbuttons_sendCode();">
                    <i class="icon-plus" style="margin-top:-1px;"></i> Insert Shortcode
                </div>
            </div>
        </div>
        <div id="medialibrary">
            <input type="hidden" id="forfield" />
            <div class="panel" id="mediaPanel">

        <?php


            $query_img_args = array(
                    'post_type' => 'attachment',
                    'post_status' => 'inherit',
                    'posts_per_page' => -1,
                    );
            $query_img = new WP_Query( $query_img_args );

            $pages = ceil($query_img->post_count/12);


            $args = array(
                'post_type' => 'attachment',
                'numberposts' => 12,
                'offset' => 0,
                'post_status' => null,
                'post_parent' => null, // any parent
                );
            $attachments = get_posts($args);
            if ($attachments) {
                foreach ($attachments as $post) {
                    echo '<div>';
                    setup_postdata($post);

                    //the_title();



                    //the_attachment_link($post->ID, false);
                    echo '<div class="mediaElement">';
                    echo wp_get_attachment_link( $post->ID, array(60,60), false, true, false);
                    echo '<div class="mediaTitleTitle"><a href="'.$post->guid.'">'.$post->post_title.'</a></div>';
                    echo '</div>';

                    //the_excerpt();
                    //echo '<pre>';
                    //print_r($post);
                    //echo '</pre>';
                    echo '</div>';
                }
            }
        ?></div>
            <div class="mediatoolbar">
                <div class="fbutton">
                    <div class="button closemedia">
                        <i class="icon-remove-sign" style="margin-top:-1px;"></i> Close Media Library
                    </div>
                </div>
                <div  class="pagination">
                    <ul>
                        <?php
                        for($i = 1; $i<= $pages; $i++){
                            $class="";
                            if($i===1){
                                $class="current";
                            }
                            echo '<li><a class="'.$class.'" href="#'.$i.'">'.$i.'</a></li>';

                        }
                        ?>
                    </ul>
                </div>
            </div>


        </div>
        <div class="content" id="contentShortcodeBuilder">
        <?php

            if(isset($preload)){
                $_POST['element'] = $ID;
                echo bootstrapbuttons_load_elementConfig(true);
            }

        ?>
        </div>
        <div class="footer">

        </div>

        <script>
            <?php
                if(file_exists(BOOTSTRAPBUTTONS_URL.'libs/minicolors.js')){
            ?>            
            jQuery('.miniColors-trigger-fake').remove();
            jQuery('.minicolorPicker').miniColors();
            <?php
                }
            ?>
            jQuery('#contentShortcodeBuilder').on('click', '#ce-nav li a', function(){
                jQuery('#ce-nav li').removeClass('current');
                jQuery('.group').hide();
                jQuery(''+jQuery(this).attr('href')+'').show();
                jQuery(this).parent().addClass('current');
                return false;
            });
            jQuery('#contentShortcodeBuilder').on('click','.addRow', function(event){
                event.preventDefault();
                addGroup(jQuery(this).attr('href'), jQuery(this).attr('ref'));
            })
            function addGroup(id, EID){

                number = jQuery('.group'+id).length+1;

                var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>';
                var data = {
                        action: 'bootstrapbuttons_addgroup',
                        group: id,
                        eid: EID,
                        number: number
                };
                jQuery.post(ajaxurl, data, function(response) {
                    jQuery('#tool'+id).before(response);
                    <?php
                        if(file_exists(BOOTSTRAPBUTTONS_URL.'libs/minicolors.js')){
                    ?>                    
                    jQuery('.miniColors-trigger-fake').remove();
                    jQuery('.minicolorPicker').miniColors();
                    <?php
                        }
                    ?>
                });
            }
            function bootstrapbuttons_sendCode(){
                

                if(jQuery('#selectedelement').length > 0){
                    if(jQuery('#selectedelement').val() == ''){
                        return;
                    }
                    var shortcode = jQuery('#shortcodekey').val();
                    var output = '['+shortcode;
                    var ctype = '';
                    if(jQuery('#shortcodetype').val() == '2'){
                        var ctype = jQuery('#defaultContent').val()+'[/'+shortcode+']';
                    }
                    jQuery('#ce_container input,#ce_container select,#ce_container textarea').each(function(){
                        if(this.value){
                            // see if its a checkbox
                            var thisinput = jQuery(this);
                            var attname = this.name;
                            if(thisinput.prop('type') == 'checkbox'){
                                if(!thisinput.prop('checked')){
                                    return;
                                }
                            }
                            if(thisinput.prop('type') == 'radio'){
                                if(!thisinput.prop('checked')){
                                    return;
                                }
                            }
                            output += ' '+attname+'="'+this.value+'"';
                        }
                    });
                    var win = window.dialogArguments || opener || parent || top;
                    win.send_to_editor(output+']'+ctype);
                }

            }
            function bootstrapbuttons_loadElement(){
                    var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>';
                    var element = jQuery('#selectedelement').val();
                    var data = {
                            action: 'bootstrapbuttons_load_elementConfig',
                            element: element
                    };
                    jQuery('#contentShortcodeBuilder').html('Loading Config...');
                    jQuery.post(ajaxurl, data, function(response) {
                        jQuery('#contentShortcodeBuilder').html(response);
                        <?php
                            if(file_exists(BOOTSTRAPBUTTONS_URL.'libs/minicolors.js')){
                        ?>                        
                        jQuery('.miniColors-trigger-fake').remove();
                        jQuery('.minicolorPicker').miniColors();
                        <?php
                            }
                        ?>
                    });

            }

        jQuery('#contentShortcodeBuilder').on('click','.remover', function(){

            var id = jQuery(this).parent().parent().parent().parent().attr('ref');
            jQuery(this).parent().parent().parent().parent().remove();
            var count = 1;
            jQuery('.group'+id).each(function(){
                jQuery(this).find('[name]').each(function(){
                    var name = jQuery(this).attr('name').split('_');
                    jQuery(this).attr('name', name[0]+'_'+count);
                })
                count++;
            });

        })

        jQuery('.closemedia').click(function(){
            jQuery('#medialibrary').slideUp('50');
        });

       function caldera_openMediaPanel(id){
            jQuery('#forfield').val(id);
            jQuery('#medialibrary').slideDown('50');
       };
       jQuery('#contentShortcodeBuilder').on('click','.bootstrapbuttons_uploader', function(){
            var id = this.id.replace('uploader_','');
            caldera_openMediaPanel(id);
       })
       
        jQuery('#contentShortcodeBuilder').on('click','.mediaElement a', function(e){
            e.preventDefault();
            var link = jQuery(this).attr('href');
            var filefield = jQuery('#forfield').val();

            var target = jQuery('#'+filefield);
            target.val(link);
            jQuery('#medialibrary').slideUp();
            return false;
        })        

        jQuery('.pagination a').click(function(){
            jQuery('.pagination a').removeClass('current');
            jQuery(this).addClass('current');
            loadMediaPage(jQuery(this).attr('href').substring(1));
        });

        function loadMediaPage(page){

            var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>';
            var data = {
                    action: 'bootstrapbuttons_loadmedia_page',
                    page: page
            };
            jQuery('#mediaPanel').html('<div class="loading">Loading</div>');
            jQuery.post(ajaxurl, data, function(response) {
                jQuery('#mediaPanel').html(response);
            });
        }

        </script>
    </body>
</html>