<?php

/**
 * @file
 * Database handler class.
 */

class RzDbConnect {
  /**
   * Gets query result.
   */
  public function getResult($s_query) {
    return dbquery($s_query);
  }

  /**
   * Gets array.
   */
  public function getArray($s_query) {
    return $this->fetch($this->getResult($s_query));
  }

  /**
   * Gets single value.
   */
  public function getValue($s_query) {
    $a_result = $this->fetch($this->getResult($s_query));
    if (is_array($a_result) && count($a_result) > 0) {
      $a_keys = array_keys($a_result);
      return $a_result[$a_keys[0]];
    }
    return "";
  }

  /**
   * Fetches query record.
   */
  public function fetch($r_result) {
    $a_res = dbarray($r_result);
    if (!is_array($a_res) || count($a_res) == 0) {
      $a_res = NULL;
    }
    return $a_res;
  }
}
