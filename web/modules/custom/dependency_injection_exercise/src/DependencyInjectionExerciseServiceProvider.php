<?php

namespace Drupal\dependency_injection_exercise;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

// @note: You only need Reference, if you want to change service arguments.
use Symfony\Component\DependencyInjection\Reference;

/**
 * Modifies the language manager service.
 */
class DependencyInjectionExerciseServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    // Overrides language_manager class to test domain language negotiation.
    // Adds entity_type.manager service as an additional argument.

    // Note: it's safest to use hasDefinition() first, because getDefinition() will
    // throw an exception if the given service doesn't exist.
    if ($container->hasDefinition('language_manager')) {
      $definition = $container->getDefinition('language_manager');
      $definition->setClass('Drupal\dependency_injection_exercise\DependencyLanguageManager');
    }
  }

}
