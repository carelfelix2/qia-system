<?php

namespace App\Imports;

use App\Models\Equipment;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EquipmentImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip rows where nama_alat is empty or null
        if (empty($row['nama_alat'])) {
            return null;
        }

        // Skip rows where tipe_alat is empty or null
        if (empty($row['tipe_alat'])) {
            return null;
        }

        return new Equipment([
            'nama_alat' => $row['nama_alat'],
            'tipe_alat' => $row['tipe_alat'],
            'merk' => $row['merk'] ?? null,
            'part_number' => $row['part_number'] ?? null,
            'harga_retail' => $this->cleanPrice($row['harga_retail'] ?? null),
            'harga_inaproc' => $this->cleanPrice($row['harga_inaproc'] ?? null),
            'harga_sebelum_ppn' => $this->cleanPrice($row['harga_sebelum_ppn'] ?? null),
        ]);
    }

    private function cleanPrice($value)
    {
        if (empty($value)) {
            return null;
        }

        // Convert to string first
        $value = (string)$value;

        // Handle scientific notation properly
        if (preg_match('/^([+-]?\d*\.?\d+)E([+-]?\d+)$/', $value, $matches)) {
            $mantissa = $matches[1];
            $exponent = (int)$matches[2];
            // Use PHP's built-in float conversion for scientific notation
            $floatValue = (float)$value;
        } else {
            // Remove any non-numeric characters except decimal point
            $cleaned = preg_replace('/[^\d.]/', '', $value);

            // If the result is empty or just a dot, return null
            if (empty($cleaned) || $cleaned === '.') {
                return null;
            }

            $floatValue = (float)$cleaned;
        }

        // Cap at a reasonable maximum for decimal(15,2) - 12 digits before decimal
        $maxValue = 999999999999.99;

        if ($floatValue > $maxValue) {
            $floatValue = $maxValue;
        }

        return $floatValue;
    }
}
