<?php

/*
 * Caldera - Exported Functions
 * (C) 2012 - David Cramer
 */

$bootstrapbuttonsfooterOutput = '';
$bootstrapbuttonsheaderscripts = '';
$bootstrapbuttonsjavascript = array();
$bootstrapbuttonsphpincludest = array();
$bootstrapbuttonscontentPrefilters = array();
$bootstrapbuttonsheaderContent = "";
$bootstrapbuttonsfooterContent = "";
$bootstrapbuttonsregisteredElements = array();
$bootstrapbuttonscssincludes = array();
$bootstrapbuttonsjsincludes = array();
$bootstrapbuttonselementinstances = array();


//custom php lib
function select_icon($a){
	$return = '<style type="text/css">.icn-selector {display: inline-block !important;height: 19px;text-align: left;width: 20px;}.icn-selector input {margin-right: 5px !important;display:none;}.icn-selector.selected {background-color: #FFB0A6;border-radius: 3px 3px 3px 3px;}	</style>';			
	$iconslist = array("icon-glass","icon-music","icon-search","icon-envelope","icon-heart","icon-star","icon-star-empty","icon-user","icon-film","icon-th-large","icon-th","icon-th-list","icon-ok","icon-remove","icon-zoom-in","icon-zoom-out","icon-off","icon-signal","icon-cog","icon-trash","icon-home","icon-file","icon-time","icon-road","icon-download-alt","icon-download","icon-upload","icon-inbox","icon-play-circle","icon-repeat","icon-refresh","icon-list-alt","icon-lock","icon-flag","icon-headphones","icon-volume-off","icon-volume-down","icon-volume-up","icon-qrcode","icon-barcode","icon-tag","icon-tags","icon-book","icon-bookmark","icon-print","icon-camera","icon-font","icon-bold","icon-italic","icon-text-height","icon-text-width","icon-align-left","icon-align-center","icon-align-right","icon-align-justify","icon-list","icon-indent-left","icon-indent-right","icon-facetime-video","icon-picture","icon-pencil","icon-map-marker","icon-adjust","icon-tint","icon-edit","icon-share","icon-check","icon-move","icon-step-backward","icon-fast-backward","icon-backward","icon-play","icon-pause","icon-stop","icon-forward","icon-fast-forward","icon-step-forward","icon-eject","icon-chevron-left","icon-chevron-right","icon-plus-sign","icon-minus-sign","icon-remove-sign","icon-ok-sign","icon-question-sign","icon-info-sign","icon-screenshot","icon-remove-circle","icon-ok-circle","icon-ban-circle","icon-arrow-left","icon-arrow-right","icon-arrow-up","icon-arrow-down","icon-share-alt","icon-resize-full","icon-resize-small","icon-plus","icon-minus","icon-asterisk","icon-exclamation-sign","icon-gift","icon-leaf","icon-fire","icon-eye-open","icon-eye-close","icon-warning-sign","icon-plane","icon-calendar","icon-random","icon-comment","icon-magnet","icon-chevron-up","icon-chevron-down","icon-retweet","icon-shopping-cart","icon-folder-close","icon-folder-open","icon-resize-vertical","icon-resize-horizontal","icon-hdd","icon-bullhorn","icon-bell","icon-certificate","icon-thumbs-up","icon-thumbs-down","icon-hand-right","icon-hand-left","icon-hand-up","icon-hand-down","icon-circle-arrow-right","icon-circle-arrow-left","icon-circle-arrow-up","icon-circle-arrow-down","icon-globe","icon-wrench","icon-tasks","icon-filter","icon-briefcase","icon-fullscreen");	

	$return .= '<div style="margin-bottom:10px;"><label class="cbbtn icnset'.$a['id'].'" onclick="jQuery(\'.icnset'.$a['id'].'\').removeClass(\'selected\');jQuery(this).addClass(\'selected\');"><input id="icn_'.$a['id'].'" type="radio" value="" name="'.$a['name'].'" ';
	if(empty($a['default'])){
		$return .= ' checked="checked"';
	}
	$return .= '>&nbsp;No Icon</label></div>';
	foreach($iconslist as &$icon){
		$sel = '';
		$clss = '';
		if($a['default'] == $icon){
			$sel = 'checked="checked"';
			$clss = ' selected';
		}
		$return .= '<label class="cbbtn icnset'.$a['id'].' icn-selector'.$clss.'" onclick="jQuery(\'.icnset'.$a['id'].'\').removeClass(\'selected\');jQuery(this).addClass(\'selected\');"><input type="radio" value="'.$icon.'" name="'.$a['name'].'" '.$sel.'>&nbsp;<i class="'.$icon.'"></i></label>';
	}
return $return;
}



function bootstrapbuttons_setup($deact=false){

    if(file_exists(BOOTSTRAPBUTTONS_PATH.'libs/define.php')){
        $define = unserialize(file_get_contents(BOOTSTRAPBUTTONS_PATH.'libs/define.php'));
    }
    if(file_exists(BOOTSTRAPBUTTONS_PATH.'libs/configs.php')){
        $configs = unserialize(file_get_contents(BOOTSTRAPBUTTONS_PATH.'libs/configs.php'));
    }
    if(!empty($define)){
		   	if(true === $deact){
				delete_option('bootstrapbuttons');
		   	}else{
        		update_option('bootstrapbuttons', $define);
			}
    }
    if(!empty($configs)){
        foreach($configs as $ID=>$element){
				if(true === $deact){
					delete_option($ID);
        		}else{
         		update_option($ID, $element);
				}
			}
    }
return true;
}

function bootstrapbuttons_exit(){
		bootstrapbuttons_setup(true);
    return true;
}
function bootstrapbuttons_start() {
    if(is_admin ()){
     if(!empty ($_GET['bootstrapbuttons'])){
         if($_GET['bootstrapbuttons'] == 'insert'){
             include(BOOTSTRAPBUTTONS_PATH.'/shortcode.php');
             die;
         }
     }


        return;
    }
    bootstrapbuttons_elementDetect();
}

function bootstrapbuttons_getUsedShortcodes($content, $return = array(), $internal = true, $preview = false){

    //$regex = get_shortcode_regex();
    $elements = get_option('bootstrapbuttons');
    foreach($elements as $key=>$el){
        if($el['elementType'] != 1 && $el['elementType'] != 3 && $preview == false){
            unset($elements[$key]);
        }
    }
    $shortcodes = array();
    if(!empty($internal)){
        $shortcodeKey = array();
    }
    foreach($elements as $ID=>$Element){
        if(!empty($Element['shortcode'])){
            $shortcodes[] = $Element['shortcode'];
            if(!empty($internal)){
                $shortcodeKey[$Element['shortcode']] = $ID;
            }
            for($i = 1; $i<=20; $i++){
                $shortcodes[] = $Element['shortcode'].'_'.$i;
                if(!empty($internal)){
                    $shortcodeKey[$Element['shortcode'].'_'.$i] = $ID;
                }
            }
        }else{
            $shortcodes[] = $ID;
            if(!empty($internal)){
                $shortcodeKey[$ID] = $ID;
            }
        }
    }
    $tagregexp = join( '|', array_map('preg_quote', $shortcodes) );
    $regex =
              '\\['                              // Opening bracket
            . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
            . "($tagregexp)"                     // 2: Shortcode name
            . '\\b'                              // Word boundary
            . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
            .     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
            .     '(?:'
            .         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
            .         '[^\\]\\/]*'               // Not a closing bracket or forward slash
            .     ')*?'
            . ')'
            . '(?:'
            .     '(\\/)'                        // 4: Self closing tag ...
            .     '\\]'                          // ... and closing bracket
            . '|'
            .     '\\]'                          // Closing bracket
            .     '(?:'
            .         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
            .             '[^\\[]*+'             // Not an opening bracket
            .             '(?:'
            .                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
            .                 '[^\\[]*+'         // Not an opening bracket
            .             ')*+'
            .         ')'
            .         '\\[\\/\\2\\]'             // Closing shortcode tag
            .     ')?'
            . ')'
            . '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]


    //vardump($regex);
    preg_match_all('/' . $regex . '/s', $content, $found);
    if(!empty($internal)){        
        if(!empty($found[2][0])){
            foreach($found[2] as $elkey=>$el){                
                $found[7][$elkey] = $shortcodeKey[$found[2][$elkey]];
            }
        }
    }
    foreach($found[5] as $innerContent){
        if(!empty($innerContent)){
           $new = bootstrapbuttons_getUsedShortcodes($innerContent, $found, $internal);
            if(!empty($new)){
                foreach($new as $key=>$val){
                    $found[$key] = array_merge($found[$key], $val);
                }
            }
        }
    }
    
    if(!empty($found[7])){
        foreach($found[7] as $ID){
            $ElementCFG = get_option($ID);
            if(!empty($ElementCFG['_mainCode'])){
               $new = bootstrapbuttons_getUsedShortcodes($ElementCFG['_mainCode'], $found, $internal);
                if(!empty($new)){
                    foreach($new as $key=>$val){
                        $found[$key] = array_merge($found[$key], $val);
                    }
                }
            }
        }
    }

    return $found;
}
function bootstrapbuttons_elementDetect($template = false){
    
    global $bootstrapbuttonscontentPrefilters, $bootstrapbuttonsfooterOutput, $bootstrapbuttonsheaderscripts, $bootstrapbuttonsphpincludest, $bootstrapbuttonsheaderContent, $bootstrapbuttonsfooterContent;

    $elements = get_option('bootstrapbuttons');
    if(empty($elements)){
        return $template;
    }
    //return;
    $postID = url_to_postid($_SERVER['REQUEST_URI']);
    if(empty($postID)){
        $frontPage = get_option('page_on_front');
        if(!empty($frontPage)){
            $frontPage = get_option('page_on_front');
            $posts[] = get_post($frontPage);
        }else{
            $args = array(
                'numberposts' => get_option('posts_per_page')
            );
            $posts = get_posts($args);
        }
    }else{        
        $posts[] = get_post($postID);
    }
    
    
    foreach ($elements as $ID => $options) {
        if ($options['elementType'] == 4) {
            $options = $elements[$ID];
            if (!empty($options['variables'])) {
                $setAtts = get_option($ID . '_cfg');
                if (empty($setAtts)) {
                    $setAtts = array();
                }
                $atts = array();
                foreach ($options['variables']['names'] as $varkey => $variable) {
                    if ($options['variables']['type'][$varkey] == 'Dropdown') {
                        $options['variables']['defaults'][$varkey] = trim(strtok($options['variables']['defaults'][$varkey], ','));
                    }
                    if (!empty($options['variables']['multiple'][$varkey])) {
                        $endLoop = true;
                        $loopIndex = 1;
                        while ($endLoop) {
                            if (isset($setAtts[$variable . '_' . $loopIndex])) {
                                $atts[$variable . '_' . $loopIndex] = $setAtts[$variable . '_' . $loopIndex];
                                $loopIndex++;
                            } else {
                                if ($loopIndex === 1) {
                                    $atts[$variable . '_' . $loopIndex] = $options['variables']['defaults'][$varkey];
                                }
                                $endLoop = false;
                            }
                        }
                    } else {
                        $atts[$variable] = $options['variables']['defaults'][$varkey];
                    }
                }
            }
            if (!empty($setAtts)) {
                $shortcodes[$ID][] = shortcode_atts($atts, $setAtts);
            } else {
                $shortcodes[$ID][] = false;
            }
        }
    }
    
    //scan the posts
    if(!empty($posts)) {
        foreach($posts as $postKey=>$post){
            $content = $post->post_content;
            $used = bootstrapbuttons_getUsedShortcodes($content);
            
            foreach ($elements as $element => $options){
                if(!empty($options['shortcode'])){
                    if ($keys = array_keys($used[2], $options['shortcode'])) {                        
                        foreach($keys as $key){
                            if(!empty($used[3][$key])){
                                $setAtts = shortcode_parse_atts($used[3][$key]);
                            }else{
                                $setAtts = array();
                            }
                            if(!empty($options['variables'])){
                                $atts = array();
                                foreach($options['variables']['names'] as $varkey=>$variable){
                                    if($options['variables']['type'][$varkey] == 'Dropdown'){
                                        $options['variables']['defaults'][$varkey] = trim(strtok($options['variables']['defaults'][$varkey], ','));
                                    }
                                    if(!empty($options['variables']['multiple'][$varkey])){
                                        $endLoop = true;
                                        $loopIndex = 1;
                                        while($endLoop){
                                            if(isset($setAtts[$variable.'_'.$loopIndex])){
                                                $atts[$variable.'_'.$loopIndex] = $setAtts[$variable.'_'.$loopIndex];
                                                $loopIndex++;
                                            }else{
                                                if($loopIndex === 1){
                                                    $atts[$variable.'_'.$loopIndex] = $options['variables']['defaults'][$varkey];
                                                }
                                                $endLoop = false;
                                            }
                                        }
                                    }else{
                                        $atts[$variable] = $options['variables']['defaults'][$varkey];
                                    }
                                }
                            }
                            if(!empty($options['removelinebreaks'])){
                                $preContent = $used[5][$key];
                                for($i=count($used[0])-1; $i>$key; $i--){
                                    $preContent = str_replace($used[0][$i], '[*'.$i.'*]', $preContent);
                                }
                                $preContent = str_replace(array("\r\n", "\r"), "", $preContent);
                                for($i=count($used[0])-1; $i>$key; $i--){
                                    $preContent = str_replace('[*'.$i.'*]', $used[0][$i], $preContent);
                                }
                                $post->post_content = trim(str_replace($used[5][$key], $preContent, $post->post_content));
                                $bootstrapbuttonscontentPrefilters[$post->ID] = $post->post_content;
                            }
                            if(!empty($setAtts)){
                                $shortcodes[$element][] = shortcode_atts($atts, $setAtts);
                            }else{
                                $shortcodes[$element][] = false;
                            }
                        }
                    }
                }
            }
        }
    }

    $sidebars = get_option('sidebars_widgets');
    unset($sidebars['wp_inactive_widgets']);
    $widgets = array();    
    foreach($sidebars as $sidebar=>$set){        
        if(is_active_sidebar($sidebar)){            
            foreach($set as $widget){
                foreach($elements as $element=>$options){                    
                    if(substr($widget,0,strlen($options['shortcode'])+1) == $options['shortcode'].'-'){
                        $widgets[$widget] = $element;
                    }
                }
            }
        }
    }    
    foreach($widgets as $key=>$element){
        $options = $elements[$element];
        $config = get_option('widget_'.$options['shortcode']);
        $cfgparts = explode('-', $key);
        $setAtts = $config[$cfgparts[1]];
        if(!empty($options['variables'])){
            $atts = array();
            foreach($options['variables']['names'] as $varkey=>$variable){
                if($options['variables']['type'][$varkey] == 'Dropdown'){
                    $options['variables']['defaults'][$varkey] = trim(strtok($options['variables']['defaults'][$varkey], ','));
                }
                if(!empty($options['variables']['multiple'][$varkey])){
                    $endLoop = true;
                    $loopIndex = 1;
                    while($endLoop){
                        if(isset($setAtts[$variable.'_'.$loopIndex])){
                            $atts[$variable.'_'.$loopIndex] = $setAtts[$variable.'_'.$loopIndex];
                            $loopIndex++;
                        }else{
                            if($loopIndex === 1){
                                $atts[$variable.'_'.$loopIndex] = $options['variables']['defaults'][$varkey];
                            }
                            $endLoop = false;
                        }
                    }
                }else{
                    $atts[$variable] = $options['variables']['defaults'][$varkey];
                }

            }
        }
        if(!empty($setAtts)){
            if(empty($atts)){
                $shortcodes[$element][] = $setAtts;
            }else{
                $shortcodes[$element][] = shortcode_atts($atts, $setAtts);
            }
        }else{
            $shortcodes[$element][] = false;
        }

    }
    
    if(empty($shortcodes)){
        return;
    }
    
    foreach ($shortcodes as $ID=>$Instances) {
        foreach($Instances as $no=>$atts){
            $atts = bootstrapbuttons_getDefaultAtts($ID, $atts);
            bootstrapbuttons_processHeaders($ID, $atts['atts']);
        }
    }
    
return;
}
function bootstrapbuttons_checkInstanceID($id, $process){
    global $bootstrapbuttonselementinstances;
    $bootstrapbuttonselementinstances[$id][$process][] = true;
    $count = count($bootstrapbuttonselementinstances[$id][$process]);
    if($count > 1){
        return $id.($count-1);
    }
    return $id;
}  
function bootstrapbuttons_processHeaders($ID, $atts){
    global $bootstrapbuttonsheaderscripts, $wp_scripts, $bootstrapbuttonscssincludes, $bootstrapbuttonselementinstances, $bootstrapbuttonsfooterContent, $bootstrapbuttonsheaderContent, $bootstrapbuttonscontentPrefilters;
        
    $syslibs = array('scriptaculous-root','scriptaculous-builder','scriptaculous-dragdrop','scriptaculous-effects','scriptaculous-slider','scriptaculous-sound','scriptaculous-controls','scriptaculous','swfobject','swfupload','swfupload-degrade','swfupload-queue','swfupload-handlers','jquery','jquery-form','jquery-color','jquery-ui-core','jquery-ui-widget','jquery-ui-mouse','jquery-ui-accordion','jquery-ui-autocomplete','jquery-ui-slider','jquery-ui-tabs','jquery-ui-sortable','jquery-ui-draggable','jquery-ui-droppable','jquery-ui-selectable','jquery-ui-datepicker','jquery-ui-resizable','jquery-ui-dialog','jquery-ui-button','schedule','suggest','thickbox','jquery-hotkeys','sack','quicktags','farbtastic','colorpicker','tiny_mce','prototype','autosave','common','editor','editor-functions','ajaxcat','password-strength-meter','xfn','upload','postbox','slug','post','page','link','comment','comment-repy','media-upload','word-count','theme-preview');
    
    $content = '';
    if(isset($atts['_content'])){
        $content = $atts['_content'];
        unset($atts['_content']);
    }
    $element = get_option($ID);
    if(!empty($element)){
        
        $instanceID = bootstrapbuttons_checkInstanceID('bootstrapbuttons'.$element['_shortcode'], 'header');

        $element['_cssCode'] = str_replace('{{_id_}}',$instanceID, $element['_cssCode']);
        if(!empty($element['_javascriptCode'])){
            $element['_javascriptCode'] = str_replace('{{_id_}}',$instanceID, $element['_javascriptCode']);
        }

        if($element['_elementType'] == 4 && $element['_alwaysLoadPlacement'] > 1){

            if(!empty($element['_mainCode'])){
                $element['_mainCode'] = str_replace('{{content}}', $content, $element['_mainCode']);
                $element['_mainCode'] = str_replace('{{_id_}}',$instanceID, $element['_mainCode']);
            }

        }

        if (!empty($element['_jsLib'])) {
            foreach ($element['_jsLib'] as $handle => $src) {
                $in_footer = false;
                if ($element['_jsLibLoc'][$handle] == 2) {
                    $in_footer = true;
                }
                if(!empty($element['_assetLabel'])){
                    foreach($element['_assetLabel'] as $assetKey=>$AssetLabel){
                        if($src == $AssetLabel){
                            $src = BOOTSTRAPBUTTONS_URL.$element['_assetURL'][$assetKey];
                        }else{
                            $src = str_replace('{{'.$AssetLabel.'}}', BOOTSTRAPBUTTONS_URL.$element['_assetURL'][$assetKey], $src);
                        }
                    }
                }
                if(in_array(strtolower(str_replace(' ','-',$src)), $syslibs)){
                    wp_enqueue_script(strtolower(str_replace(' ','-',$src)));
                }else{
                    wp_enqueue_script($handle, $src, false, false, $in_footer);
                }
            }
        }
        if (!empty($element['_cssLib'])) {
            foreach ($element['_cssLib'] as $handle => $src) {
                if(!empty($element['_assetLabel'])){
                    foreach($element['_assetLabel'] as $assetKey=>$AssetLabel){
                        if($src == $AssetLabel){
                            $src = BOOTSTRAPBUTTONS_URL.$element['_assetURL'][$assetKey];
                        }else{
                            $src = str_replace('{{'.$AssetLabel.'}}', BOOTSTRAPBUTTONS_URL.$element['_assetURL'][$assetKey], $src);
                        }
                    }
                }
                wp_enqueue_style($handle, $src);
            }
        }

        if (!empty($element['_cssCode'])) {
            if (!empty($element['_variable'])) {
                foreach ($element['_variable'] as $VarKey => $Variable) {
                    if(isset($atts[$Variable])){
                        $VarVal = $atts[$Variable];
                    }
                    if (!empty($atts[$Variable . '_1'])) {
                        $startcounter = true;
                        $index = 1;
                        while ($startcounter == true) {
                            if (!empty($atts[$Variable . '_' . $index])) {
                                $varArray[trim($Variable)][] = $atts[$Variable . '_' . $index];
                            } else {
                                $startcounter = false;
                            }
                            $index++;
                        }
                    }
                    if(isset($VarVal)){
                         $element['_cssCode'] = str_replace('{{' . $Variable . '}}', $VarVal, $element['_cssCode']);
                    }
                }
            }
            
            
            if(!empty($element['_assetLabel'])){
                foreach($element['_assetLabel'] as $assetKey=>$AssetLabel){
                    $element['_cssCode'] = str_replace('{{'.$AssetLabel.'}}', BOOTSTRAPBUTTONS_URL.$element['_assetURL'][$assetKey], $element['_cssCode']);
                }
            }

            
            $pattern = '\[(\[?)(once)\b([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
            preg_match_all('/' . $pattern . '/s', $element['_cssCode'], $once);            
            if (!empty($once)) {
                foreach ($once[0] as $onceKey => $onceCode) {
                    if (!empty($once[5][$onceKey])) {
                        $codeSignature = md5(trim($once[5][$onceKey]));
                        if(empty($bootstrapbuttonscssincludes[$codeSignature])){
                            $element['_cssCode'] = str_replace($once[0][$onceKey], trim($once[5][$onceKey]), $element['_cssCode']);
                            $bootstrapbuttonscssincludes[$codeSignature] = true;
                        }else{
                            $element['_cssCode'] = str_replace($once[0][$onceKey], '', $element['_cssCode']);
                        }
                    }
                }
            }
            
            
            
            ob_start();
                eval(' ?>' . $element['_cssCode'] . ' <?php ');
            $Css = ob_get_clean();
            $bootstrapbuttonsheaderscripts .= "\n".$Css;
        }

        if($element['_elementType'] == 4){
            $inContent = false;
            if(!empty($atts['_content']) && $element['_shortcodeType'] == 2){
                $inContent = $atts['_content'];
            }
            if($element['_alwaysLoadPlacement'] > 1){
                $output = bootstrapbuttons_doShortcode($atts, $inContent, $element['_shortcode']);
                if(!empty($element['_mainCode'])){
                    if($element['_alwaysLoadPlacement'] == 2){
                        $bootstrapbuttonsheaderContent .= $output;
                    }elseif($element['_alwaysLoadPlacement'] == 5){
                        $bootstrapbuttonsfooterContent .= $output;
                    }elseif($element['_alwaysLoadPlacement'] == 3 || $element['_alwaysLoadPlacement'] == 4){
                        $bootstrapbuttonscontentPrefilters[$ID] = $output;
                    }
                }
            }
        };
    }

}
function bootstrapbuttons_contentPrefilter($template){
    global $wp_query,$bootstrapbuttonscontentPrefilters, $post, $bootstrapbuttonsfooterOutput, $bootstrapbuttonsheaderscripts, $bootstrapbuttonsphpincludest;
    $elements = get_option('bootstrapbuttons'); 
    foreach($elements as $id=>$cfg){
        if($cfg['elementType'] == 4){
            if(!empty($bootstrapbuttonscontentPrefilters[$id])){
                $element = get_option($id);
                if($element['_alwaysLoadPlacement'] == 3){                        
                    $post->post_content = $bootstrapbuttonscontentPrefilters[$id].$post->post_content;
                }elseif($element['_alwaysLoadPlacement'] == 4){
                    $post->post_content .= $bootstrapbuttonscontentPrefilters[$id];
                }
            }
        }
    }

    // Custom do shortcode - disabled for now :(
    return $template;
    $Elements = get_option('bootstrapbuttons');    
    if(empty($Elements)){
        return $template;
    }
    foreach($wp_query->posts as $postKey=>$post){
        $Used = bootstrapbuttons_getUsedShortcodes($post->post_content, array(), true);        
        foreach($Used[2] as $key=>$shortcode){
            $options = $Elements[$Used[7][$key]];            
            if(!empty($Used[3][$key])){
                $setAtts = shortcode_parse_atts($Used[3][$key]);
            }else{
                $setAtts = array();
            }
            
            if(!empty($options['variables'])){
                $atts = array();
                foreach($options['variables']['names'] as $varkey=>$variable){
                    if($options['variables']['type'][$varkey] == 'Dropdown'){
                        $options['variables']['defaults'][$varkey] = trim(strtok($options['variables']['defaults'][$varkey], ','));
                    }
                    if(!empty($options['variables']['multiple'][$varkey])){
                        $endLoop = true;
                        $loopIndex = 1;
                        while($endLoop){
                            if(isset($setAtts[$variable.'_'.$loopIndex])){
                                $atts[$variable.'_'.$loopIndex] = $setAtts[$variable.'_'.$loopIndex];
                                $loopIndex++;
                            }else{
                                if($loopIndex === 1){
                                    $atts[$variable.'_'.$loopIndex] = $options['variables']['defaults'][$varkey];
                                }
                                $endLoop = false;
                            }
                        }
                    }else{
                        $atts[$variable] = $options['variables']['defaults'][$varkey];
                    }
                }
            }
            if(!empty($setAtts)){
                $Atts = shortcode_atts($atts, $setAtts);
            }else{
                $Atts = false;
            }
            //need to replace the shortcode found with the new code below!!
            $newContent = trim(bootstrapbuttons_doShortcode($Atts, $Used[5][$key], $options['shortcode']));

            $post->post_content = str_replace($Used[0][$key], $newContent, $post->post_content);
            //vardump($Used[5][$key], false);
            //vardump(bootstrapbuttons_doShortcode($Atts, $post->post_content, $options['shortcode']));
        }

    }
    //die;
    return $template;
}

function bootstrapbuttons_shortcode_ajax(){

    if(empty($_POST['process']) && empty($_POST['shortcode'])){
        return false;
    }

    $elements = get_option('bootstrapbuttons');
    foreach($elements as $ID=>$element){
        if($element['shortcode'] == $_POST['shortcode']){
            break;
        }
    }
    $Config = get_option($ID);
    //if(!empty($Config['_phpCode'])){
    $func = 'ajax_'.$_POST['process'];
    $func();
    //}


die;
}
function bootstrapbuttons_button() {

    echo "<a onclick=\"return false;\" id=\"bootstrapbuttons_inserter\" title=\"Bootstrap Buttons Shortcode Builder\" class=\"thickbox button\" href=\"?bootstrapbuttons=insert&TB_iframe=1&width=640&height=307\">\n";
    echo "<span class=\"wp-media-buttons-icon\" style=\"background: url('".BOOTSTRAPBUTTONS_URL."styles/images/icon.png') no-repeat scroll left top; padding: 0px 2px;margin:-1px 0px 0 -6px;\"></span>Bootstrap Buttons";
    echo "</a>\n";
}

function bootstrapbuttons_getheader() {
    global $bootstrapbuttonsheaderContent;
    echo $bootstrapbuttonsheaderContent;
}
function bootstrapbuttons_getfooter() {
    global $bootstrapbuttonsfooterContent;
    echo $bootstrapbuttonsfooterContent;
}
function bootstrapbuttons_header() {
    global $bootstrapbuttonsheaderscripts;

    if(!empty($bootstrapbuttonsheaderscripts)){
    echo "<style type=\"text/css\">\n";
        echo str_replace('}',"}\n",str_replace('; ',';',str_replace(' }',"}",str_replace('{ ','{',str_replace(array("\r\n","\r","\n","\t",'  ','    ','    '),"",preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!','',$bootstrapbuttonsheaderscripts))))));
    echo "</style>\n";
        $bootstrapbuttonsheaderscripts = '';
    }
}
function bootstrapbuttons_footer() {
    global $bootstrapbuttonsfooterOutput;
    if(!empty($bootstrapbuttonsfooterOutput)){
        echo "<script>\n";

        echo $bootstrapbuttonsfooterOutput;

        echo "</script>\n";
        $bootstrapbuttonsfooterOutput = '';
    }
}
function bootstrapbuttons_widgetscripts(){
    if(function_exists('wp_enqueue_media')){
        wp_enqueue_media();
	   }
    wp_enqueue_script('media-upload');
    wp_enqueue_style('thickbox');
    wp_enqueue_script('thickbox');
	   if(file_exists(BOOTSTRAPBUTTONS_URL.'libs/minicolors.js')){
    		wp_enqueue_script('minicolors',BOOTSTRAPBUTTONS_URL.'libs/minicolors.js');
    }
}
function bootstrapbuttons_widgetcss(){
    wp_enqueue_style('caldera-widgetcoreCSS', BOOTSTRAPBUTTONS_URL.'styles/core.css');
    wp_enqueue_style('caldera-widgetpanelCSS', BOOTSTRAPBUTTONS_URL.'styles/panel.css');
	   if(file_exists(BOOTSTRAPBUTTONS_URL.'libs/minicolors.css')){
    		wp_enqueue_style('caldera-minicolors', BOOTSTRAPBUTTONS_URL.'styles/minicolors.css');
    }
}
function bootstrapbuttons_widgetjs(){
?>
<script>

        jQuery('#widgets-right .bootstrapbuttons_panel').on('hover', '.minicolorPicker,.miniColors-trigger-fake',function(){
            jQuery('.miniColors-trigger-fake').remove();
            jQuery('.minicolorPicker').miniColors();
            
        });

        if((typeof wp !== 'undefined')){
            var _custom_media = true,
            _orig_send_attachment = wp.media.editor.send.attachment;

            jQuery('#widgets-right .bootstrapbuttons_panel').on('click','.bootstrapbuttons_uploader', function() {

                //openNewImageDialog('Select File', alert);
                var button = jQuery(this);
                var frame = wp.media({
                    title : 'Select File',
                    multiple : false,
                    button : { text : 'Use File' }
                });
                // Runs on select
                frame.on('select',function(){
                    var objSettings = frame.state().get('selection').first().toJSON();
                    button.prev().val(objSettings.url);
                });

                // Open ML
                frame.open();
            });
        }else{
            jQuery('#widgets-right .bootstrapbuttons_panel').on('click','.bootstrapbuttons_uploader', function() {

                formfield = jQuery(this).parent().find('input');
                tb_show('Select or Upload a File', 'media-upload.php?type=file&amp;post_id=0&amp;TB_iframe=true');

                window.send_to_editor = function(html) {
                linkurl = jQuery(html).attr('href');
                imgsrc = jQuery(html).find('img').attr('src');
                if(imgsrc){
                    jQuery(formfield).val(imgsrc);
                }else{
                 jQuery(formfield).val(linkurl);
                }
                tb_remove();
                }

                return false;
            });
        }
    jQuery('#widgets-right .bootstrapbuttons_panel').on('click','.removeRow', function(event){
        jQuery(this).parent().parent().remove();
    });
    jQuery('#widgets-right .bootstrapbuttons_panel').on('click','.addRow', function(event){

        event.preventDefault();
        jQuery(this).before('<input type="hidden" value="'+jQuery(this).attr('ref')+'" name="'+jQuery(this).parent().attr('ref')+'" />');

    })

    function addGroup(id, prefix){
        number = jQuery('.group'+id).length+1;
        //alert(jQuery('.group'+id).length);
        var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>';
        var data = {
                action: 'fileatt_addgroup',
                group: id,
                number: number,
                nameprefix: prefix
        };
        jQuery('#mediaPanel').html('<div class="loading">Loading</div>');
        jQuery.post(ajaxurl, data, function(response) {
            jQuery('#tool'+id).before(response);
        });
    }


</script>
<?php
}

function bootstrapbuttons_register_element($name, $atts){
    global $bootstrapbuttonsregisteredElements;
    if(!isset($atts['_slug'])){
        return;
    }else{
        global $bootstrapbuttonsregisteredElements;
        $Elements = get_option('bootstrapbuttons');
        foreach($Elements as $Element=>$Opt){
            if($Opt['shortcode'] == $atts['_slug']){
                $shortcode = $Opt['shortcode'];
                break;
            }
        }
        if(empty($shortcode)){
            return;
        }
        $slug = $shortcode;
        $ID = $Element;
        $content = false;
        if(!empty($atts['_content'])){
            $content = $slugs[$atts['_content']];
            unset($atts['_content']);
        }
        unset($atts['_slug']);
        $atts = bootstrapbuttons_getDefaultAtts($ID, $atts);
        bootstrapbuttons_processHeaders($ID, $atts['atts']);
        $bootstrapbuttonsregisteredElements[$name] = bootstrapbuttons_doShortcode($atts['atts'], $content, $slug);
    }
}
function bootstrapbuttons_render_element($name = false){
    global $bootstrapbuttonsregisteredElements;
    if(isset($bootstrapbuttonsregisteredElements[$name])){
        return $bootstrapbuttonsregisteredElements[$name];
    }
    return;
}
function bootstrapbuttons_getDefaultAtts($ElementID, $atts = false){

    $Element = get_option($ElementID);
    if(!empty($Element['_variable'])){
        if(empty($Element['_elementType'])){
            $Element['_elementType'] = 1;
        }
        if($Element['_elementType'] == 4 || $Element['_elementType'] == 5){
            $defaultatts = get_option($ElementID.'_cfg');
            if(!empty($defaultatts) && !empty($atts)){
                $atts = shortcode_atts($defaultatts, $atts);
            }
        }else{
            $defaultatts = array();
        }
        foreach($Element['_variable'] as $varkey=>$variable){
            if(!empty($Element['_type'][$varkey])){
                if($Element['_type'][$varkey] == 'Custom'){
                    $p = explode(',',$Element['_variableDefault'][$varkey], 2);
                    if(!empty($p[1])){
                        $Element['_variableDefault'][$varkey] = trim($p[1]);
                    }
                }
                if($Element['_type'][$varkey] == 'Dropdown'){
                    if(strpos($Element['_variableDefault'][$varkey], '*') !== false){
                        $opts = explode(',', $Element['_variableDefault'][$varkey]);
                        foreach($opts as $valoption){
                            if(strpos($valoption, '*') !== false){
                                $Element['_variableDefault'][$varkey] = trim(strtok($valoption, '*'));
                                break;
                            }
                        }
                    }else{
                        $Element['_variableDefault'][$varkey] = trim(strtok($Element['_variableDefault'][$varkey], ','));
                    }

                }
            }
            if(!empty($Element['_isMultiple'][$varkey])){
                $endLoop = true;
                $loopIndex = 1;
                while($endLoop){
                    if(isset($atts[$variable.'_'.$loopIndex])){
                        $defaultatts[$variable.'_'.$loopIndex] = $atts[$variable.'_'.$loopIndex];
                        $varArray[trim($variable)][] = $atts[$variable . '_' . $loopIndex];
                        $loopIndex++;
                    }else{
                        if($loopIndex === 1){
                            $defaultatts[$variable.'_'.$loopIndex] = $Element['_variableDefault'][$varkey];
                            $varArray[trim($variable)][] = $Element['_variableDefault'][$varkey];
                        }
                        $endLoop = false;
                    }
                }
            }else{
                $defaultatts[$variable] = $Element['_variableDefault'][$varkey];
            }

        }
         if(!empty($atts) && !empty($defaultatts)){
            $atts = shortcode_atts($defaultatts, $atts);
        }else{
            if(!empty($defaultatts)){
                $atts = $defaultatts;
            }else{
                $atts = array();
            }
        }

    }else{
        $atts = false;
    }
    if(!empty($varArray)){
        $Out['vararray'] = $varArray;
    }
    $Out['atts'] = $atts;    
    return $Out;
}
function bootstrapbuttons_doShortcode($atts, $content, $shortcode) {

    global $bootstrapbuttonsfooterOutput, $bootstrapbuttonsjavascript, $bootstrapbuttonsjsincludes;

    $elements = get_option('bootstrapbuttons');
    foreach ($elements as $id => $element) {
        if (!empty($element['shortcode'])) {
            if ($element['shortcode'] === $shortcode) {
                break;
            }
        }
    }
    if (empty($id)) {
        return;
    }
    $Element = get_option($id);
    $attsOutput = bootstrapbuttons_getDefaultAtts($id, $atts);
    $atts = $attsOutput['atts'];
    if(!empty($attsOutput['vararray'])){
        $varArray = $attsOutput['vararray'];
    }


    $pattern = '\[(\[?)(if)\b([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
    preg_match_all('/' . $pattern . '/s', $Element['_mainCode'], $mifs);
    preg_match_all('/' . $pattern . '/s', $Element['_javascriptCode'], $jifs);
    if(!empty($mifs[5]) && !empty($Element['_variable'])){
        foreach($Element['_variable'] as $varID=>$varKey){

            $pattern = '\[(\[?)(if ('.$varKey.')|if !('.$varKey.'))\b([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
            preg_match_all('/' . $pattern . '/s', $Element['_mainCode'], $subs);
            if(!empty($subs[2])){
                foreach($subs[2] as $ifKey=>$ifVal){

                    $caseOp = '';
                    $field = 3;
                    if(strpos($ifVal, '!') !== false){
                        $caseOp = '!';
                        $field = 4;
                    }

                    if(!empty($Element['_isMultiple'][$varID])){
                        if(!empty($caseOp)){
                            $phpCode = '<?php if(empty($varArray[\''.$varKey.'\'][0]) || $varArray[\''.$varKey.'\'][0] == \'false\'){ ?>';
                        }else{
                            $phpCode = '<?php if(!empty($varArray[\''.$varKey.'\'][0]) && $varArray[\''.$varKey.'\'][0] != \'false\'){ ?>';
                        }
                        $Element['_mainCode'] = str_replace('[if '.trim($caseOp.$varKey).']', $phpCode, $Element['_mainCode']);
                    }else{
                        if(!empty($caseOp)){
                            $phpCode = '<?php if(empty($atts[\''.$varKey.'\']) || $atts[\''.$varKey.'\'] == \'false\'){ ?>';
                            $varKeyRep = $varKey;
                        }else{
                            if(!empty($subs[5][$ifKey])){
                                $phpCode = '<?php if($atts[\''.$varKey.'\'] ='.$subs[5][$ifKey].'){ ?>';
                            }else{
                                $phpCode = '<?php if(!empty($atts[\''.$varKey.'\']) && $atts[\''.$varKey.'\'] != \'false\'){ ?>';
                            }
                            $varKeyRep = $varKey.$subs[5][$ifKey];
                        }
                        $Element['_mainCode'] = str_replace('[if '.trim($caseOp.$varKeyRep).']', $phpCode, $Element['_mainCode']);
                    }
                }
            }
            $Element['_mainCode'] = str_replace('[else]', "<?php }else{ ?>", $Element['_mainCode']);
            $Element['_mainCode'] = str_replace('[/if]', "<?php } ?>", $Element['_mainCode']);
        }        
    }
    
    
    if(!empty($jifs[5]) && !empty($Element['_variable'])){
        foreach($Element['_variable'] as $varID=>$varKey){
            $pattern = '\[(\[?)(if '.$varKey.')\b([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
            preg_match_all('/' . $pattern . '/s', $Element['_javascriptCode'], $subs);
            if(!empty($subs[3])){
                foreach($subs[3] as $ifVal){
                    $Element['_javascriptCode'] = str_replace('[if '.$varKey.$ifVal.']', "<?php if('{{".$varKey."}}' === '".trim($ifVal, '=')."'){ ?>", $Element['_javascriptCode']);
                }
            }
            
            $Element['_javascriptCode'] = str_replace('[if '.$varKey.']', "<?php if('{{".trim($varKey)."}}' != ''){ ?>", $Element['_javascriptCode']);
            $Element['_javascriptCode'] = str_replace('[else]', "<?php }else{ ?>", $Element['_javascriptCode']);
            $Element['_javascriptCode'] = str_replace('[/if]', "<?php } ?>", $Element['_javascriptCode']);
        }        
    }


    $instanceID = bootstrapbuttons_checkInstanceID('bootstrapbuttons'.$Element['_shortcode'], 'footer');

    $Element['_mainCode'] = str_replace('{{content}}', $content, $Element['_mainCode']);
    $Element['_mainCode'] = str_replace('{{_id_}}',$instanceID, $Element['_mainCode']);

    $Element['_javascriptCode'] = str_replace('{{content}}', $content, $Element['_javascriptCode']);
    $Element['_javascriptCode'] = str_replace('{{_id_}}',$instanceID, $Element['_javascriptCode']);




    $pattern = '\[(\[?)(loop)\b([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
    preg_match_all('/' . $pattern . '/s', $Element['_mainCode'], $loops);
    if (!empty($loops)) {
        foreach ($loops[0] as $loopKey => $loopcode) {
            if (!empty($loops[3][$loopKey])) {
                $LoopCodes[$loopKey] = $loops[5][$loopKey];
                $Element['_mainCode'] = str_replace($loopcode, '{{__loop_' . $loopKey . '_}}', $Element['_mainCode']);
                //$Element['_javascriptCode'] = str_replace($loopcode, '{{__loop_' . $loopKey . '_}}', $Element['_javascriptCode']);
            }
        }
    }
    
    if(!empty($Element['_assetLabel'])){
        foreach($Element['_assetLabel'] as $assetKey=>$AssetLabel){
            $Element['_mainCode'] = str_replace('{{'.$AssetLabel.'}}', BOOTSTRAPBUTTONS_URL.$Element['_assetURL'][$assetKey], $Element['_mainCode']);
        }
    }
    if (!empty($Element['_variable'])) {
        foreach ($Element['_variable'] as $VarKey => $Variable) {
            $VarVal = $Element['_variableDefault'][$VarKey];
            if (isset($atts[$Variable])) {
                $VarVal = $atts[$Variable];
            }

            $Element['_mainCode'] = str_replace('{{' . $Variable . '}}', $VarVal, $Element['_mainCode']);
            $Element['_javascriptCode'] = str_replace('{{' . $Variable . '}}', $VarVal, $Element['_javascriptCode']);
        }        
        if (!empty($LoopCodes) && !empty($varArray)) {
            foreach ($LoopCodes as $loopKey => $loopCode) {
                $loopReplace = '';
                if (!empty($varArray[trim($loops[3][$loopKey])])) {
                    foreach ($varArray[trim($loops[3][$loopKey])] as $replaceKey => $replaceVar) {
                        $loopReplace .= $loopCode;
                        foreach ($varArray as $Variable => $VarableArray) {
                            if (!empty($varArray[$Variable][$replaceKey])) {
                                $loopReplace = str_replace('{{' . $Variable . '}}', $varArray[$Variable][$replaceKey], $loopReplace);
                            } else {
                                $loopReplace = str_replace('{{' . $Variable . '}}', '', $loopReplace);
                            }
                            $loopReplace = str_replace('[increment]', $replaceKey, $loopReplace);
                        }
                    }
                    $Element['_mainCode'] = str_replace('{{__loop_' . $loopKey . '_}}', $loopReplace, $Element['_mainCode']);
                    //$Element['_javascriptCode'] = str_replace('{{__loop_' . $loopKey . '_}}', $loopReplace, $Element['_javascriptCode']);
                }
            }
        }
    }

    //$pattern = '\[(\[?)(if)\b([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
    //preg_match_all('/' . $pattern . '/s', $Element['_mainCode'], $ifs);

    if (!empty($Element['_javascriptCode'])) {
        
            $pattern = '\[(\[?)(once)\b([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
            preg_match_all('/' . $pattern . '/s', $Element['_javascriptCode'], $once);            
            if (!empty($once)) {
                foreach ($once[0] as $onceKey => $onceCode) {
                    if (!empty($once[5][$onceKey])) {
                        $codeSignature = md5(trim($once[5][$onceKey]));
                        if(empty($bootstrapbuttonsjsincludes[$codeSignature])){
                            $Element['_javascriptCode'] = str_replace($once[0][$onceKey], trim($once[5][$onceKey]), $Element['_javascriptCode']);
                            $bootstrapbuttonsjsincludes[$codeSignature] = true;
                        }else{
                            $Element['_javascriptCode'] = str_replace($once[0][$onceKey], '', $Element['_javascriptCode']);
                        }
                    }
                }
            }

        ob_start();
            eval(' ?>' . $Element['_javascriptCode'] . ' <?php ');
        $js = ob_get_clean();

        $bootstrapbuttonsfooterOutput .= "
        " . $js . "

        ";
    }


    ob_start();
    eval(' ?>' . $Element['_mainCode'] . ' <?php ');
    $Output = ob_get_clean();
    //return $Output;
    return do_shortcode(trim($Output));
}

if(is_admin ()){    
    function bootstrapbuttons_loadmedia_page(){

        $page = 12*$_POST['page']-12;


        $args = array(
                'post_type' => 'attachment',
                'numberposts' => 12,
                'offset' => $page,
                'post_status' => null,
                'post_parent' => null, // any parent
                );
            $attachments = get_posts($args);
            if ($attachments) {
                foreach ($attachments as $post) {
                    echo '<div>';
                    setup_postdata($post);
                    echo '<div class="mediaElement">';
                    echo wp_get_attachment_link( $post->ID, array(60,60), false, true, false);
                    echo '<div class="mediaTitleTitle"><a href="'.$post->guid.'">'.$post->post_title.'</a></div>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            die;

    }
    function bootstrapbuttons_addGroup($Element, $GroupID=false, $number=false, $instance = false){

        if($GroupID == false){
            $GroupID = $_POST['group'];
            $number = $_POST['number'];
            $instance = false;
            $optionID = $_POST['eid'];
            $andDie = true;
        }

    $Element = get_option($optionID);

    echo '<table class="form-table rowGroup group'.$GroupID.'" ref="'.$GroupID.'">';
    echo '<tbody>';
    $first = true;
    foreach($Element['_group'] as $key=>$groupKey){
        if($groupKey == $GroupID){
            if(!empty($Element['_isMultiple'][$key])){
                if(empty($Element['_label'][$key])){
                    $Element['_label'][$key] = ucwords($Element['_variable'][$key]);
                }
                $Default = false;
                if(!empty($instance[$Element['_variable'][$key].'_'.$number])){
                    $Default = $instance[$Element['_variable'][$key].'_'.$number];
                }
                $args = array(
                    'elementID' => $optionID,
                    'key' => $key,
                    'id' => $key.'_'.$number,
                    'name' => $Element['_variable'][$key].'_'.$number,
                    'default' => $Default,
                    'duplicate' => $first
                );
                echo bootstrapbuttons_attsConfigFields($args);
                //echo bootstrapbuttons_alwaysloadformFieldSet($Element['_type'][$Field], ucwords($Element['_label'][$Field]), $Field, $Element['_variable'][$Field].'_'.$number, $Default, true, $first, $optionID, $Element['_variableInfo'][$Field]);
                $first = false;
            }
        }
    }
    echo '</tbody>';
    echo '</table>';
    if(!empty($andDie)){
        die;
    }

}

function bootstrapbuttons_dropdown_pages($args = '') {
	$defaults = array(
		'depth' => 0, 'child_of' => 0,
		'selected' => 0, 'echo' => 1,
		'name' => 'page_id', 'id' => '',
		'show_option_none' => '', 'show_option_no_change' => '',
		'option_none_value' => ''
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );

	$pages = get_pages($r);
	$output = '';
	// Back-compat with old system where both id and name were based on $name argument
	if ( empty($id) )
		$id = $name;

	if ( ! empty($pages) ) {
		$output = "<select name='" . esc_attr( $name ) . "' class='" . esc_attr( $class ) . "' id='" . esc_attr( $id ) . "'>\n";
		if ( $show_option_no_change )
			$output .= "\t<option value=\"-1\">$show_option_no_change</option>";
		if ( $show_option_none )
			$output .= "\t<option value=\"" . esc_attr($option_none_value) . "\">$show_option_none</option>\n";
		$output .= walk_page_dropdown_tree($pages, $depth, $r);
		$output .= "</select>\n";
	}

	$output = apply_filters('wp_dropdown_pages', $output);

	if ( $echo )
		echo $output;

	return $output;
}
function bootstrapbuttons_formField($Element, $Type, $Title, $ID, $Name, $Default = false, $Dup = false, $isFirst = false, $caption = false){

    $class= 'itemRow';
    if(!empty($Dup)){
        $class = '';
    }
    if(!empty($Element['_assetLabel'])){
        foreach($Element['_assetLabel'] as $assetKey=>$asset){
            $Default = str_replace('{{'.$asset.'}}', BOOTSTRAPBUTTONS_URL.$Element['_assetURL'][$assetKey], $Default);
        }
    }
    $Return = '<tr valign="top" class="'.$class.'" ref="'.strtok($Name, '_').'"><th scope="row"><label for="field_'.$ID.'">'.$Title.'</label></th>';
    $Return .= '  <td>';

        switch ($Type){
            case 'Dropdown':
                $options = explode(',', $Element['_variableDefault'][$ID]);
                $Return .= '<select name="'.$Name.'" id="'.$Name.'" class="regular-text attrVal" ref="'.$ID.'">';
                    if(strpos($Element['_variableDefault'][$ID], '*') === false){
                        $Return .= '<option value=""></option>';
                    }
                    if($Element['_variableDefault'][$ID] == $Default){
                        $Default = 0;
                    }

                    foreach($options as $option){

                        $sel = '';
                        if(empty($Default)){
                            if(strpos($option, '*') !== false){
                                $sel = 'selected="selected"';
                            }
                        }
                        $option = str_replace('*', '', $option);
                        if($option == $Default){
                            $sel = 'selected="selected"';
                        }
                        $Return .= '<option value="'.$option.'" '.$sel.'>'.ucwords($option).'</option>';
                    }

                $Return .= '</select>';
                break;
            case 'Text':
                $Return .= '<input name="'.$Name.'"  id="'.$Name.'" class="regular-text attrVal" type="text" ref="'.$ID.'" value="'.$Default.'"/>';
                break;
            case 'File':
                $Return .= '<input name="'.$Name.'" id="'.$Name.'" type="text" class="regular-text attrVal" ref="'.$ID.'" value="'.$Default.'" />';
                $Return .= '<div id="uploader_field_'.$ID.'" class="fbutton" onclick="bootstrapbuttons_openMediaPanel(\''.$Name.'\');" style="float:none; display:inline;">';
                $Return .= '  <div class="button addFile" style="padding:2px 2px 1px; margin-bottom: 5px; font-weight:normal;">';
                $Return .= '      <i class="icon-file" style="margin-top:-1px;"></i><span> Media Library</span>';
                $Return .= '  </div>';
                $Return .= '</div>';
                break;
            case 'Page':
                $args = array(
                        'depth'   => 0,
                        'child_of'=> 0,
                        'selected'=> $Default,
                        'echo'    => 0,
                        'name'    => $Name,
                        'id'    => $Name,
                        'class'    => 'attrVal',
                        'post_type' => 'page');
                $Return .= bootstrapbuttons_dropdown_pages($args);
                //$Return .= '<input name="'.$Name.'"  id="'.$Name.'" class="regular-text attrVal" type="text" ref="'.$ID.'" value="'.$Default.'"/>';
                break;

        }

        if($isFirst){
            $Return .= '<div id="remover_field_'.$ID.'" class="fbutton remover" style="float:none; display:inline;">';
            $Return .= '  <div class="button removeRow" style="padding:2px 2px 1px; margin-bottom: 5px; font-weight:normal;">';
            $Return .= '      <i class="icon-remove-sign" style="margin-top:-1px;"></i><span> Remove</span>';
            $Return .= '  </div>';
            $Return .= '</div>';
        }
    if(!empty($caption)){
        $Return .= '<p class="description">'.$caption.'</p>';
    }

    $Return .= '  </td>';
    $Return .= '</tr>';

    return $Return;
}
function bootstrapbuttons_load_elementConfig($die = false){
         if(empty($_POST['element'])){
             echo 'Please select a shortcode to continue';
             die;
         }
        $optionID = $_POST['element'];
        $Element = get_option($_POST['element']);


        if(empty($Element['_defaultContent'])){
         $Element['_defaultContent'] = 'Content Goes Here';
        }
        echo '<input type="hidden" id="shortcodekey" value="'.$Element['_shortcode'].'" />';
        echo '<input type="hidden" id="shortcodetype" value="'.$Element['_shortcodeType'].'" />';
        echo '<input type="hidden" id="defaultContent" value="'.$Element['_defaultContent'].'" />';
        
        $Groups = array();
        if(!empty($Element['_variable'])){
            foreach($Element['_variable'] as $Key=>$Var){
                if(empty($Element['_isMultiple'][$Key])){
                    $Groups[$Element['_tabgroup'][$Key]][$Key] = $Var;
                }else{
                    $Groups[$Element['_group'][$Key]][$Key] = $Var;
                }

            }
        }        
        
?>

<div class="wrap poststuff" id="ce_container">
    <div id="main">
        <?php
            $groupCount = count($Groups);        
            $contentclass = '';
            if($groupCount == 1){
                $contentclass = 'solo';
            }        

        ?>
        <div id="ce-nav" class="<?php echo $contentclass; ?>">

            <ul>
                             
                <?php
                    $first = true;                    
                    foreach($Groups as $GroupName=>$vars){
                        $GroupID = sanitize_key($GroupName);
                        $class= '';                        
                        if(!empty($Element['_tabgroup'][$GroupID])){
                            $GroupName = ucwords($Element['_tabgroup'][$GroupID]);
                        }
                        if($first){
                            $class='class="current"';
                        }
                ?>
                <li <?php echo $class; ?>>
                    <a href="#<?php echo $GroupID; ?>" title="<?php echo $GroupName; ?>"><strong><?php echo $GroupName; ?></strong></a>
                </li>                
                
                <?php
                    $first = false;
                    }
                ?>
                
                
                
                
            </ul>

        </div>

        <div id="content" class="<?php echo $contentclass;?>">
            
            
            
            
<?php



if(empty($Element['_variable'])){
        echo 'No configuration nessasary for this plugin, just enjoy it!';
}









$isfirst = true;
foreach($Groups as $GroupName=>$vars){

            $GroupID = sanitize_key($GroupName);
            $class= '';                        
            if(!empty($Element['_tabgroup'][$GroupID])){
                $GroupName = ucwords($Element['_tabgroup'][$GroupID]);
            }
            $display = 'none';
            if($isfirst){
                $display = 'block';
                $isfirst = false;
            }
            
            echo '<div style="display: '.$display.';" class="group" id="'.$GroupID.'">';
            if($groupCount == 1){
                $GroupName = $Element['_name'];                
            }
                echo '<h2>'.$GroupName.'</h2>';
                        
            echo '<p>';
            if(!empty($Element['_variable'][$GroupID])){
                echo '<table class="form-table rowGroup group'.$GroupID.'" id="group'.$GroupID.'" ref="'.$GroupID.'">';
            }else{
                echo '<table class="form-table group'.$GroupID.'" id="group'.$GroupID.'" ref="'.$GroupID.'">';
            }      
            echo '<tbody>';
            foreach($vars as $key=>$var){
                if($Element['_type'][$key] == 'Color Picker'){
                    $enableColorPicker = true;
                }
                if(empty($Element['_label'][$key])){
                    $Element['_label'][$key] = ucwords($Element['_variable'][$key]);
                }
                if(!empty($Element['_isMultiple'][$GroupID])){
                    // go make a multi group
                    if(!isset($isfirst)){
                       $isfirst = true;
                    }else{
                        $isfirst = false;
                    }
                    if(empty($instance[$var.'_1'])){
                        $instance[$var.'_1'] = $Element['_variableDefault'][$key];
                    }
                    $args = array(
                        'elementID' => $optionID,
                        'key' => $key,
                        'id' => $key,
                        'name' => $var.'_1',
                        'default' => $instance[$var.'_1'],
                        'duplicate' => false
                    );
                    echo bootstrapbuttons_attsConfigFields($args);
                }else{
                    // go make a single group
                    if(empty($instance[$var])){
                        $instance[$var] = $Element['_variableDefault'][$key];
                    }
                    if(empty($Element['_variableInfo'][$key])){
                         $Element['_variableInfo'][$key] = '';
                    }
                    
                    $args = array(
                        'elementID' => $optionID,
                        'key' => $key,
                        'id' => $key,
                        'name' => $var,
                        'default' => $instance[$var],
                        'duplicate' => false
                    );
                    echo bootstrapbuttons_attsConfigFields($args);
                }
            }
            echo '</tbody>';
            echo '</table>';            
            
            echo '</p>';

            $run = true;
            $index = 2;
            while($run){
                if(empty($GroupID) || empty($Element['_variable'][$GroupID])){
                    break;
                }
                if(!empty($instance[$Element['_variable'][$GroupID].'_'.$index])){
                    echo bootstrapbuttons_alwaysloadaddGroupSet($GroupID, $index, $instance, $optionID);
                }else{
                    $run = false;
                }
                $index++;
            }



         if(!empty($Element['_variable'][$GroupID])){
            echo '<div class="toolrow" id="tool'.$GroupID.'"><span class="fbutton"><a class="button addRow" href="'.$GroupID.'" ref="'.$optionID.'">Add '.$GroupName.'</a></span></div>';
         }
         echo '</div>';
        }




?>
            
            
          
            
            
            
            
            
            
            
        </div>
        <div class="clear"></div>

    </div>
    <div style="clear:both;"></div>
</div>

<?php        
        if($die == true){
            return;
        }
        die;
    }



function bootstrapbuttons_attsConfigFields($args, $isWidget = false){

    if(empty($args['elementID']) || empty($args['key'])){
        return 'Need to define the element and key.';
    }    
    $Element = get_option($args['elementID']); 
    $argDefault = array(
        'elementID' => '',
        'key' => '',
        'id' => $Element['_variable'][$args['key']],
        'name' => $Element['_variable'][$args['key']],
        'default' => trim($Element['_variableDefault'][$args['key']]),
        'duplicate' => false
    );
    $args = wp_parse_args($args, $argDefault);
    
    $class= 'itemRow';
    if(!empty($Dup)){
        $class = '';
    }  
    if(empty($Element)){
        return 'Element ID "'.$args['elementID'].'" is invalid.';
    }
    if(!empty($Element['_assetLabel'])){
        foreach($Element['_assetLabel'] as $assetKey=>$asset){
            $args['default'] = str_replace('{{'.$asset.'}}', BOOTSTRAPBUTTONS_URL.$Element['_assetURL'][$assetKey], $args['default']);
        }
    }
    if($isWidget){
        $Return = '<label for="'.$args['id'].'">'.$Element['_label'][$args['key']].':</label>';
    }else{
        $Return = '<tr valign="top" class="'.$class.'"><th scope="row"><label for="'.$args['id'].'">'.$Element['_label'][$args['key']].'</label></th>';
        $Return .= '  <td>';
    }


        switch ($Element['_type'][$args['key']]){
            case 'Dropdown':
                $options = explode(',', $Element['_variableDefault'][$args['key']]);
                $Return .= '<select name="'.$args['name'].'" class="regular-text" ref="'.$args['id'].'" id="field_'.$args['id'].'">';

                    $Return .= '<option value=""></option>';
                    foreach($options as $option){

                        $sel = '';
                        if(strpos($option, '*') !== false && ($args['default'] == $Element['_variableDefault'][$args['key']])){
                            $sel = 'selected="selected"';
                        }
                        $option = str_replace('*', '', $option);
                        if($option == $args['default']){
                            $sel = 'selected="selected"';
                        }
                        $Return .= '<option value="'.$option.'" '.$sel.'>'.ucwords($option).'</option>';
                    }

                $Return .= '</select>';
                break;
            case 'Checkbox':
                $options = explode(',', $Element['_variableDefault'][$args['key']]);
                    foreach($options as $key=>$option){

                        $sel = '';
                        if(strpos($option, '*') !== false && ($args['default'] == $Element['_variableDefault'][$args['key']])){
                            $sel = 'checked="checked"';
                        }
                        $option = str_replace('*', '', $option);
                        $label = ucwords($option);
                        if(strpos($option, ';') !== false){
                            $opts = explode(';', $option);
                            $option = $opts[0];
                            $lable = $opts[1];
                        }
                        if($option == $args['default']){
                            $sel = 'checked="checked"';
                        }
                        $Return .= '<label for="'.$args['id'].'">';
                        $Return .= '<input type="checkbox" value="'.$option.'" id="'.$args['id'].'" name="'.$args['name'].'" '.$sel.'/> ';
                        $Return .= $label.'</label><br />';
                        
                    }
                break;            
            case 'Radio':
                $options = explode(',', $Element['_variableDefault'][$args['key']]);
                    foreach($options as $key=>$option){

                        $sel = '';
                        if(strpos($option, '*') !== false && ($args['default'] == $Element['_variableDefault'][$args['key']])){
                            $sel = 'checked="checked"';
                        }
                        $option = str_replace('*', '', $option);
                        $label = ucwords($option);
                        if(strpos($option, ';') !== false){
                            $opts = explode(';', $option);
                            $option = $opts[0];
                            $lable = $opts[1];
                        }
                        if($option == $args['default']){
                            $sel = 'checked="checked"';
                        }
                        $Return .= '<label for="'.$args['id'].'">';
                        $Return .= '<input type="radio" value="'.$option.'" id="'.$args['id'].'" name="'.$args['name'].'" '.$sel.'/> ';
                        $Return .= $label.'</label><br />';
                        
                    }
                break;            
            case 'Color Picker':
                $Return .= '<input name="'.$args['name'].'" class="small-text minicolorPicker" type="text" ref="'.$args['id'].'" id="'.$args['id'].'" value="'.$args['default'].'" style="width:115px;"/><a href="#" style="background-color: '.$args['default'].'" class="miniColors-trigger miniColors-trigger-fake"></a>';
                break;
            case 'Text':
            case 'Text Field':
                $Return .= '<input name="'.$args['name'].'" class="regular-text" type="text" ref="'.$args['id'].'" id="'.$args['id'].'" value="'.$args['default'].'"/>';
                break;
            case 'Text Box':
                $Return .= '<textarea name="'.$args['name'].'" class="large-text" rows="5" type="text" id="'.$args['id'].'" ref="'.$args['id'].'">'.htmlspecialchars($args['default']).'</textarea>';
                break;
            case 'File':
                $Return .= '<input name="'.$args['name'].'" type="text" class="regular-text" ref="'.$args['id'].'" id="'.$args['id'].'" value="'.$args['default'].'" />';
                $Return .= '<div id="uploader_'.$args['id'].'" class="fbutton bootstrapbuttons_uploader" style="float:none; display:inline;">';
                $Return .= '  <div class="button addFile" style="padding:2px 2px 1px; margin-bottom: 5px; font-weight:normal;">';
                $Return .= '      <i class="icon-file" style="margin-top:-1px;"></i><span> Select File</span>';
                $Return .= '  </div>';
                $Return .= '</div>';
                break;
            case 'Page Selector':
            case 'Page':
                $pageargs = array(
                        'depth'   => 0,
                        'child_of'=> 0,
                        'selected'=> $args['default'],
                        'echo'    => 0,
                        'name'    => $args['name'],
                        'id'    => $args['id'],
                        'class'    => 'attrVal',
                        'post_type' => 'page');
                $pageArgsDefault = array(
                        'depth' => 0, 'child_of' => 0,
                        'selected' => 0, 'echo' => 1,
                        'name' => 'page_id', 'id' => '',
                        'show_option_none' => '', 'show_option_no_change' => '',
                        'option_none_value' => ''
                );

                $r = wp_parse_args( $pageargs, $pageArgsDefault );
                extract( $r, EXTR_SKIP );

                $pages = get_pages($r);
                $output = '';
                if ( empty($id) )
                        $id = $name;

                if ( ! empty($pages) ) {
                        $output = "<select name='" . esc_attr( $name ) . "' class='" . esc_attr( $class ) . "' id='" . esc_attr( $id ) . "'>\n";
                        if ( $show_option_no_change )
                                $output .= "\t<option value=\"-1\">$show_option_no_change</option>";
                        if ( $show_option_none )
                                $output .= "\t<option value=\"" . esc_attr($option_none_value) . "\">$show_option_none</option>\n";
                        $output .= walk_page_dropdown_tree($pages, $depth, $r);
                        $output .= "</select>\n";
                }

                $Return .= apply_filters('wp_dropdown_pages', $output);
                break;
            case 'Custom':
                //caldera_formField($Element, $Type, $Title, $ID, $Name, $Default = false, $Dup = false, $isFirst = false, $caption = false);


                $Custom = explode(';', $argDefault['default']);
                $func = $Custom[0];
                if($args['default'] == $argDefault['default']){
                   if(!empty($Custom[1])){
                       $args['default'] = $Custom[1];
                   }else{
                       $args['default'] = '';
                   }
                }
                $Return .= $func($args);

                break;
        }

        if(!empty($args['duplicate'])){
            $Return .= '<div id="remover_'.$args['id'].'" class="fbutton remover" style="float:none; display:inline;">';
            $Return .= '  <div class="button removeRow" style="padding:2px 2px 1px; margin-bottom: 5px; font-weight:normal;">';
            $Return .= '      <i class="icon-remove-sign" style="margin-top:-1px;"></i><span> Remove</span>';
            $Return .= '  </div>';
            $Return .= '</div>';
        }
    if(!empty($Element['_variableInfo'][$args['key']])){
        $Return .= '<p class="description">'.$Element['_variableInfo'][$args['key']].'</p>';
    }
	   if(empty($isWidget)){
    		$Return .= '  </td>';
    		$Return .= '</tr>';
		}
    return $Return;
}

}
?>