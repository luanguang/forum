<?php

namespace Tests\Feature;

use Tests\TestCase;

class FavoritiesTest extends TestCase
{
    public function test_an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();

        $reply = create('Reply');

        try {
            $this->post('replies/' . $reply->id . '/favorite');

            $this->post('replies/' . $reply->id . '/favorite');
        } catch (\Exception $e) {
            $this->fail('Did not expect to insert the same record set twice.');
        }

        $this->assertCount(1, $reply->favorites);
    }

    public function test_an_authenticated_user_can_unfavorite_a_reply()
    {
        $this->signIn();

        $reply = create('Reply');

        $reply->favorite();

        $this->delete('/replies/' . $reply->id . '/favorite');

        $this->assertCount(0, $reply->favorites);
    }

    public function test_guests_can_not_favorite_anything()
    {
        $this->withExceptionHandling()
            ->post('replies/1/favorite')
            ->assertRedirect('/login');
    }
}
