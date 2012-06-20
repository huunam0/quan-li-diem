<?php
$fusion_page_replacements = "";
$fusion_output_handlers = "";
$fusion_page_title = $settings['sitename'];
$fusion_page_meta = array("description" => $settings['description'], "keywords" => $settings['keywords']);
$fusion_page_head_tags = "";

function set_title($title=""){
        global $fusion_page_title;
        
        $fusion_page_title = $title;
}

function add_to_title($addition=""){
        global $fusion_page_title;
        
        $fusion_page_title .= $addition;
}

function set_meta($name, $content=""){
        global $fusion_page_meta;
        $fusion_page_meta[$name] = $content;
}

function add_to_meta($name, $addition=""){
        global $fusion_page_meta;
        if(isset($fusion_page_meta[$name])){
                $fusion_page_meta[$name] .= $addition;
        }
}

function add_to_head($tag=""){
        global $fusion_page_head_tags;
        
        if(!stristr($fusion_page_head_tags, $tag)){
                $fusion_page_head_tags .= $tag."\n";
        }
}

function replace_in_output($target, $replace, $modifiers=""){
        global $fusion_page_replacements;
        
        $fusion_page_replacements .= "\$output = preg_replace('^$target^$modifiers', '$replace', \$output);";
}

function add_handler($name){
        global $fusion_output_handlers;
        if(!empty($name)){
                $fusion_output_handlers = "\$output = $name(\$output);";
        }
}

function handle_output($output){
        global $fusion_page_head_tags, $fusion_page_title, $fusion_page_meta, $fusion_page_replacements, $fusion_output_handlers, $settings;
        
        if(!empty($fusion_page_head_tags)){
                $output = preg_replace("^(</head>)^", $fusion_page_head_tags."\\1", $output, 1);
        }
        if($fusion_page_title != $settings['sitename']){
                $output = preg_replace("^(<title>).*(</title>)^i", "\\1".$fusion_page_title."\\2", $output, 1);
        }
        if(!empty($fusion_page_meta)){
                foreach($fusion_page_meta as $name => $content){
                        $output = preg_replace("^(<meta (http-equiv|name)='$name' content=').*(' />)^i", "\\1".$content."\\3", $output, 1);
                }
        }
        if(!empty($fusion_page_replacements)){
                eval($fusion_page_replacements);
        }
        if(!empty($fusion_output_handlers)){
                eval($fusion_output_handlers);
        }
        
        return $output;
}

?>
