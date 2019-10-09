<?php

namespace Drupal\burdastyle_headless\EventSubscriber;

use Drupal\Component\HttpFoundation\SecuredRedirectResponse;
use Drupal\Core\Routing\LocalRedirectResponse;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\EventSubscriber\RedirectResponseSubscriber;

/**
 * Override RedirectResponseSubscriber of Drupal Core for headless projects.
 */
class BurdaStyleHeadlessRedirectResponseSubscriber extends RedirectResponseSubscriber {

  /**
   * Allows manipulation of the response object when performing a redirect.
   *
   * Overridden checkRedirectUrl() also checks configured frontend domain
   * as a TrustedRedirectResponse.
   *
   * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
   *   The Event to process.
   */
  public function checkRedirectUrl(FilterResponseEvent $event) {
    $response = $event->getResponse();
    if ($response instanceof RedirectResponse) {
      $request = $event->getRequest();

      // Let the 'destination' query parameter override the redirect target.
      // If $response is already a SecuredRedirectResponse, it might reject the
      // new target as invalid, in which case proceed with the old target.
      $destination = $request->query->get('destination');
      if ($destination) {
        // The 'Location' HTTP header must always be absolute.
        $destination = $this->getDestinationAsAbsoluteUrl($destination, $request->getSchemeAndHttpHost());
        try {
          $response->setTargetUrl($destination);
        }
        catch (\InvalidArgumentException $e) {
        }
      }

      // Regardless of whether the target is the original one or the overridden
      // destination, ensure that all redirects are safe.
      if (!($response instanceof SecuredRedirectResponse)) {
        try {
          // TODO: Get frontend domain from burdastyle_headless settings.
          $trustedDomains = [
            'www.harpersbazaar.de',
            'www2.harpersbazaar.de',
            'dev.harpersbazaar.de',
            'staging.harpersbazaar.de',
            'feature1.harpersbazaar.de',
            'feature2.harpersbazaar.de',
            'bazaar.local',
          ];
          $host = parse_url($response->getTargetUrl(), PHP_URL_HOST);
          if (in_array($host, $trustedDomains)) {
            $safe_response = TrustedRedirectResponse::createFromRedirectResponse($response);
            $safe_response->setRequestContext($this->requestContext);
          }
          else {
            // SecuredRedirectResponse is an abstract class that requires a
            // concrete implementation. Default to LocalRedirectResponse, which
            // considers only redirects to within the same site as safe.
            $safe_response = LocalRedirectResponse::createFromRedirectResponse($response);
            $safe_response->setRequestContext($this->requestContext);
          }
        }
        catch (\InvalidArgumentException $e) {
          // If the above failed, it's because the redirect target wasn't
          // local. Do not follow that redirect. Display an error message
          // instead. We're already catching one exception, so trigger_error()
          // rather than throw another one.
          // We don't throw an exception, because this is a client error rather than a
          // server error.
          $message = 'Redirects to external URLs are not allowed by default, use \Drupal\Core\Routing\TrustedRedirectResponse for it.';
          trigger_error($message, E_USER_ERROR);
          $safe_response = new Response($message, 400);
        }
        $event->setResponse($safe_response);
      }
    }
  }

}
