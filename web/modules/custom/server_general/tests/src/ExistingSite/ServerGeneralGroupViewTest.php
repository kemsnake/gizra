<?php

namespace Drupal\Tests\server_general\ExistingSite;

use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\KernelTests\AssertContentTrait;
use Drupal\og\OgMembershipInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Test input formats.
 */
class ServerGeneralGroupViewTest extends ServerGeneralTestBase {

  use AssertContentTrait;
  /**
   * Test Full HTML input format.
   */
  public function testFullHtmlFormat() {
    // Create new group content.
    $group_node = $this->createNode(['type' => 'group', 'title' => 'Test Group']);
    $group_node->toUrl();

    // Create new regular user and login.
    $user = $this->createUser();
    $user->save();
    $this->drupalLogin($user);

    // Load group node page.
    $this->drupalGet($group_node->toUrl());
    $this->assertSession()->statusCodeEquals(Response::HTTP_OK);
    $this->assertSession()->elementTextEquals('css', 'h1.page-title', 'Test Group');

    // Generate subscribe link for group.
    $parameters = [
      'entity_type_id' => $group_node->getEntityTypeId(),
      'group' => $group_node->id(),
      'og_membership_type' => OgMembershipInterface::TYPE_DEFAULT,
    ];
    $url = Url::fromRoute('og.subscribe', $parameters);
    // Prepare group subscribe text.
    $subscribe_text = 'Hi ' . $user->getAccountName() . ', click ' .
      Link::fromTextAndUrl('here', $url)->toString() .
      ' if you would like to subscribe to this group called ' .
      $group_node->label();
    // Define selector and compare values.
    $css_selector = 'article.node--type-group .container-wide h3';
    $text_from_page = $this->getSession()->getPage()->findAll('css', $css_selector);
    $this->assertEquals($text_from_page[0]->getHtml(), $subscribe_text);
  }

}
