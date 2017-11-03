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

// Generate the sets of the responsive image styles.
// The first number is the number of columns, roughly equates to 120px/column.
// The second is the height variation.
$styleSets = [
    'set_col_12_banner' => [
        'label' => 'Col 12 Banner',
        'breakpoints' => [
            'lg' => '12|high',
            'md' => '8|high',
            'sm' => '4|high',
            'xs' => '4|high',
        ]
    ]
];

foreach ($styleSets as $styleSetName => $styleSet) {

  $responsiveImageSet = [
      'id' => $styleSetName,
      'label' => $styleSet['label'],
      'breakpoint_group' => 'baseline',
      'fallback_image_style' => '_original image_',
  ];


  foreach ($styleSet['breakpoints'] as $breakpoint => $data) {

    list($colWidth, $variationName) = explode('|', $data);

    $outputFile = $outputDir . 'responsive_image.styles.' . $styleSetName . '.yml';

    foreach ($resolutions as $resolutionName => $resolution) {
      $responsiveImageSet['image_style_mappings'][] = [
          'breakpoint_id' => 'baseline.' . $breakpoint,
          'multiplier' => $resolutionName,
          'image_mapping_type' => 'image_style',
          'image_mapping' => 'col_' . $colWidth . '_' . $variationName . '_' . $resolutionName,
      ];
    }
  }
  echo "Responsive Image Style: Updated " . $responsiveImageSet['label'] . "\n";
  file_put_contents($outputFile, \Symfony\Component\Yaml\Yaml::dump($responsiveImageSet, 4, 2));
}



