<?php

namespace Drupal\custom_field_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'Rot 13' formatter.
 *
 * @FieldFormatter(
 *   id = "rot13_default",
 *   label = @Translation("Rot 13 Field Formatter"),
 *   field_types = {
 *     "string",
 *     "text"
 *   }
 * )
 */
class Rot13DefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Displays the string in rot13 Format.');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      // Render each element as markup.
      $element[$delta] = ['#markup' => $this->rot13($item->value)];
    }

    return $element;
  }

  public function rot13($string) {
    $string = array_map('ord', str_split($string));

    foreach ($string as $index => $char) {
        if (ctype_lower($char)) {
            $dec = ord('a');
        } elseif (ctype_upper($char)) {
            $dec = ord('A');
        } else {
            $string[$index] = $char;
            continue;
        }
        $string[$index] = (($char - $dec + 13) % 26) + $dec;
    }

    return implode(array_map('chr', $string));
  }
}