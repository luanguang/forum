<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Model\Spam;

class SpamTest extends TestCase
{
    public function test_it_validates_spam()
    {
        $spam = new Spam();

        $this->assertFalse($spam->detect('Innocent reply here.'));
    }
}
