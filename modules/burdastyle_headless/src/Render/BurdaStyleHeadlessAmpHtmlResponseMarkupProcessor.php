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
    $frontend_base_url = $this->configFactory->get('burdastyle_headless.settings')->get('frontend_base_url');

    if (!empty($frontend_base_url)) {
      $this->ampContent = $response->getContent();

      /**
       * Workaround to provide correct frontend and backend URLs on AMP.
       *
       * - Replace absolute backend URLs to frontend URLs (1,2).
       * - Replace relative image URLs to absolute backend URLs (3,5,6).
       * - Replace frontend image URLs to backend image URLs (4).
       */
      $sources = [
        $base_secure_url,
        $base_insecure_url,
        'src="/sites/default/files/styles',
        $frontend_base_url . '/sites/default/files/styles',
        'href="/themes/custom',
        'src="/themes/custom',
      ];
      $replacements = [
        $frontend_base_url,
        $frontend_base_url,
        'src="' . $base_secure_url . '/sites/default/files/styles',
        $base_secure_url . '/sites/default/files/styles',
        'href="' . $base_secure_url . '/themes/custom',
        'src="' . $base_secure_url . '/themes/custom',
      ];
      $response->setContent(str_replace($sources, $replacements, $this->ampContent));
    }
    return $response;
  }

}
