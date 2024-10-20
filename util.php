<?php
function get_base_url($url) {
  $split_url = explode("/", $url);
  $base_url = $split_url[0] . "//" . $split_url[2] . "/";
  return $base_url;
}

function remove_duplicates_by_key($array, $key) {
  $temp_array = [];
  $key_array = [];
  foreach($array as $val) {
    if (!in_array($val[$key], $key_array)) {
      $key_array[] = $val[$key];
      $temp_array[] = $val;
    }
  }
  return $temp_array;
}

function print_elapsed_time($start) {
  $now = microtime(true);
  $time_diff = $now-$start;
  $time_diff = round($time_diff, 2, PHP_ROUND_HALF_UP);
  echo "<div class=\"elapsed-time\">";
    echo "<p>Search took: $time_diff sec.</p>";
  echo "</div>";
}
?>