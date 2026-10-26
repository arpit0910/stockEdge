<?php

namespace App\Support;

class SalesCrm
{
    /** @return array<string, string> */
    public static function pipelineOptions(): array
    {
        return ['duplicate' => 'Duplicate', 'converted' => 'Converted', 'voicemail' => 'Voice mail', 'interested' => 'Interested', 'not_interested' => 'Not interested', 'opportunity' => 'Opportunity', 'new' => 'New lead'];
    }

    public static function statusLabel(string $status): string
    {
        return self::pipelineOptions()[$status] ?? ($status === 'dnd' ? 'Do not disturb' : ucfirst($status));
    }

    public static function maskPhone(?string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone ?? '');
        $length = strlen($digits);

        return $length > 4 ? substr($digits, 0, 2).str_repeat('*', $length - 4).substr($digits, -2) : str_repeat('*', $length);
    }
}
