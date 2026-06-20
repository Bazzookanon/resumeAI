<?php

namespace App\Services;

use Smalot\PdfParser\Parser;
use Exception;

class PdfParserService
{
    public function extractText(string $filePath): string
    {
        try {
            $parser = new Parser();
            $pdf    = $parser->parseFile($filePath);
            $text   = $pdf->getText();

            if (empty(trim($text))) {
                throw new Exception('No text could be extracted from the PDF. It may be scanned or image-based.');
            }

            return $this->cleanText($text);
        } catch (Exception $e) {
            throw new Exception('PDF parsing failed: ' . $e->getMessage());
        }
    }

    private function cleanText(string $text): string
    {
        $text = $this->sanitizeUtf8($text);

        // Remove excessive whitespace and normalize line endings
        $text = preg_replace('/\r\n|\r/', "\n", $text);
        $text = preg_replace('/\n{3,}/', "\n\n", $text);
        $text = preg_replace('/ {2,}/', ' ', $text);

        return trim($text);
    }

    private function sanitizeUtf8(string $text): string
    {
        // PDFs with broken font encodings can yield malformed UTF-8 byte sequences,
        // which break JSON encoding when the text is queued or sent to the Claude API.
        $clean = @iconv('UTF-8', 'UTF-8//IGNORE', $text);

        if ($clean === false) {
            $clean = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        }

        // Strip stray control characters (keep newlines and tabs).
        return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $clean);
    }
}
