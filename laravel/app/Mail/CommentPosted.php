<?php

namespace App\Mail;

use App\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentPosted extends Mailable
{
    use Queueable, SerializesModels;

    // ここで定義されたpublic propertyはviewで自動的に使用可
    public $comment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        // commentのセット
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Commented was posted on your {$this->comment->commentable->title} blog post";
        // view(ビュー名, 渡す配列);
        return $this
            // file attachment
            // first method
            // ->attach(
            //     storage_path('app/public') . '/' . $this->comment->commentable->image->path,
            //     [
            //         'as' => 'profile_picture.jpeg',
            //         'mime' => 'image/jpeg'
            //     ]
            // )
            // second method
            // ->attachFromStorage($this->comment->commentable->image->path, 'profile_picture.jpeg')
            // third method
            // ->attachFromStorageDisk('public', $this->commentable->iamge->path)
            // ->attachData(Storage::get($this->comment->commentable->image->path), 'profile_picture_from_data.jpeg', ['mime' => 'image/jpeg'])
            ->subject($subject)
            ->view('emails.posts.commented');
    }
}
