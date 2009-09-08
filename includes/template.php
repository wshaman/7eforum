<?php
/*! class Template, Smarty wrapper
 * Special vars:
 * {$title_for_layout} - page title, T::setTitle(<title>)
 * {$keywords_for_layout} - meta keywords, T::setKeywords(<keywords>)
 * {$description_for_layout} - meta description, T::setDescription(<description>)
 * {$head_array} - custom array to place in <HEAD> block, T::assignHEAD(<content>)
 * {$css_array} - css sources, T::assignCSS(<src>)
 * {$js_array} - js sources, T::assignJS(<src>)
  * {$content_for_layout} - special var for content
 * {$use_wysiwyg_editor} - T::useEditor();
 * $GLOBALS['_debug_array_']
*/
class T extends Template{

}
class Template{
	var $smarty;
	function &getInstance() {
		static $instance;
		if (!isset($instance)) {
           $c = __CLASS__;
           $instance[0] = new $c;
		}
		return $instance[0];
	}
	function init($smarty_dir,$template_dir,$compile_dir, $debug = true) {
		require_once $smarty_dir.'Smarty.class.php';
		$_this = &Template::getInstance();
		$_this->smarty = new Smarty();
		$_this->smarty->compile_check = true;
		$_this->smarty->debugging = $debug;
		$_this->smarty->template_dir = $template_dir;
		$_this->smarty->compile_dir = $compile_dir;
		$_this->registerSmartyFunctions();
	}
	// Smarty functions wrappers
	function assign($var, $value = '') {
		$_this = &Template::getInstance();
		$_this->smarty->assign($var, $value);
	}
	function display($file_name, $var = '', $value = '') {
		$_this = &Template::getInstance();
		if (!empty($var)) $_this->assign($var, $value);
		$_this->smarty->display($file_name);
	}
	function fetch($file_name, $var = '', $value = '') {
		$_this = &Template::getInstance();
		if (!empty($var)) $_this->assign($var, $value);		
		return $_this->smarty->fetch($file_name);
	}
	function get($name = null) {
		return Template::getVars( $name );
	}
	function getVars($name = null) {
		$_this = &Template::getInstance();
		return $_this->smarty->get_template_vars($name);
	}
	// end smarty wrappers
	function registerSmartyFunctions() {
		$_this = &Template::getInstance();
		$f_arr = get_defined_functions();
		foreach ($f_arr['user'] as $f) {
			if (substr($f,0,13) == "smarty_block_") {
				//echo substr($f,14);
				$_this->smarty->register_block(substr($f,13), $f);
			} else if (substr($f,0, 16) == "smarty_modifier_") {
				$_this->smarty->register_modifier(substr($f,16), $f);
			} else if (substr($f,0, 16) == "smarty_function_") {
				$_this->smarty->register_function(substr($f, 16), $f);
			} else if (preg_match("/smarty_resource_([\w]+)_template/i",$f,$r)) {
				$_this->smarty->register_resource($r[1], array("smarty_resource_".$r[1]."_template",
														"smarty_resource_".$r[1]."_timestamp",
														"smarty_resource_".$r[1]."_secure",
														"smarty_resource_".$r[1]."_trusted"));
			}
		}
	}
	//! $title - page title
	/*! $mode - "r" = replace, "i" = insert, "a" = append*/
	function setTitle($title, $mode = "r", $separator = " - ") {
		if ($mode != "r") {
			$current_title = Template::getVars("title_for_layout");
			$title = ($mode == "i") ? $title.$separator.$current_content : $current_title.$separator.$title;
		}
		Template::assign("title_for_layout",$title);
	}
	//! $keywords - meta keywords
	/*! $mode - "r" = replace, "i" = insert, "a" = append*/
	function setKeywords($keywords, $mode = "r", $separator = ",") {
		if (is_array($keywords)) $keywords = implode($separator, $keywords);
		if ($mode != "r") {
			$current_keywords = Template::getVars("keywords_for_layout");
			$keywords = ($mode == "i") ? $keywords.$separator.$current_keywords : $current_keywords.$separator.$keywords;
		}		
		Template::assign("keywords_for_layout", $keywords);
	}
	//! $description - meta description
	/*! $mode - "r" = replace, "i" = insert, "a" = append*/
	function setDescription($description, $mode = "r", $separator = ". ") {
		if (is_array($description)) implode($separator, $description);
		if ($mode != "r") {
			$current_description = Template::getVars("description_for_layout");
			$description = ($mode == "i") ? $description.$separator.$current_description : $current_description.$separator.$description;
		}		
		Template::assign("description_for_layout", $description);
	}
	function assignJS($js_src) {
		if (strpos($js_src,"://") === false && $js_src{0} != "/") {
			$js_src = JS_URL.$js_src;
		}
		$js_array = Template::getVars("js_array");
		if (!is_array($js_array))
			$js_array = array($js_src);
		else
			$js_array[] = $js_src;
		Template::assign("js_array",$js_array);
	}
	function assignCSS($css_src) {
		if (strpos($css_src,"://") === false && $css_src{0} != "/") {
			$css_src = CSS_URL.$css_src;
		}
		$css_array = Template::getVars("css_array");
		if (!is_array($css_array))
			$css_array = array($css_src);
		else
			$css_array[] = $css_src;
		Template::assign("css_array", $css_array);
	}
}
?>
