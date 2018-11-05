<?php

namespace App\Inspections;

class Spam
{
    protected $inspections = [
        InvalidKeywords::class,
        KeyHeldDown::class,
    ];

    public function detect($body)
    {
        foreach ($this->inspections as $inspection) {
            app($inspection)->detect($body);
        }

        return false;
    }

    public function detectInvalidKeywords($body)
    {
        $invalidKeywords = [
            'something forbidden'
        ];

        foreach ($invalidKeywords as $invalidKeyword) {
            if (stripos($body, $invalidKeyword) !== false) {
                throw new \Exception('Your reply contains spam.');
            }
        }
    }

    public function detectKeyHeldDown($body)
    {
        if (preg_match('/(.)\\1{4,}/', $body)) {
            throw new \Exception('Your reply contains spam.');
        }
    }
}
