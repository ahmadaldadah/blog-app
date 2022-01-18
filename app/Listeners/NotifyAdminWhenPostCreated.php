<?php

namespace App\Listeners;

use App\Events\PostPosted;
use App\Jobs\ThrottledMail;
use App\Mail\PostAdded;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyAdminWhenPostCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(PostPosted $event)
    {
        User::thatIsAnAdmin()->get()
            ->map(function (User $user){
               ThrottledMail::dispatch(new PostAdded(),$user);
            });
    }
}
