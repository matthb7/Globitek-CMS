<?php

  // is_blank('abcd')
  function is_blank($value='') {
    return empty($value);
  }

  // has_length('abcd', ['min' => 3, 'max' => 5])
  function has_length($value, $options=array()) {
    $len = strlen($value);
    return $len >= $options[0] && $len <= $options[1];
  }

  // has_valid_email_format('test@test.com')
  function has_valid_email_format($value) {
    return filter_var($value, FILTER_VALIDATE_EMAIL);
  }

?>
