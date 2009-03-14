<?php
function wp_b64_ajaxdecode($encodedtext) {
$formatedtext = wp_b64_format_text(html_entity_decode(base64_decode($encodedtext), ENT_QUOTES));
return $formatedtext;
}

function wp_b64_format_text($decodedtext) {
  $retval = "<p>";
  $retval .= str_replace("\n", "</p><p>", $decodedtext);
  $retval .= "</p>";
  return $retval;
}

echo wp_b64_ajaxdecode($_REQUEST['string']);

?>
