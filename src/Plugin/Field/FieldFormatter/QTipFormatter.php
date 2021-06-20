<?php

namespace Drupal\custom_field_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'QTip' formatter.
 *
 * @FieldFormatter(
 *   id = "qtip_formatter",
 *   label = @Translation("QTip Field Formatter"),
 *   field_types = {
 *     "string",
 *     "text"
 *   }
 * )
 */
class QTipFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Displays the string in QTip Format.');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements['#attached']['library'][] = 'custom_field_formatter/custom_field_formatter';
    $elements['#theme'] = 'qtip_field_formatter';

    foreach ($items as $delta => $item) {
      $elements['#items'][$delta] = $item;
    }

    return $elements;
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