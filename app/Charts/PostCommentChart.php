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
        $this->labels(['Posts', 'Comments']);
        $post = Post::count();
        $comment = Comment::count();
        $this->dataset('Posts:Comments', 'bar', [$post, $comment])->backgroundColor(['#f87979', '#7acbf9']);
    }
}
