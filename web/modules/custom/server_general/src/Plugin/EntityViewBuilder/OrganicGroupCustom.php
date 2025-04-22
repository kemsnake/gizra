<?php

namespace Drupal\server_general\Plugin\EntityViewBuilder;

use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\node\Entity\NodeType;
use Drupal\node\NodeInterface;
use Drupal\og\Og;
use Drupal\og\OgMembershipInterface;
use Drupal\server_general\ElementNodeGroupTrait;
use Drupal\server_general\EntityViewBuilder\NodeViewBuilderAbstract;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The "Node News" plugin.
 *
 * @EntityViewBuilder(
 *   id = "node.group",
 *   label = @Translation("Node - Group"),
 *   description = "Node view builder for Group bundle."
 * )
 */
class OrganicGroupCustom extends NodeViewBuilderAbstract {

  use ElementNodeGroupTrait;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The OG access service.
   *
   * @var \Drupal\og\OgAccessInterface
   */
  protected $ogAccess;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $plugin = parent::create($container, $configuration, $plugin_id, $plugin_definition);

    $plugin->renderer = $container->get('renderer');
    $plugin->currentUser = $container->get('current_user');
    $plugin->ogAccess = $container->get('og.access');

    return $plugin;
  }

  /**
   * Build full view mode.
   *
   * @param array $build
   *   The existing build.
   * @param \Drupal\node\NodeInterface $entity
   *   The entity.
   *
   * @return array
   *   Render array.
   */
  public function buildFull(array $build, NodeInterface $entity) {
    // The node's label.
    $node_type = NodeType::load($entity->bundle());
    $label = $node_type->label();

    // Check membership.
    $is_member = Og::isMember($entity, $this->currentUser);
    if ($is_member) {
      $subscribe_text = $this->t('You already subscribed to this group.');
    }
    /** @var \Drupal\Core\Access\AccessResult $access */
    elseif (
      (($access = $this->ogAccess->userAccess($entity, 'subscribe', $this->currentUser)) && $access->isAllowed())
      || (($access = $this->ogAccess->userAccess($entity, 'subscribe without approval', $this->currentUser)) && $access->isAllowed())
    ) {
      // Generate subscribe link for user if we have access.
      $parameters = [
        'entity_type_id' => $entity->getEntityTypeId(),
        'group' => $entity->id(),
        'og_membership_type' => OgMembershipInterface::TYPE_DEFAULT,
      ];
      $url = Url::fromRoute('og.subscribe', $parameters);

      // Subscribe text for group page.
      $subscribe_text = $this->t('Hi @name, click @here if you would like to subscribe to this group called @label',
      [
        '@name' => $this->currentUser->getAccountName(),
        '@here' => Link::fromTextAndUrl('here', $url)->toString(),
        '@label' => $entity->label(),
      ]);
    }
    else {
      $subscribe_text = $this->t('This is a closed group. Only a group administrator can add you.');
    }

    $element = $this->buildElementNodeGroup(
      $entity->label(),
      $label,
      $this->buildProcessedText($entity, 'body'),
      $this->wrapHtmlTag($subscribe_text, 'h3'),
    );
    $build[] = $element;

    return $build;
  }

}
