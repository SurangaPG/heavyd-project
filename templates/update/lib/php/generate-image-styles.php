#!/usr/bin/env php

<?php

/**
 * Very minimal helper file to generate a number of image styles.
 */

require_once dirname(__DIR__) . "/vendor/autoload.php";

$standardWidth = 120;

$imageStyle = [];

$variations = [
  'very_low' => 0.5,
  'low' => 0.75,
  'square' => 1,
  'high' => 1.5,
  'very_high' => 2,
];

$resolutions = [
  '3x' => 3,
  '2x' => 2,
  '1x' => 1,
];

$colWidths = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

$uuid = new \Drupal\Component\Uuid\Php();
$outputDir = dirname(__DIR__) . '/etc/site/default/config/';

foreach ($variations as $variationName => $variation) {
  foreach ($resolutions as $resolutionName => $resolution) {
    foreach ($colWidths as $colWidth) {

      $width = (int) floor($colWidth * $standardWidth * $resolution);
      $height = (int) floor($width * $variation);

      $effectUuid = $uuid->generate();
      $cropUuid = $uuid->generate();
      $machineName = 'col_' . $colWidth . '_' . $variationName . '_' . $resolutionName;
      $label = 'Col ' . $colWidth . ' ' . str_replace('_', ' ', $variationName);

      $outputFile = $outputDir . 'image.style.' . $machineName . '.yml';
      $cropOutputFile = $outputDir . 'crop.type.' . $machineName . '.yml';

      $imageStyle = [
        'label' => $label . ' (' . $width . 'x' . $height . '|' . $resolutionName . ')',
        'name' => $machineName,
        'effects' => [
          $cropUuid => [
            'id' => 'crop_crop',
            'uuid' => $cropUuid,
            'weight' => -10,
            'data' => [
              'crop_type' => $machineName
            ]
          ],
          $effectUuid => [
            'uuid' => $effectUuid,
            'id' => 'image_scale_and_crop',
            'weight' => 1,
            'data' => [
              'width' => $width,
              'height' => $height,
            ]
          ]
        ],
      ];

      echo "Image Style: Updated " . $imageStyle['label'] . "\n";
      file_put_contents($outputFile, \Symfony\Component\Yaml\Yaml::dump($imageStyle, 4, 2));

      // Generate a manual crop style matching this one.
      $crop = [
          'label' => $label . ' (' . $resolutionName . ')',
          'id' => $machineName,
        // @TODO Use a clean aspect ratio here.
          'aspect_ratio' => $width . ':' . $height,
          'soft_limit_width' => $width,
          'soft_limit_height' => $height,
      ];

      echo "Crop Style: Updated " . $crop['label'] . "\n";
      file_put_contents($cropOutputFile, \Symfony\Component\Yaml\Yaml::dump($crop, 4, 2));

    }
  }
}

// Generate the sets of the responsive image styles.
foreach ($variations as $variationName => $variation) {
  for ($colWidth = 1; $colWidth <= 12; $colWidth++) {

    $outputFile = $outputDir . 'responsive_image.styles.col_' . $colWidth . '_' . $variationName . '.yml';
    $responsiveImageSet = [
      'id' => 'col_' . $colWidth . '_' . $variationName,
      'label' => 'Col ' . $colWidth . ' ' . str_replace('_', ' ', $variationName),
      'image_style_mappings' => [
        [
          'breakpoint_id' => 'baseline.lg',
          'multiplier' => '1x',
          'image_mapping_type' => 'image_style',
          'image_mapping' => 'col_' . $colWidth . '_' . $variationName . '_x1'
        ],
        [
            'breakpoint_id' => 'baseline.lg',
            'multiplier' => '2x',
            'image_mapping_type' => 'image_style',
            'image_mapping' => 'col_' . $colWidth . '_' . $variationName . '_x2'
        ],
        [
            'breakpoint_id' => 'baseline.lg',
            'multiplier' => '3x',
            'image_mapping_type' => 'image_style',
            'image_mapping' => 'col_' . $colWidth . '_' . $variationName . '_x3'
        ]
      ],
      'breakpoint_group' => 'baseline',
      'fallback_image_style' => '_original image_',
    ];

    echo "Responsive Image Style: Updated " . $responsiveImageSet['label'] . "\n";
    file_put_contents($outputFile, \Symfony\Component\Yaml\Yaml::dump($responsiveImageSet, 4, 2));
  }
}



