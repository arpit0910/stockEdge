<?php

namespace App\Support;

use InvalidArgumentException;

class StockCsvImporter
{
    public const INVESTMENT_RATINGS = ['Buy', 'Accumulate', 'Speculative Buy', 'Hold', 'Sell'];

    public const COLUMNS = [
        'symbol',
        'name',
        'sector',
        'cap',
        'description',
        'seo_title',
        'meta_description',
        'price',
        'day_high',
        'day_low',
        'change',
        'yield',
        'buy_price',
        'target_price',
        'recommendation_status',
        'homepage_position',
        'show_on_homepage',
        'investment_rating',
    ];

    /**
     * @return array{headers: list<string>, rows: list<array{line: int, values: array<string, string>}>}
     */
    public function parse(string $path): array
    {
        $handle = fopen($path, 'rb');
        if ($handle === false) {
            throw new InvalidArgumentException('The CSV file could not be read.');
        }

        try {
            $headerRow = fgetcsv($handle, null, ',', '"', '');
            if ($headerRow === false) {
                throw new InvalidArgumentException('The CSV file is empty.');
            }

            $headers = array_map(function (?string $header, int $index): string {
                $normalized = trim((string) $header);
                if ($index === 0) {
                    $normalized = ltrim($normalized, "\xEF\xBB\xBF");
                }

                return strtolower((string) preg_replace('/[\s-]+/', '_', $normalized));
            }, $headerRow, array_keys($headerRow));

            if (in_array('', $headers, true) || count(array_unique($headers)) !== count($headers)) {
                throw new InvalidArgumentException('CSV headings must be unique and cannot be blank.');
            }
            if (! in_array('symbol', $headers, true)) {
                throw new InvalidArgumentException('The CSV must include a symbol column.');
            }

            $unknownHeaders = array_diff($headers, self::COLUMNS);
            if ($unknownHeaders !== []) {
                throw new InvalidArgumentException('Unknown CSV '.(count($unknownHeaders) === 1 ? 'column' : 'columns').': '.implode(', ', $unknownHeaders).'.');
            }

            $rows = [];
            $line = 1;
            while (($row = fgetcsv($handle, null, ',', '"', '')) !== false) {
                $line++;
                if (count(array_filter($row, fn (?string $value): bool => trim((string) $value) !== '')) === 0) {
                    continue;
                }
                if (count($row) !== count($headers)) {
                    throw new InvalidArgumentException("Row {$line} has ".count($row).' values; expected '.count($headers).'.');
                }

                $values = array_combine($headers, array_map(fn (?string $value): string => trim((string) $value), $row));
                $values = $this->normalizeClassifications($values, $line);
                $rows[] = ['line' => $line, 'values' => $values];
            }
        } finally {
            fclose($handle);
        }

        if ($rows === []) {
            throw new InvalidArgumentException('The CSV does not contain any company rows.');
        }

        return ['headers' => $headers, 'rows' => $rows];
    }

    /**
     * @param  array<string, string>  $values
     * @return array<string, string>
     */
    private function normalizeClassifications(array $values, int $line): array
    {
        $choices = [
            'sector' => config('stockedge.sectors'),
            'cap' => ['Blue Chip', 'Mid Cap', 'Small Cap'],
            'recommendation_status' => ['Active', 'Target Achieved', 'Closed', ...self::INVESTMENT_RATINGS],
            'investment_rating' => self::INVESTMENT_RATINGS,
        ];

        foreach ($choices as $field => $options) {
            if (empty($values[$field])) {
                continue;
            }
            $value = preg_replace('/\s+/', ' ', $values[$field]);
            foreach ($options as $option) {
                if (strcasecmp($value, $option) === 0) {
                    $values[$field] = $option;
                    break;
                }
            }
        }

        if (isset($values['cap']) && in_array(strtolower($values['cap']), ['large cap', 'large cap / blue-chip scale', 'blue-chip'], true)) {
            $values['cap'] = 'Blue Chip';
        }

        if (in_array($values['recommendation_status'] ?? '', self::INVESTMENT_RATINGS, true)) {
            if (! empty($values['investment_rating']) && $values['investment_rating'] !== $values['recommendation_status']) {
                throw new InvalidArgumentException("Row {$line}: investment_rating conflicts with the rating in recommendation_status.");
            }
            $values['investment_rating'] = $values['recommendation_status'];
            unset($values['recommendation_status']);
        }

        return $values;
    }
}
