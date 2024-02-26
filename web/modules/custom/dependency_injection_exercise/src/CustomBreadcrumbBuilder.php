<?php

namespace Drupal\dependency_injection_exercise;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides a breadcrumb builder.
 */
class CustomBreadcrumbBuilder implements BreadcrumbBuilderInterface {

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    return $route_match->getRouteName() == 'dependency_injection_exercise.rest_output_controller_photos';
  }

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match) {
    $breadcrumb = new Breadcrumb();
    $breadcrumb->addCacheContexts(['url.path.parent']);
    $breadcrumb->addLink(Link::createFromRoute(new TranslatableMarkup('Home'), '<front>'));
    $breadcrumb->addLink(Link::createFromRoute(new TranslatableMarkup('Dropsolid'), 'system.admin'));
    $breadcrumb->addLink(Link::createFromRoute(new TranslatableMarkup('Example'), 'system.admin_config'));
    $breadcrumb->addLink(Link::createFromRoute(new TranslatableMarkup('Photos'), 'dependency_injection_exercise.rest_output_controller_photos'));

    return $breadcrumb;
  }

}
