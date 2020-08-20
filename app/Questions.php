<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    protected $table = 'questions';

    public function getMyPoll()
    {
        return $this->select('questions.question', 'questions.created_at', \DB::raw('GROUP_CONCAT(answers.answer) as answer'))
            ->join('answers', 'answers.question_id', '=', 'questions.id')
            ->groupBy('questions.id')
            ->orderBy('questions.id', 'DESC');
    }

    public function getMyResult()
    {
        return $this->select('questions.question', 'replies.created_at', 'answers.answer')
            ->join('replies', 'replies.question_id', '=', 'questions.id')
            ->join('answers', 'answers.id', '=', 'replies.answer_id')
            ->where('replies.user_id', \Auth::id())
            ->get();
    }
}
