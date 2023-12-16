<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Renderless;

class CuttingAlgorithm extends Component
{
    public $quantityItems = 10000;
    public $itemsLength = 18.5;
    public $itemsWidth = 5;
    public $gapBetweenItems = 0.2;
    public $minimalLengthSheet = 18;
    public $minimalWidthSheet = 11.6;
    public $maximalLengthSheet = 50.5;
    public $maximalWidthSheet = 34.5;
    public $sheetMarginTop = 1;
    public $sheetMarginBottom = 0.5;
    public $sheetMarginLeft = 0.5;
    public $sheetMarginRight = 0.5;
    public $planoLength = 109;
    public $planoWidth = 79;
    public $orientationSheet; //'landscape', 'Potrait'
    public $orientationPlano; //'landscape', 'Potrait', 'Auto Rotate'

    public $resultSheet = [];
    public $resultPlano = [];

    public $maxItemsOnPlano;

    private function calculateSheetDimensions($itemsLength, $itemsWidth, $gapBetweenItems, $minimalLengthSheet, $minimalWidthSheet, $maximalLengthSheet, $maximalWidthSheet, $sheetMarginTop, $sheetMarginBottom, $sheetMarginLeft, $sheetMarginRight, $orientationSheet)
    {
        if ($orientationSheet == 'landscape') {
            [$itemsLength, $itemsWidth] = [$itemsLength, $itemsWidth];
        } else {
            [$itemsLength, $itemsWidth] = [$itemsWidth, $itemsLength];
        }

        //landscape
        $max_col = floor($maximalLengthSheet / $itemsLength);
        $max_row = floor($maximalWidthSheet / $itemsWidth);

        // Create an empty array to store the results
        $results = [];

        // Initialize row counter
        $row_counter = 0;

        // Run the while loop until maximum row is reached
        while ($row_counter < $max_row) {
            // Increment row counter
            $row_counter++;

            // Initialize col counter
            $col_counter = 0;

            // Reset sheetLength and sheetWidth for each new row
            $sheetLength = 0;
            $sheetWidth = 0;

            // Run the while loop until maximum col is reached
            while ($col_counter < $max_col) {
                // Increment col counter
                $col_counter++;

                // Increment sheetLength and sheetWidth based on col and row counters
                $sheetLength = $itemsLength * $col_counter + $gapBetweenItems * ($col_counter - 1) + $sheetMarginLeft + $sheetMarginRight;
                $sheetWidth = $itemsWidth * $row_counter + $gapBetweenItems * ($row_counter - 1) + $sheetMarginTop + $sheetMarginBottom;

                if ($sheetLength >= $minimalLengthSheet && $sheetLength <= $maximalLengthSheet && ($sheetWidth >= $minimalWidthSheet && $sheetWidth <= $maximalWidthSheet)) {
                    $results[] = [
                        'col' => (int) $col_counter,
                        'row' => (int) $row_counter,
                        'sheetLength' => (float) $sheetLength,
                        'sheetWidth' => (float) $sheetWidth,
                        'wasteLength' => (float) $maximalLengthSheet - $sheetLength,
                        'wasteWidth' => (float) $maximalWidthSheet - $sheetWidth,
                        'orientationSheet' => $orientationSheet,
                        'itemsPerSheet' => (float) $col_counter * $row_counter,
                        'itemsLength' => (float) $itemsLength,
                        'itemsWidth' => (float) $itemsWidth,
                        'gapBetweenItems' => (float) $gapBetweenItems,
                        'sheetMarginTop' => (float) $sheetMarginTop,
                        'sheetMarginBottom' => (float) $sheetMarginBottom,
                        'sheetMarginLeft' => (float) $sheetMarginLeft,
                        'sheetMarginRight' => (float) $sheetMarginRight,
                    ];
                }
            }
        }

        $bestDimensionSheet = [];
        $minWaste = PHP_INT_MAX;

        // Iterate through $results to find the result with the smallest waste
        foreach ($results as $result) {
            $totalWaste = $result['wasteLength'] * $result['wasteWidth'];

            if ($totalWaste < $minWaste) {
                $minWaste = $totalWaste;
                $bestDimensionSheet = [
                    'col' => (int) $result['col'],
                    'row' => (int) $result['row'],
                    'sheetLength' => (float) $result['sheetLength'],
                    'sheetWidth' => (float) $result['sheetWidth'],
                    'wasteLength' => (float) $result['wasteLength'],
                    'wasteWidth' => (float) $result['wasteWidth'],
                    'orientationSheet' => (float) $result['orientationSheet'],
                    'itemsPerSheet' => (float) $result['itemsPerSheet'],
                    'itemsLength' => (float) $result['itemsLength'],
                    'itemsWidth' => (float) $result['itemsWidth'],
                    'gapBetweenItems' => (float) $result['gapBetweenItems'],
                    'sheetMarginTop' => (float) $result['sheetMarginTop'],
                    'sheetMarginBottom' => (float) $result['sheetMarginBottom'],
                    'sheetMarginLeft' => (float) $result['sheetMarginLeft'],
                    'sheetMarginRight' => (float) $result['sheetMarginRight'],
                ];
            }
        }

        $this->dispatch('createLayoutSetting', $bestDimensionSheet);

        return $bestDimensionSheet;
    }

    private function calculateAllSheetDimensions($itemsLength, $itemsWidth, $gapBetweenItems, $minimalLengthSheet, $minimalWidthSheet, $maximalLengthSheet, $maximalWidthSheet, $sheetMarginTop, $sheetMarginBottom, $sheetMarginLeft, $sheetMarginRight, $orientationSheet)
    {
        if ($orientationSheet == 'landscape') {
            [$itemsLength, $itemsWidth] = [$itemsLength, $itemsWidth];
        } else {
            [$itemsLength, $itemsWidth] = [$itemsWidth, $itemsLength];
        }

        //landscape
        $max_col = floor($maximalLengthSheet / $itemsLength);
        $max_row = floor($maximalWidthSheet / $itemsWidth);

        // Create an empty array to store the results
        $results = [];

        // Initialize row counter
        $row_counter = 0;

        // Run the while loop until maximum row is reached
        while ($row_counter < $max_row) {
            // Increment row counter
            $row_counter++;

            // Initialize col counter
            $col_counter = 0;

            // Reset sheetLength and sheetWidth for each new row
            $sheetLength = 0;
            $sheetWidth = 0;

            // Run the while loop until maximum col is reached
            while ($col_counter < $max_col) {
                // Increment col counter
                $col_counter++;

                // Increment sheetLength and sheetWidth based on col and row counters
                $sheetLength = $itemsLength * $col_counter + $gapBetweenItems * ($col_counter - 1) + $sheetMarginLeft + $sheetMarginRight;
                $sheetWidth = $itemsWidth * $row_counter + $gapBetweenItems * ($row_counter - 1) + $sheetMarginTop + $sheetMarginBottom;

                if ($sheetLength >= $minimalLengthSheet && $sheetLength <= $maximalLengthSheet && ($sheetWidth >= $minimalWidthSheet && $sheetWidth <= $maximalWidthSheet)) {
                    $results[] = [
                        'col' => (int) $col_counter,
                        'row' => (int) $row_counter,
                        'sheetLength' => (float) $sheetLength,
                        'sheetWidth' => (float) $sheetWidth,
                        'wasteLength' => (float) $maximalLengthSheet - $sheetLength,
                        'wasteWidth' => (float) $maximalWidthSheet - $sheetWidth,
                        'orientationSheet' => $orientationSheet,
                        'itemsPerSheet' => (int) $col_counter * (int) $row_counter,
                        'itemsLength' => (float) $itemsLength,
                        'itemsWidth' => (float) $itemsWidth,
                        'gapBetweenItems' => (float) $gapBetweenItems,
                        'sheetMarginTop' => (float) $sheetMarginTop,
                        'sheetMarginBottom' => (float) $sheetMarginBottom,
                        'sheetMarginLeft' => (float) $sheetMarginLeft,
                        'sheetMarginRight' => (float) $sheetMarginRight,
                    ];
                }
            }
        }

        return $results;
    }

    private function calculateNumSheetsInPlano($sheetLength, $sheetWidth, $itemsPerSheet, $planoLength, $planoWidth, $orientationPlano)
    {
        if ($orientationPlano == 'landscape') {
            [$sheetLength, $sheetWidth] = [$sheetLength, $sheetWidth];
        } else {
            [$sheetLength, $sheetWidth] = [$sheetWidth, $sheetLength];
        }

        $max_col = floor($planoLength / $sheetLength);
        $max_row = floor($planoWidth / $sheetWidth);

        $results = [];

        // Initialize row counter
        $row_counter = 0;

        // Run the while loop until maximum row is reached
        while ($row_counter < $max_row) {
            // Increment row counter
            $row_counter++;

            // Initialize col counter
            $col_counter = 0;

            // Reset sheetLength and sheetWidth for each new row
            $cutSheetLength = 0;
            $cutSheetWidth = 0;

            // Run the while loop until maximum col is reached
            while ($col_counter < $max_col) {
                // Increment col counter
                $col_counter++;

                // Increment sheetLength and sheetWidth based on col and row counters
                $cutSheetLength = $sheetLength * $col_counter;
                $cutSheetWidth = $sheetWidth * $row_counter;

                if ($cutSheetLength <= $planoLength && $cutSheetWidth <= $planoWidth) {
                    $results = [
                        'col' => (int) $col_counter,
                        'row' => (int) $row_counter,
                        'planoLength' => (float) $planoLength,
                        'planoWidth' => (float) $planoWidth,
                        'sheetLength' => (float) $sheetLength,
                        'sheetWidth' => (float) $sheetWidth,
                        'cutSheetLength' => (float) $cutSheetLength,
                        'cutSheetWidth' => (float) $cutSheetWidth,
                        'wasteCutLength' => (float) $planoLength - $cutSheetLength,
                        'wasteCutWidth' => (float) $planoWidth - $cutSheetWidth,
                        'orientationPlano' => (float) $orientationPlano,
                        'itemsPerPlano' => (int) $itemsPerSheet * ($col_counter * $row_counter),
                    ];
                }
            }
        }

        $this->dispatch('createLayoutBahan', $results);

        return $results;
    }

    private function calculateNumSheetsAutoRotateInPlano($resultAllSheet, $planoLength, $planoWidth, $orientationPlano)
    {
        $results = [];

        $sheetLength = $resultAllSheet['sheetLength'];
        $sheetWidth = $resultAllSheet['sheetWidth'];
        $itemsPerSheet = $resultAllSheet['itemsPerSheet'];

        if ($orientationPlano == 'landscape') {
            [$sheetLength, $sheetWidth] = [$sheetLength, $sheetWidth];
        } elseif ($orientationPlano == 'potrait') {
            [$sheetLength, $sheetWidth] = [$sheetWidth, $sheetLength];
        }

        $max_col = floor($planoLength / $sheetLength);
        $max_row = floor($planoWidth / $sheetWidth);

        // Initialize row counter
        $row_counter = 0;

        // Run the while loop until maximum row is reached
        while ($row_counter < $max_row) {
            // Increment row counter
            $row_counter++;

            // Initialize col counter
            $col_counter = 0;

            // Reset sheetLength and sheetWidth for each new row
            $cutSheetLength = 0;
            $cutSheetWidth = 0;

            // Run the while loop until maximum col is reached
            while ($col_counter < $max_col) {
                // Increment col counter
                $col_counter++;

                // Increment sheetLength and sheetWidth based on col and row counters
                $cutSheetLength = $sheetLength * $col_counter;
                $cutSheetWidth = $sheetWidth * $row_counter;

                if ($cutSheetLength <= $planoLength && $cutSheetWidth <= $planoWidth) {
                    $resultAllSheetPlano = [
                        'colSheet' => (int) $resultAllSheet['col'],
                        'rowSheet' => (int) $resultAllSheet['row'],
                        'col' => (int) $col_counter,
                        'row' => (int) $row_counter,
                        'planoLength' => (float) $planoLength,
                        'planoWidth' => (float) $planoWidth,
                        'sheetLength' => (float) $sheetLength,
                        'sheetWidth' => (float) $sheetWidth,
                        'cutSheetLength' => (float) $cutSheetLength,
                        'cutSheetWidth' => (float) $cutSheetWidth,
                        'wasteCutLength' => (float) $planoLength - $cutSheetLength,
                        'wasteCutWidth' => (float) $planoWidth - $cutSheetWidth,
                        'orientationSheet' => $resultAllSheet['orientationSheet'],
                        'orientationPlano' => $orientationPlano,
                        'itemsPerSheet' => (int) $resultAllSheet['itemsPerSheet'],
                        'itemsPerPlano' => (int) $resultAllSheet['itemsPerSheet'] * ((int) $col_counter * (int) $row_counter),
                    ];

                    // Inisialisasi nilai tambahan dengan null
                    $resultAllSheetPlano['planoLength_extra_1'] = null;
                    $resultAllSheetPlano['planoWidth_extra_1'] = null;
                    $resultAllSheetPlano['planoLength_extra_2'] = null;
                    $resultAllSheetPlano['planoWidth_extra_2'] = null;

                    // Logika penentuan nilai tambahan
                    if ($resultAllSheetPlano['wasteCutLength'] >= $resultAllSheetPlano['sheetWidth'] || $resultAllSheetPlano['sheetLength'] >= $this->minimalLengthSheet) {
                        $resultAllSheetPlano['planoLength_extra_1'] = $resultAllSheetPlano['wasteCutLength'];
                        $resultAllSheetPlano['planoWidth_extra_1'] = $resultAllSheetPlano['planoWidth'];

                        $resultAllSheetPlano['planoLength_extra_2'] = $resultAllSheetPlano['cutSheetLength'];
                        $resultAllSheetPlano['planoWidth_extra_2'] = $resultAllSheetPlano['wasteCutWidth'];
                    } elseif ($resultAllSheetPlano['wasteCutWidth'] >= $resultAllSheetPlano['sheetLength'] || $resultAllSheetPlano['sheetWidth'] >= $this->minimalWidthSheet) {
                        $resultAllSheetPlano['planoLength_extra_1'] = $resultAllSheetPlano['wasteCutLength'];
                        $resultAllSheetPlano['planoWidth_extra_1'] = $resultAllSheetPlano['cutSheetWidth'];

                        $resultAllSheetPlano['planoLength_extra_2'] = $resultAllSheetPlano['planoLength'];
                        $resultAllSheetPlano['planoWidth_extra_2'] = $resultAllSheetPlano['wasteCutWidth'];
                    }

                    $resultAllSheetPlano['col_extra_1'] = floor($resultAllSheetPlano['planoLength_extra_1'] / $resultAllSheetPlano['sheetWidth']);
                    $resultAllSheetPlano['row_extra_1'] = floor($resultAllSheetPlano['planoWidth_extra_1'] / $resultAllSheetPlano['sheetLength']);
                    $resultAllSheetPlano['item_per_plano_extra_1'] = $resultAllSheetPlano['itemsPerSheet'] * ((int) $resultAllSheetPlano['col_extra_1'] * (int) $resultAllSheetPlano['row_extra_1']);

                    $resultAllSheetPlano['col_extra_2'] = floor($resultAllSheetPlano['planoLength_extra_2'] / $resultAllSheetPlano['sheetWidth']);
                    $resultAllSheetPlano['row_extra_2'] = floor($resultAllSheetPlano['planoWidth_extra_2'] / $resultAllSheetPlano['sheetLength']);
                    $resultAllSheetPlano['item_per_plano_extra_2'] = $resultAllSheetPlano['itemsPerSheet'] * ((int) $resultAllSheetPlano['col_extra_2'] * (int) $resultAllSheetPlano['row_extra_2']);

                    $results[] = $resultAllSheetPlano;
                }
            }
        }

        $maxTotalItems = 0;
        $selectedDataPlano = null;
        $bestDimensionPlano = [];
        foreach ($results as $dataPlano) {
            $totalItems = $dataPlano['itemsPerPlano'] + $dataPlano['item_per_plano_extra_1'] + $dataPlano['item_per_plano_extra_2'];

            if ($totalItems > $maxTotalItems) {
                $bestDimensionPlano = [
                    'col' => (int) $dataPlano['col'],
                    'row' => (int) $dataPlano['row'],
                    'planoLength' => (float) $dataPlano['planoLength'],
                    'planoWidth' => (float) $dataPlano['planoWidth'],
                    'sheetLength' => (float) $dataPlano['sheetLength'],
                    'sheetWidth' => (float) $dataPlano['sheetWidth'],
                    'cutSheetLength' => (float) $dataPlano['cutSheetLength'],
                    'cutSheetWidth' => (float) $dataPlano['cutSheetWidth'],
                    'totalItems' => (int) $totalItems,

                    'planoLength_extra_1' => (int) $dataPlano['planoLength_extra_1'],
                    'planoWidth_extra_1' => (int) $dataPlano['planoWidth_extra_1'],
                    'col_extra_1' => (int) $dataPlano['col_extra_1'],
                    'row_extra_1' => (int) $dataPlano['row_extra_1'],
                    'item_per_plano_extra_1' => (int) $dataPlano['item_per_plano_extra_1'],

                    'planoLength_extra_2' => (int) $dataPlano['planoLength_extra_2'],
                    'planoWidth_extra_2' => (int) $dataPlano['planoWidth_extra_2'],
                    'col_extra_2' => (int) $dataPlano['col_extra_2'],
                    'row_extra_2' => (int) $dataPlano['row_extra_2'],
                    'item_per_plano_extra_2' => (int) $dataPlano['item_per_plano_extra_2'],
                ];
            }
        }

        $this->dispatch('createLayoutBahanAutoRotate', $bestDimensionPlano);

        return $bestDimensionPlano;
    }

    private function calculateNumSheetsAutoRotateSizeSheetInPlano($resultAllSheet, $planoLength, $planoWidth)
    {
        $results = [];

        foreach($resultAllSheet as $data){
          $sheetLength = $data['sheetLength'];
          $sheetWidth = $data['sheetWidth'];
          $itemsPerSheet = $data['itemsPerSheet'];

          if ($data['orientationSheet'] == 'landscape') {
              [$sheetLength, $sheetWidth] = [$sheetLength, $sheetWidth];
          } elseif ($data['orientationSheet'] == 'potrait') {
              [$sheetLength, $sheetWidth] = [$sheetWidth, $sheetLength];
          }

          $max_col = floor($planoLength / $sheetLength);
          $max_row = floor($planoWidth / $sheetWidth);

          // Initialize row counter
          $row_counter = 0;

          // Run the while loop until maximum row is reached
          while ($row_counter < $max_row) {
              // Increment row counter
              $row_counter++;

              // Initialize col counter
              $col_counter = 0;

              // Reset sheetLength and sheetWidth for each new row
              $cutSheetLength = 0;
              $cutSheetWidth = 0;

              // Run the while loop until maximum col is reached
              while ($col_counter < $max_col) {
                  // Increment col counter
                  $col_counter++;

                  // Increment sheetLength and sheetWidth based on col and row counters
                  $cutSheetLength = $sheetLength * $col_counter;
                  $cutSheetWidth = $sheetWidth * $row_counter;

                  if ($cutSheetLength <= $planoLength && $cutSheetWidth <= $planoWidth) {
                      $dataPlano = [
                          'colSheet' => (int) $data['col'],
                          'rowSheet' => (int) $data['row'],
                          'col' => (int) $col_counter,
                          'row' => (int) $row_counter,
                          'planoLength' => (float) $planoLength,
                          'planoWidth' => (float) $planoWidth,
                          'sheetLength' => (float) $sheetLength,
                          'sheetWidth' => (float) $sheetWidth,
                          'cutSheetLength' => (float) $cutSheetLength,
                          'cutSheetWidth' => (float) $cutSheetWidth,
                          'wasteCutLength' => (float) $planoLength - $cutSheetLength,
                          'wasteCutWidth' => (float) $planoWidth - $cutSheetWidth,
                          'orientationSheet' => $data['orientationSheet'],
                          'itemsPerSheet' => (int) $data['itemsPerSheet'],
                          'itemsPerPlano' => (int) $data['itemsPerSheet'] * ((int) $col_counter * (int) $row_counter),
                      ];

                      // Inisialisasi nilai tambahan dengan null
                      $dataPlano['planoLength_extra_1'] = null;
                      $dataPlano['planoWidth_extra_1'] = null;
                      $dataPlano['planoLength_extra_2'] = null;
                      $dataPlano['planoWidth_extra_2'] = null;

                      // Logika penentuan nilai tambahan
                      if ($dataPlano['wasteCutLength'] >= $dataPlano['sheetWidth'] || $dataPlano['sheetLength'] >= $this->minimalLengthSheet) {
                          $dataPlano['planoLength_extra_1'] = $dataPlano['wasteCutLength'];
                          $dataPlano['planoWidth_extra_1'] = $dataPlano['planoWidth'];

                          $dataPlano['planoLength_extra_2'] = $dataPlano['cutSheetLength'];
                          $dataPlano['planoWidth_extra_2'] = $dataPlano['wasteCutWidth'];
                      } elseif ($dataPlano['wasteCutWidth'] >= $dataPlano['sheetLength'] || $dataPlano['sheetWidth'] >= $this->minimalWidthSheet) {
                          $dataPlano['planoLength_extra_1'] = $dataPlano['wasteCutLength'];
                          $dataPlano['planoWidth_extra_1'] = $dataPlano['cutSheetWidth'];

                          $dataPlano['planoLength_extra_2'] = $dataPlano['planoLength'];
                          $dataPlano['planoWidth_extra_2'] = $dataPlano['wasteCutWidth'];
                      }

                      $dataPlano['col_extra_1'] = floor($dataPlano['planoLength_extra_1'] / $dataPlano['sheetWidth']);
                      $dataPlano['row_extra_1'] = floor($dataPlano['planoWidth_extra_1'] / $dataPlano['sheetLength']);
                      $dataPlano['item_per_plano_extra_1'] = $dataPlano['itemsPerSheet'] * ((int) $dataPlano['col_extra_1'] * (int) $dataPlano['row_extra_1']);
                      $dataPlano['waste_length_extra_1'] = $dataPlano['col_extra_1'] * ((int) $dataPlano['row_extra_1'] - (int) $dataPlano['planoLength_extra_1']);

                      $dataPlano['col_extra_2'] = floor($dataPlano['planoLength_extra_2'] / $dataPlano['sheetWidth']);
                      $dataPlano['row_extra_2'] = floor($dataPlano['planoWidth_extra_2'] / $dataPlano['sheetLength']);
                      $dataPlano['item_per_plano_extra_2'] = $dataPlano['itemsPerSheet'] * ((int) $dataPlano['col_extra_2'] * (int) $dataPlano['row_extra_2']);

                      $dataPlano['totalItems'] = $dataPlano['itemsPerPlano'] + ((int) $dataPlano['item_per_plano_extra_1'] + (int) $dataPlano['item_per_plano_extra_2']);

                      $results[] = $dataPlano;
                  }
              }
          }
        }
        dd($results);

        $maxTotalItems = 0;
        $bestDimensionPlano = [];

        foreach ($results as $dataPlano) {
            $totalItems = $dataPlano['totalItems'];

            // Perbarui jika totalItems lebih besar dari $maxTotalItems
            if ($totalItems > $maxTotalItems) {
                $maxTotalItems = $totalItems;

                $bestDimensionPlano = [
                    'col' => (int) $dataPlano['col'],
                    'row' => (int) $dataPlano['row'],
                    'planoLength' => (float) $dataPlano['planoLength'],
                    'planoWidth' => (float) $dataPlano['planoWidth'],
                    'sheetLength' => (float) $dataPlano['sheetLength'],
                    'sheetWidth' => (float) $dataPlano['sheetWidth'],
                    'cutSheetLength' => (float) $dataPlano['cutSheetLength'],
                    'cutSheetWidth' => (float) $dataPlano['cutSheetWidth'],
                    'totalItems' => (int) $totalItems,

                    'planoLength_extra_1' => (int) $dataPlano['planoLength_extra_1'],
                    'planoWidth_extra_1' => (int) $dataPlano['planoWidth_extra_1'],
                    'col_extra_1' => (int) $dataPlano['col_extra_1'],
                    'row_extra_1' => (int) $dataPlano['row_extra_1'],
                    'item_per_plano_extra_1' => (int) $dataPlano['item_per_plano_extra_1'],

                    'planoLength_extra_2' => (int) $dataPlano['planoLength_extra_2'],
                    'planoWidth_extra_2' => (int) $dataPlano['planoWidth_extra_2'],
                    'col_extra_2' => (int) $dataPlano['col_extra_2'],
                    'row_extra_2' => (int) $dataPlano['row_extra_2'],
                    'item_per_plano_extra_2' => (int) $dataPlano['item_per_plano_extra_2'],
                ];
            }
        }



        // $this->dispatch('createLayoutBahanAutoRotate', $bestDimensionPlano);

        return $results;
    }

    #[Renderless]
    public function calc()
    {
        if ($this->orientationSheet == 'landscape') {
            $resultSheetLandscape = $this->calculateSheetDimensions($this->itemsLength, $this->itemsWidth, $this->gapBetweenItems, $this->minimalLengthSheet, $this->minimalWidthSheet, $this->maximalLengthSheet, $this->maximalWidthSheet, $this->sheetMarginTop, $this->sheetMarginBottom, $this->sheetMarginLeft, $this->sheetMarginRight, $this->orientationSheet);

            if ($this->orientationPlano == 'landscape') {
                $resultPlanoLandscape = $this->calculateNumSheetsInPlano($resultSheetLandscape['sheetLength'], $resultSheetLandscape['sheetWidth'], $resultSheetLandscape['itemsPerSheet'], $this->planoLength, $this->planoWidth, $this->orientationPlano);
            } elseif ($this->orientationPlano == 'potrait') {
                $resultPlanoPotrait = $this->calculateNumSheetsInPlano($resultSheetLandscape['sheetLength'], $resultSheetLandscape['sheetWidth'], $resultSheetLandscape['itemsPerSheet'], $this->planoLength, $this->planoWidth, $this->orientationPlano);
            } else {
                $resultAllSheetLandscape = $this->calculateSheetDimensions($this->itemsLength, $this->itemsWidth, $this->gapBetweenItems, $this->minimalLengthSheet, $this->minimalWidthSheet, $this->maximalLengthSheet, $this->maximalWidthSheet, $this->sheetMarginTop, $this->sheetMarginBottom, $this->sheetMarginLeft, $this->sheetMarginRight, $this->orientationSheet);
                $resultAllPlanoLandscape = $this->calculateNumSheetsAutoRotateInPlano($resultAllSheetLandscape, $this->planoLength, $this->planoWidth, 'landscape');
            }
        } elseif ($this->orientationSheet == 'potrait') {
            $resultSheetPotrait = $this->calculateSheetDimensions($this->itemsLength, $this->itemsWidth, $this->gapBetweenItems, $this->minimalLengthSheet, $this->minimalWidthSheet, $this->maximalLengthSheet, $this->maximalWidthSheet, $this->sheetMarginTop, $this->sheetMarginBottom, $this->sheetMarginLeft, $this->sheetMarginRight, $this->orientationSheet);

            if ($this->orientationPlano == 'landscape') {
                $resultPlanoLandscape = $this->calculateNumSheetsInPlano($resultSheetPotrait['sheetLength'], $resultSheetPotrait['sheetWidth'], $resultSheetPotrait['itemsPerSheet'], $this->planoLength, $this->planoWidth, $this->orientationPlano);
            } elseif ($this->orientationPlano == 'potrait') {
                $resultPlanoPotrait = $this->calculateNumSheetsInPlano($resultSheetPotrait['sheetLength'], $resultSheetPotrait['sheetWidth'], $resultSheetPotrait['itemsPerSheet'], $this->planoLength, $this->planoWidth, $this->orientationPlano);
            } else {
                $resultAllSheetPotrait = $this->calculateSheetDimensions($this->itemsLength, $this->itemsWidth, $this->gapBetweenItems, $this->minimalLengthSheet, $this->minimalWidthSheet, $this->maximalLengthSheet, $this->maximalWidthSheet, $this->sheetMarginTop, $this->sheetMarginBottom, $this->sheetMarginLeft, $this->sheetMarginRight, $this->orientationSheet);
                $resultAllPlanoPotrait = $this->calculateNumSheetsAutoRotateInPlano($resultAllSheetPotrait, $this->planoLength, $this->planoWidth, 'potrait');
            }
        } else {
            $resultSheetLandscape = $this->calculateAllSheetDimensions($this->itemsLength, $this->itemsWidth, $this->gapBetweenItems, $this->minimalLengthSheet, $this->minimalWidthSheet, $this->maximalLengthSheet, $this->maximalWidthSheet, $this->sheetMarginTop, $this->sheetMarginBottom, $this->sheetMarginLeft, $this->sheetMarginRight, 'landscape');
            $resultSheetPotrait = $this->calculateAllSheetDimensions($this->itemsLength, $this->itemsWidth, $this->gapBetweenItems, $this->minimalLengthSheet, $this->minimalWidthSheet, $this->maximalLengthSheet, $this->maximalWidthSheet, $this->sheetMarginTop, $this->sheetMarginBottom, $this->sheetMarginLeft, $this->sheetMarginRight, 'potrait');
            $resultAllSheet = array_merge($resultSheetLandscape, $resultSheetPotrait);

            $resultAllPlanoPotrait = $this->calculateNumSheetsAutoRotateSizeSheetInPlano($resultAllSheet, $this->planoLength, $this->planoWidth);
        }
    }
    public function render()
    {
        return view('livewire.cutting-algorithm');
    }
}
