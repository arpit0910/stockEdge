<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ReportHtmlImporter
{
    /**
     * @return array{title: string, summary: string, symbol: ?string, rating: ?string, report_date: ?string, body: string}
     */
    public function parse(string $html): array
    {
        $document = new DOMDocument;
        $previousErrors = libxml_use_internal_errors(true);
        $loaded = $document->loadHTML('<?xml encoding="UTF-8">'.$html, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();
        libxml_use_internal_errors($previousErrors);

        if (! $loaded) {
            throw new InvalidArgumentException('The HTML report could not be read.');
        }

        $xpath = new DOMXPath($document);
        $content = $xpath->query('//article[contains(concat(" ", normalize-space(@class), " "), " body ")]')->item(0)
            ?? $xpath->query('//article')->item(0)
            ?? $xpath->query('//main')->item(0)
            ?? $xpath->query('//body')->item(0);

        if (! $content instanceof DOMElement) {
            throw new InvalidArgumentException('The HTML report does not contain readable content.');
        }

        $documentText = preg_replace('/\s+/', ' ', implode(' ', array_map(
            fn (DOMNode $node): string => $node->textContent,
            iterator_to_array($xpath->query('//text()')),
        ))) ?? '';
        $title = trim($xpath->query('//h1')->item(0)?->textContent ?? '');
        if ($title === '') {
            $title = trim(explode('|', $xpath->query('//title')->item(0)?->textContent ?? '')[0]);
        }
        $summary = trim($xpath->query('//meta[translate(@name, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")="description"]')->item(0)?->getAttribute('content') ?? '');
        if ($summary === '') {
            $summary = trim($xpath->query('.//p', $content)->item(0)?->textContent ?? '');
        }
        preg_match('/\bASX\s*:\s*([A-Z0-9]{2,6})\b/i', $documentText, $symbolMatch);
        preg_match('/\bOriginal stance\s*:\s*(Buy|Hold|Sell)\b/i', $documentText, $ratingMatch);
        preg_match('/\bReport date\s*:\s*(\d{1,2}-[A-Za-z]{3}-\d{4})\b/i', $documentText, $dateMatch);

        foreach (['script', 'style', 'link', 'meta', 'base', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'template', 'svg', 'math'] as $tag) {
            foreach (iterator_to_array($content->getElementsByTagName($tag)) as $node) {
                $node->parentNode?->removeChild($node);
            }
        }

        foreach ($xpath->query('.//*', $content) as $element) {
            if (! $element instanceof DOMElement) {
                continue;
            }

            foreach (iterator_to_array($element->attributes) as $attribute) {
                $name = strtolower($attribute->name);
                if (str_starts_with($name, 'on') || ! in_array($name, ['class', 'href', 'src', 'alt', 'title', 'colspan', 'rowspan', 'scope', 'target', 'rel', 'width', 'height'])) {
                    $element->removeAttribute($attribute->name);
                }
            }

            foreach (['href', 'src'] as $attribute) {
                $value = trim($element->getAttribute($attribute));
                if ($value !== '' && preg_match('/^(?:javascript|data):/i', $value)) {
                    $element->removeAttribute($attribute);
                }
            }
        }

        $converted = trim(implode('', array_map(
            fn (DOMNode $node): string|false => $document->saveHTML($node),
            iterator_to_array($content->childNodes),
        )));

        if ($converted === '' || trim(strip_tags($converted)) === '') {
            throw new InvalidArgumentException('The HTML report does not contain readable content.');
        }

        return [
            'title' => Str::limit($title, 200, ''),
            'summary' => Str::limit($summary, 2000, ''),
            'symbol' => isset($symbolMatch[1]) ? strtoupper($symbolMatch[1]) : null,
            'rating' => isset($ratingMatch[1]) ? ucfirst(strtolower($ratingMatch[1])) : null,
            'report_date' => $dateMatch[1] ?? null,
            'body' => $converted,
        ];
    }
}
