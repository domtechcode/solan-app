<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Renderless;

class CuttingAlgorithm extends Component
{
    public $quantityItems = 10000;
    public $itemsLength;
    public $itemsWidth;
    public $gapBetweenItems = 0.2;
    public $minimalLengthSheet = 18;
    public $minimalWidthSheet = 11.6;
    public $maximalLengthSheet = 50.5;
    public $maximalWidthSheet = 34.5;
    public $sheetMarginTop = 1;
    public $sheetMarginBottom = 0.5;
    public $sheetMarginLeft = 0.5;
    public $sheetMarginRight = 0.5;
    public $planoLength;
    public $planoWidth;
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
                        'col' => $col_counter,
                        'row' => $row_counter,
                        'sheetLength' => $sheetLength,
                        'sheetWidth' => $sheetWidth,
                        'wasteLength' => $maximalLengthSheet - $sheetLength,
                        'wasteWidth' => $maximalWidthSheet - $sheetWidth,
                        'orientationSheet' => $orientationSheet,
                        'itemsPerSheet' => $col_counter * $row_counter,
                        'itemsLength' => $itemsLength,
                        'itemsWidth' => $itemsWidth,
                        'gapBetweenItems' => $gapBetweenItems,
                        'sheetMarginTop' => $sheetMarginTop,
                        'sheetMarginBottom' => $sheetMarginBottom,
                        'sheetMarginLeft' => $sheetMarginLeft,
                        'sheetMarginRight' => $sheetMarginRight,
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
                    'col' => (int)$result['col'],
                    'row' => (int)$result['row'],
                    'sheetLength' => (float)$result['sheetLength'],
                    'sheetWidth' => (float)$result['sheetWidth'],
                    'wasteLength' => (float)$result['wasteLength'],
                    'wasteWidth' => (float)$result['wasteWidth'],
                    'orientationSheet' => (float)$result['orientationSheet'],
                    'itemsPerSheet' => (float)$result['itemsPerSheet'],
                    'itemsLength' => (float)$result['itemsLength'],
                    'itemsWidth' => (float)$result['itemsWidth'],
                    'gapBetweenItems' => (float)$result['gapBetweenItems'],
                    'sheetMarginTop' => (float)$result['sheetMarginTop'],
                    'sheetMarginBottom' => (float)$result['sheetMarginBottom'],
                    'sheetMarginLeft' => (float)$result['sheetMarginLeft'],
                    'sheetMarginRight' => (float)$result['sheetMarginRight'],
                ];
            }
        }

        $this->dispatch('createLayoutSetting', $bestDimensionSheet);

        return $bestDimensionSheet;
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
                        'col' => (int)$col_counter,
                        'row' => (int)$row_counter,
                        'planoLength' => (float)$planoLength,
                        'planoWidth' => (float)$planoWidth,
                        'sheetLength' => (float)$sheetLength,
                        'sheetWidth' => (float)$sheetWidth,
                        'cutSheetLength' => (float)$cutSheetLength,
                        'cutSheetWidth' => (float)$cutSheetWidth,
                        'wasteCutLength' => (float)$planoLength - $cutSheetLength,
                        'wasteCutWidth' => (float)$planoWidth - $cutSheetWidth,
                        'orientationPlano' => (float)$orientationPlano,
                        'itemsPerPlano' => (int)$itemsPerSheet * ($col_counter * $row_counter),
                    ];
                }
            }
        }

        $this->dispatch('createLayoutBahan', $results);

        return $results;
    }

    // private function calculateNumSheetsInPlano($resultSheet, $orientationPlano)
    // {
    //     $minimumWaste = null;
    //     $bestDimensionSheet = [];

    //     foreach ($resultSheet as $solution) {
    //         $waste = $solution['wasteLength'] * $solution['wasteWidth'];

    //         if (($minimumWaste === null || $waste < $minimumWaste) && $solution['orientationSheet'] == $this->orientationSheet) {
    //             $minimumWaste = $waste;
    //             $bestDimensionSheet = [
    //                 'col' => $solution['col'],
    //                 'row' => $solution['row'],
    //                 'sheetLength' => $solution['sheetLength'],
    //                 'sheetWidth' => $solution['sheetWidth'],
    //                 'wasteLength' => $solution['wasteLength'],
    //                 'wasteWidth' => $solution['wasteWidth'],
    //                 'itemsLength' => $solution['itemsLength'],
    //                 'itemsWidth' => $solution['itemsWidth'],
    //                 'gapBetweenItems' => $solution['gapBetweenItems'],
    //                 'sheetMarginTop' => $solution['sheetMarginTop'],
    //                 'sheetMarginBottom' => $solution['sheetMarginBottom'],
    //                 'sheetMarginLeft' => $solution['sheetMarginLeft'],
    //                 'sheetMarginRight' => $solution['sheetMarginRight'],
    //                 'orientationSheet' => $solution['orientationSheet'],
    //                 'itemsPerSheet' => $solution['itemsPerSheet'],
    //             ];
    //         }
    //     }

    //     $sheetLength = $bestDimensionSheet['sheetLength'];
    //     $sheetWidth = $bestDimensionSheet['sheetWidth'];

    //     if ($orientationPlano == 'landscape') {
    //         [$sheetLength, $sheetWidth] = [$sheetLength, $sheetWidth];
    //     } elseif ($orientationPlano == 'potrait') {
    //         [$sheetLength, $sheetWidth] = [$sheetWidth, $sheetLength];
    //     } else {
    //         //auto rotation
    //     }

    //     $this->maxItemsOnPlano = floor(($this->planoLength * $this->planoWidth) / ($this->itemsLength * $this->itemsWidth));

    //     $max_col = floor($this->planoLength / $sheetLength);
    //     $max_row = floor($this->planoWidth / $sheetWidth);

    //     $results = [];

    //     // Initialize row counter
    //     $row_counter = 0;

    //     // Run the while loop until maximum row is reached
    //     while ($row_counter < $max_row) {
    //         // Increment row counter
    //         $row_counter++;

    //         // Initialize col counter
    //         $col_counter = 0;

    //         // Reset sheetLength and sheetWidth for each new row
    //         $cutSheetLength = 0;
    //         $cutSheetWidth = 0;

    //         // Run the while loop until maximum col is reached
    //         while ($col_counter < $max_col) {
    //             // Increment col counter
    //             $col_counter++;

    //             // Increment sheetLength and sheetWidth based on col and row counters
    //             $cutSheetLength = $sheetLength * $col_counter;
    //             $cutSheetWidth = $sheetWidth * $row_counter;

    //             if ($sheetLength <= $this->planoLength && $sheetWidth <= $this->planoWidth) {
    //                 $data = [
    //                     'col' => $col_counter,
    //                     'row' => $row_counter,
    //                     'sheetLength' => $sheetLength,
    //                     'sheetWidth' => $sheetWidth,
    //                     'cutSheetLength' => $cutSheetLength,
    //                     'cutSheetWidth' => $cutSheetWidth,
    //                     'wasteCutLength' => $this->planoLength - $cutSheetLength,
    //                     'wasteCutWidth' => $this->planoWidth - $cutSheetWidth,
    //                     'orientationPlano' => $orientationPlano,
    //                     'itemsPerPlano' => $bestDimensionSheet['itemsPerSheet'] * ($col_counter * $row_counter),
    //                 ];

    //                 if ($this->planoLength - $cutSheetLength >= $this->minimalLengthSheet) {
    //                     $data['wasteLengthPlano_1'] = $this->planoLength - $cutSheetLength;
    //                     $data['wasteWidthPlano_1'] = $this->planoWidth;

    //                     foreach ($resultSheet as $sheet) {
    //                         if ($sheet['sheetLength'] <= $data['wasteLengthPlano_1'] && $sheet['sheetWidth'] <= $this->planoWidth && $bestDimensionSheet['orientationSheet'] == $this->orientationSheet) {
    //                             $data['extraLengthSheet_1'] = $sheet['sheetLength'];
    //                             $data['orientationSheet'] = $sheet['orientationSheet'];
    //                         } else {
    //                             $data['extraLengthSheet_1'] = null;
    //                             $data['orientationSheet'] = null;
    //                         }

    //                         if ($sheet['sheetWidth'] <= $this->planoLength && $sheet['sheetWidth'] <= $data['wasteWidthPlano_1'] && $bestDimensionSheet['orientationSheet'] == $this->orientationSheet) {
    //                             $data['extraWidthSheet_1'] = $sheet['sheetWidth'];
    //                         } else {
    //                             $data['extraWidthSheet_1'] = null;
    //                         }
    //                     }
    //                 } else {
    //                     $data['wasteLengthPlano_1'] = null;
    //                     $data['wasteWidthPlano_1'] = null;
    //                 }

    //                 if ($this->planoWidth - $cutSheetWidth >= $this->minimalWidthSheet) {
    //                     $data['wasteLengthPlano_2'] = $this->planoLength - $data['wasteLengthPlano_1'];
    //                     $data['wasteWidthPlano_2'] = $this->planoWidth - $cutSheetWidth;
    //                 } else {
    //                     $data['wasteLengthPlano_2'] = null;
    //                     $data['wasteWidthPlano_2'] = null;
    //                 }

    //                 $results[] = $data;
    //             }
    //         }
    //     }

    //     return $results;
    // }
    #[Renderless]
    public function calc()
    {
        if ($this->orientationSheet == 'potrait' || $this->orientationSheet == 'landscape') {
            $resultSheet = $this->calculateSheetDimensions($this->itemsLength, $this->itemsWidth, $this->gapBetweenItems, $this->minimalLengthSheet, $this->minimalWidthSheet, $this->maximalLengthSheet, $this->maximalWidthSheet, $this->sheetMarginTop, $this->sheetMarginBottom, $this->sheetMarginLeft, $this->sheetMarginRight, $this->orientationSheet);
            if ($this->orientationPlano == 'potrait' || $this->orientationPlano == 'landscape') {
              $resultPlano = $this->calculateNumSheetsInPlano($resultSheet['sheetLength'], $resultSheet['sheetWidth'], $resultSheet['itemsPerSheet'], $this->planoLength, $this->planoWidth, $this->orientationPlano);
            }else{
              $resultPlano = $this->calculateNumSheetsInPlano($resultSheet['sheetLength'], $resultSheet['sheetWidth'], $resultSheet['itemsPerSheet'], $this->planoLength, $this->planoWidth, $this->orientationPlano);
            }

          } else {
            $resultSheet = $this->calculateSheetDimensions($this->itemsLength, $this->itemsWidth, $this->gapBetweenItems, $this->minimalLengthSheet, $this->minimalWidthSheet, $this->maximalLengthSheet, $this->maximalWidthSheet, $this->sheetMarginTop, $this->sheetMarginBottom, $this->sheetMarginLeft, $this->sheetMarginRight, $this->orientationSheet);
        }
        // $this->skipRender();

        // $allResultSheet = array_merge($resultSheetPotrait, $resultSheetLandscape);
        // $this->resultSheet = $allResultSheet;

        // $resultPlanoPotrait = $this->calculateNumSheetsInPlano($allResultSheet, 'potrait');
        // $resultPlanoLandscape = $this->calculateNumSheetsInPlano($allResultSheet, 'landscape');
        // $allResultPlano = array_merge($resultPlanoPotrait, $resultPlanoLandscape);
        // $this->resultPlano = $allResultPlano;
        // dd($allResultSheet);
    }
    public function render()
    {
        return view('livewire.cutting-algorithm');
    }
}
