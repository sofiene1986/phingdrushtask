<?php

namespace Drush;

/**
 * Class Param. Represents a Drush CLI parameter.
 */
class Param {

  /**
   * The parameter's value.
   *
   * @var string
   */
  protected $value;

  /**
   * If TRUE, escape the value. Otherwise not. Default is TRUE.
   *
   * @var bool
   */
  protected $escape;

  /**
   * If TRUE, surround the value with quotes. Otherwise not. Default is TRUE.
   *
   * @var bool
   */
  protected $quote;

  /**
   * DrushParam constructor.
   */
  public function __construct() {
    $this->setEscape();
    $this->setQuote();
  }

  /**
   * Set the escape's param value.
   *
   * @param string|bool $escape
   *   The escape's param value.
   */
  public function setEscape($escape = TRUE) {
    $this->escape = $escape;
  }

  /**
   * Get the escape's param value.
   *
   * @return bool
   *   The escape's param value.
   */
  public function getEscape() {
    return ($this->escape === 'yes' || $this->escape === 'true');
  }

  /**
   * Set the quote's param value.
   *
   * @param string|bool $quote
   *   The quote's param value.
   */
  public function setQuote($quote = TRUE) {
    $this->quote = $quote;
  }

  /**
   * Get the quote's param value.
   *
   * @return bool
   *   The quote's param value.
   */
  public function getQuote() {
    return ($this->quote === 'yes' || $this->quote === 'true');
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

    if ($this->getEscape()) {
      $value = escapeshellcmd($value);
    }

    if ($this->getQuote()) {
      $value = '"' . $value . '"';
    }

    return $value;
  }

}
