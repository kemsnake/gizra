<?php

namespace Drupal\server_general;

/**
 * Helper method for building the Node news element.
 */
trait ElementNodeGroupTrait {

  use ElementWrapTrait;
  use EntityDateTrait;
  use InnerElementLayoutTrait;
  use LineSeparatorTrait;
  use LinkTrait;
  use ElementLayoutTrait;
  use SocialShareTrait;
  use TagTrait;
  use TitleAndLabelsTrait;

  /**
   * Build the Node news element.
   *
   * @param string $title
   *   The node title.
   * @param string $label
   *   The label (e.g. `News`).
   * @param array $body
   *   The body render array.
   * @param array $subscribe_text
   *   Subscribe text.
   *
   * @return array
   *   The render array.
   *
   * @throws \IntlException
   */
  protected function buildElementNodeGroup(string $title, string $label, array $body, array $subscribe_text): array {
    $elements = [];

    // Header.
    $element = $this->buildHeader(
      $title,
      $label,
    );
    $elements[] = $this->wrapContainerWide($element);

    // Main content and sidebar.
    $element = $this->buildMainAndSidebar(
      $subscribe_text,
      $this->wrapProseText($body)
    );
    $elements[] = $this->wrapContainerWide($element);

    $elements = $this->wrapContainerVerticalSpacingBig($elements);
    return $this->wrapContainerBottomPadding($elements);
  }

  /**
   * Build the header.
   *
   * @param string $title
   *   The node title.
   * @param string $label
   *   The label (e.g. `News`).
   *
   * @return array
   *   Render array.
   *
   * @throws \IntlException
   */
  private function buildHeader(string $title, string $label): array {
    $elements = [];

    $elements[] = $this->buildPageTitle($title);

    // Show the node type as a label.
    $elements[] = $this->buildLabelsFromText([$label]);

    return $this->wrapContainerMaxWidth($elements, '3xl');
  }

  /**
   * Build the Main content and the sidebar.
   *
   * @param array $subscribe_text
   *   Subscribe text.
   * @param array $body
   *
   * @return array
   *   Render array
   */
  private function buildMainAndSidebar(array $subscribe_text, array $body): array {
    $main_elements = [];
    $main_elements[] = $body;
    $main_elements[] = $subscribe_text;

    return $this->buildElementLayoutMainAndSidebar($this->wrapContainerVerticalSpacingBig($main_elements));
  }

}
