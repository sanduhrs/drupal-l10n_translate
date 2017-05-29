<?php

namespace Drupal\l10n_translate;

/**
 * Interface TranslationInterface.
 *
 * @package Drupal\l10n_translate
 */
interface TranslationInterface {

  /**
   * Set the language code for the string.
   *
   * @param string $langcode
   *   A language code e.g. en, de, fr.
   *
   * @return self
   *   Return self for chaining.
   */
  public function setSource($langcode);

  /**
   * Set the translation target language code.
   *
   * @param string $langcode
   *   A language code e.g. en, de, fr.
   *
   * @return self
   *   Return self for chaining.
   */
  public function setTarget($langcode);

  /**
   * Translate a string.
   *
   * @param string $string
   *   The string to translate.
   *
   * @return array
   *   An array of translated string and some more information.
   */
  public function translate($string);

}
