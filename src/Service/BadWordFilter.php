<?php
namespace App\Service;

class BadWordFilter
{
    private $badWords = [
        'badword1',
        'badword2',
        'badword3'
    ];

    public function filter(string $input): string
    {
        $filteredInput = $input;
        foreach ($this->badWords as $badWord) {
            $pattern = "/\b$badWord\b/i";
            $replacement = str_repeat('*', strlen($badWord));
            $filteredInput = preg_replace($pattern, $replacement, $filteredInput);
        }
        return $filteredInput;
    }
}