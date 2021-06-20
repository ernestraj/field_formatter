<?php

namespace Drupal\custom_field_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\custom_field_formatter\SlugifyService;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Plugin implementation of the 'Slugify' formatter.
 *
 * @FieldFormatter(
 *   id = "slugify_formatter",
 *   label = @Translation("Slugify Field Formatter"),
 *   field_types = {
 *     "string",
 *     "text"
 *   },
 *   settings = {
 *     "separator" = "-"
 *   }
 * )
 */
class SlugifyFormatter extends FormatterBase {

  protected $slugifyService;

  /**
   * Constructs a LanguageFormatter instance.
   *
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the formatter is associated.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Any third party settings settings.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, SlugifyService $custom_service) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);

    $this->slugifyService = $custom_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('custom_field_formatter.custom_service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'separator' => '-'
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = parent::settingsForm($form, $form_state);

    $element['separator'] = [
      '#title' => $this->t('Separator'),
      '#type' => 'textfield',
      '#size' => 10,
      '#default_value' => $this->getSetting('separator') ? $this->getSetting('separator'): "-",
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $separator = $this->getSetting('separator') ? $this->getSetting('separator'): "-";
    $summary[] = $this->t('Displays the string in slugify Format. Separator used ' . $separator);
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      // Render each element as markup.
      $element[$delta] = ['#markup' => $this->slugifyService->slugify($item->value, $this->getSetting('separator'))];
    }

    return $element;
  }
}