<?php

/**
 * @file
 * Contains \Drupal\burdastyle_headless\EventSubscriber\BurdaStyleHeadlessRedirectResponseSubscriber.
 */

namespace Drupal\burdastyle_headless\EventSubscriber;

use Drupal\Component\HttpFoundation\SecuredRedirectResponse;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Routing\LocalRedirectResponse;
use Drupal\Core\Routing\RequestContext;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\Core\Utility\UnroutedUrlAssemblerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\EventSubscriber\RedirectResponseSubscriber;

/**
 * Override RedirectResponseSubscriber of Drupal Core for headless projects.
 *
 * @package Drupal\burdastyle_headless\EventSubscriber
 */
class BurdaStyleHeadlessRedirectResponseSubscriber extends RedirectResponseSubscriber implements EventSubscriberInterface {

  /**
   * Config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;

  /**
   * Constructs a RedirectResponseSubscriber object.
   *
   * @param \Drupal\Core\Utility\UnroutedUrlAssemblerInterface $url_assembler
   *   The unrouted URL assembler service.
   * @param \Drupal\Core\Routing\RequestContext $request_context
   *   The request context.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(UnroutedUrlAssemblerInterface $url_assembler, RequestContext $request_context, ConfigFactoryInterface $config_factory) {
    $this->unroutedUrlAssembler = $url_assembler;
    $this->requestContext = $request_context;
    $this->config = $config_factory->get('burdastyle_headless.settings');
  }

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
          $host = parse_url($response->getTargetUrl(), PHP_URL_HOST);
          if (strpos($this->config->get('frontend_base_url'), $host) !== FALSE) {
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
