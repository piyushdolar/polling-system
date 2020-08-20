<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Answers extends Model
{
    protected $table    = 'answers';
    protected $fillable = ['question_id', 'answer'];
}
