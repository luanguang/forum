<?php

namespace Tests\Unit;

use Tests\TestCase;

class ReplyTest extends TestCase
{
   public function test_a_reply_has_an_owner()
   {
       $reply = factory('App\Model\Reply')->create();

       $this->assertInstanceOf('App\Model\User', $reply->owner);
   }
}
