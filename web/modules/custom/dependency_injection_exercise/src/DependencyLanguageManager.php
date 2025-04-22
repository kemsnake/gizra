<?php

namespace Drupal\dependency_injection_exercise;

use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Language\LanguageManager;

/**
 * Modifies the language manager service.
 */
class DependencyLanguageManager extends LanguageManager {

  /**
   * {@inheritdoc}
   */
  public function getLanguages($flags = LanguageInterface::STATE_CONFIGURABLE) {
    \Drupal::logger('dependency_injection_exercise')->notice('custom getLanguages');
    $static_cache_id = $this->getCurrentLanguage()->getId();
    if (!isset($this->languages[$static_cache_id][$flags])) {
      // If this language manager is used, there are no configured languages.
      // The default language and locked languages comprise the full language
      // list.
      $default = $this->getDefaultLanguage();
      $languages = [$default->getId() => $default];
      //$languages[] = [$default->getId() => $default];

      // Filter the full list of languages based on the value of $flags.
      $this->languages[$static_cache_id][$flags] = $languages;
    }
    return $this->languages[$static_cache_id][$flags];
  }

}
