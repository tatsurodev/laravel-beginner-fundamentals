<?php

namespace App;

use App\BlogPost;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function blogPosts()
    {
        return $this->hasMany('App\BlogPost');
    }

    // blog postに対するcomments
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    // userに対するcomments, one to manyのpolymorphic relation
    public function commentsOn()
    {
        // latest scopeはComment modelで定義されているscopeだが使えるっぽい
        return $this->morphMany('App\Comment', 'commentable')->latest();
    }

    public function image()
    {
        return $this->morphOne('App\Image', 'imageable');
    }

    public function scopeWithMostBlogPosts(Builder $query)
    {
        return $query->withCount('blogPosts')->orderBy('blog_posts_count', 'desc');
    }

    public function scopeWithMostBlogPostsLastMonth(Builder $query)
    {
        return $query
            ->withCount([
                'blogPosts' => function (Builder $query) {
                    $query->whereBetween(static::CREATED_AT, [now()->subMonths(1), now()]);
                }
            ])
            ->having('blog_posts_count', '>=', 2)
            // ->has('blogPosts', '>=', 2)
            ->orderBy('blog_posts_count', 'desc');
    }

    public function scopeThatHasCommentedOnPost(Builder $query, BlogPost $post)
    {
        return $query->whereHas('comments', function ($query) use ($post) {
            return $query->where('commentable_id', '=', $post->id)->where('commentable_type', '=', BlogPost::class);
        });
    }
}
