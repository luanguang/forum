<?php

namespace Tests\Feature;

use Tests\TestCase;

class SearchTest extends TestCase
{
    public function test_a_user_can_search_threads()
    {
        $search = 'foobar';

        create('Thread', [], 2);
        create('Thread', ['body' => "A thread with {$search} term."], 2);

        $results = $this->getJson("/threads/search?q={$search}")->json();

        $this->assertCount(2, $results['data']);
    }
}
