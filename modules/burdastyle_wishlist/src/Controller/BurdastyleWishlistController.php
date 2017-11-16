<?php

namespace Drupal\burdastyle_wishlist\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Render\Markup;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class BurdastyleWishlistController extends ControllerBase
{
  public function show()
  {
    $build = array(
      '#type' => 'markup',
      '#markup' => Markup::create('<div id="wishlist-page"></div>'),
    );
    return $build;
  }


  public function loadProducts(Request $request)
  {
    $productIds = [];
    $products = [];
    $wishlist = json_decode($request->get('wishlist'), true);
    foreach ($wishlist as $wishlistItem) {
      $productIds[] = $wishlistItem['productId'];
    }

    if (false === empty($productIds)) {
      $query = Database::getConnection()
        ->select('advertising_product')
        ->fields('advertising_product')
        ->condition('product_id', $productIds, 'IN')
        ->execute();

      foreach ($query->fetchAll() as $product) {
        $build = [
          '#theme' => 'burdastyle_wishlist_item',
          '#product' => $product,
        ];
        $products[] = [
          'productId' => $product->product_id,
          'markup' => \Drupal::service('renderer')->renderPlain($build),
        ];
      }
    }


    header('Content-type: application/json');
    return new JsonResponse([
      'products' => $products,
    ]);
  }
}