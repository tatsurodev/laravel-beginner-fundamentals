<?php

namespace App\Jobs;

use App\User;
use App\Comment;
use App\Jobs\ThrottledMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use App\Mail\CommentPostedOnPostWatched;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NotifyUsersPostWasCommented implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $comment;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $now = now();
        // User::thatHasCommentedOnPost($this->comment->commentable)->get()->filter(function (User $user) {
        //     return $user->id !== $this->comment->user_id;
        // })->map(function (User $user) use ($now) {
        //     Mail::to($user)->later(
        //         $now->addSeconds(6),
        //         new CommentPostedOnPostWatched($this->comment, $user)
        //     );
        // });

        User::thatHasCommentedOnPost($this->comment->commentable)->get()->filter(function (User $user) {
            return $user->id !== $this->comment->user_id;
        })->map(function (User $user) {
            ThrottledMail::dispatch(
                new CommentPostedOnPostWatched($this->comment, $user),
                $user
            );
        });
    }
}
