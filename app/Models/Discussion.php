<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Lexx\ChatMessenger\Models\Thread;

class Discussion extends Thread
{
    public function one($userId)
    {
        return $this->participants()->where('user_id', $userId)->with('user')->first();
    }

    public function other($userId)
    {
        return $this->participants()->whereNot('user_id', $userId)->with('user')->first();
    }
}
