<?php

namespace Drupal\custom_field_formatter;

use Cocur\Slugify\Slugify;

/**
 * Class SlugifyService.
 */
class SlugifyService {
  /**
   * Returns Slugify content
   */
  public function slugify($string, $separator) {
    $slugify = new Slugify();
    return $slugify->slugify($string, $separator);
  }
}