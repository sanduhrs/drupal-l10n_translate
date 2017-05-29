<?php

namespace Drupal\l10n_translate\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController.
 *
 * @package Drupal\l10n_translate\Controller
 */
class DefaultController extends ControllerBase {

  /**
   * Translate.
   *
   * @return string
   *   Return Hello string.
   */
  public function translate(Request $request) {
    $string = $request->get('string');
    $source = $request->get('source');
    $target = $request->get('target');

    //TODO: Inject the service;
    /** @var \Drupal\l10n_translate\TranslationService $translation */
    $translation = \Drupal::service('l10n_translate.translate');
    $translation->setSource($source);
    $translation->setTarget($target);
    try {
      $data = $translation->translate($string);
      return new JsonResponse(
        $data,
        200
      );
    }
    catch (\Exception $e) {
      return new JsonResponse(
        [
          'error' => $e->getMessage(),
          'code' => $e->getCode(),
        ],
        $e->getCode()
      );
    }
  }

}
