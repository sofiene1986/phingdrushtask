<?php

namespace Phing\Drush;

/**
 * Class Param. Represents a Drush CLI parameter.
 */
class Param extends \CommandlineArgument {

  /**
   * The parameter's value.
   *
   * @var string
   */
  protected $value;


  /**
   * Set the parameter value from a text element.
   *
   * @param string $str
   *   The value of the text element.
   */
  public function addText($str) {
    $this->value = (string) $str;
  }

  /**
   * Get the parameter's value.
   *
   * @return string
   *   The parameter value.
   */
  public function getValue() {
    return $this->value;
  }

  /**
   * Get the string.
   *
   * @return string
   *   The parameter string.
   */
  public function toString() {
    $value = $this->getValue();

    return $value;
  }

}
