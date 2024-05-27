<?php

// app/Imports/BingoCardsImport.php

namespace App\Imports;

use App\Models\BingoCard;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BingoCardsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new BingoCard([
            'card_data' => json_encode([
                [$row['b1'], $row['i1'], $row['n1'], $row['g1'], $row['o1']],
                [$row['b2'], $row['i2'], $row['n2'], $row['g2'], $row['o2']],
                [$row['b3'], $row['i3'], 'FREE', $row['g3'], $row['o3']],
                [$row['b4'], $row['i4'], $row['n4'], $row['g4'], $row['o4']],
                [$row['b5'], $row['i5'], $row['n5'], $row['g5'], $row['o5']],
            ]),
        ]);
    }
}
