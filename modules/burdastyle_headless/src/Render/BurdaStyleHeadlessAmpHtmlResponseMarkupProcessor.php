<?php

/**
 * @file
 * Contains \Drupal\burdastyle_headless\Render\BurdaStyleHeadlessAmpHtmlResponseMarkupProcessor.
 */

namespace Drupal\burdastyle_headless\Render;

use Drupal\amp\Render\AmpHtmlResponseMarkupProcessor;
use Drupal\Core\Render\HtmlResponse;

/**
 * Processes markup of HTML responses.
 */
class BurdaStyleHeadlessAmpHtmlResponseMarkupProcessor extends AmpHtmlResponseMarkupProcessor {

  /**
   * Processes the content of a response into AMP html.
   *
   * @param \Drupal\Core\Render\HtmlResponse $response
   *   The response to process.
   *
   * @return \Drupal\Core\Render\HtmlResponse|\Symfony\Component\HttpFoundation\Response
   *   The processed response, with the content updated to amp markup.
   */
  public function processMarkupToAmp(HtmlResponse $response) {
    global $base_insecure_url, $base_secure_url;

    $response = parent::processMarkupToAmp($response);
    if ($frontend_base_url = $this->configFactory->get('burdastyle_headless.settings')->get('frontend_base_url')) {
      $this->ampContent = $response->getContent();
      $response->setContent(str_replace([$base_secure_url, $base_insecure_url], $frontend_base_url, $this->ampContent));
    }
    return $response;
  }

}
