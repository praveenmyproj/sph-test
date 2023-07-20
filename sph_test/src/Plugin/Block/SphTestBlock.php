<?php

namespace Drupal\sph_test\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Render\Markup;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;


/**
 * Provides an Sph Test block.
 *
 * @Block(
 *   id = "sph_test_block",
 *   admin_label = @Translation("Sph Test Block"),
 *   category = @Translation("Sph Test")
 * )
 */
class SphTestBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    if ($node = \Drupal::routeMatch()->getParameter('node')) {
      if (!empty($node->field_app_purchase_link->uri)) {
        $qrCode = new QrCode($node->field_app_purchase_link->uri);
        $qrCode->setSize(300);
        $qrCode->setMargin(10);

        $qrCode->setWriterByName('png');
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH());
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
        $qrCode->setValidateResult(false);

        $qrCode->setRoundBlockSize(true, QrCode::ROUND_BLOCK_SIZE_MODE_MARGIN);
        $qrCode->setRoundBlockSize(true, QrCode::ROUND_BLOCK_SIZE_MODE_ENLARGE);
        $qrCode->setRoundBlockSize(true, QrCode::ROUND_BLOCK_SIZE_MODE_SHRINK);

        $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);

        return [
          '#type' => 'fieldset',
          '#title' => 'Scan here on Mobile',
          'content' => [
            '#type' => 'markup',
            '#markup' => Markup::create('<span>To Puchase this product on our app to avail exclusive app-only.</span><img src="data:image/png;base64,' . base64_encode($qrCode->writeString()) . '"')
          ],
          '#cache' => [
            'max-age' => 0,
          ]
        ];
      }
    }
    return [
      '#type' => 'markup',
      '#markup' => t('No Purchase Link provided'),
      '#cache' => [
        'max-age' => 0,
      ]
    ];
  }
}