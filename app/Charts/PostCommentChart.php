<?php

namespace App\Charts;

use App\Models\Comment;
use App\Models\Post;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class PostCommentChart extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->labels(['Posts', 'Comments','Deleted Post', 'Deleted Comments']);
        $post = Post::count();
        $comment = Comment::count();
        $deletedp = Post::onlyTrashed()->count();
        $deletedc = Comment::onlyTrashed()->count();
        $this->dataset('Posts:Comments', 'bar', [$post, $comment, $deletedp, $deletedc])->backgroundColor(['#f87979', '#7acbf9', '#3acb22', '#EC4E20']);
    }
}
