<?php
// Redirect browser using the header function, operation status saved also
function redirect($location, $status = "", $type = "header") {
	if ($status == "header" || $status == "script") {
		$type = $status;
		$status = "";
	}
	if (!empty($status) && isset($_SESSION)) {
		$_SESSION['_status'] = $status;
		if ($type != "header" && $type != "script") {
			$_SESSION['_status_message'] = $type;
			$type = "header";
		}
	}
	if ($type == "header") {
		header("Location: ".$location);
		exit();
	} else {
		echo "<script type='text/javascript'>document.location.href='".$location."'</script>\n";
	}
}
/**
 * Returns given $lower_case_and_underscored_word as a camelCased word.
 *
 * @param string $lower_case_and_underscored_word Word to camelize
 * @return string Camelized word. likeThis.
 * @access public
 * @static
 * Got from ABC::Inflector
 */
function camelize($lower_case_and_underscored_word) {
    $replace = str_replace(" ", "", ucwords(str_replace("_", " ", $lower_case_and_underscored_word)));
    return $replace;
}
//! implode function can't work with UTF-8 Cyr. Let's make our own! With blackjack and hookers. In fact, forget the function.
function mb_implode( $glue, $pieces ){
    $cnt = count( $pieces );
    if( $cnt < 2 ) return $pieces[0];
    $r = '';
    for( $i = 0; $i < $cnt-1; $i++ ) $r .= $pieces[$i].$glue;
    return $r.$pieces[$cnt -1];
}
?>
