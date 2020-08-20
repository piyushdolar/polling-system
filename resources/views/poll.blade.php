@extends('layouts.app')
@section('content')
<div class="container">
    @if($question)
    <div class="card">
        <div class="card-header">
            Answer the question!
        </div>
        <div class="card-body">
            <form action="{{ route('answer.create') }}" method="POST">
                @csrf
                <h5 class="card-title">{{$question['question']}}</h5>
                <p class="card-text">
                    <input type='hidden' name='question_id' value="{{$question['id']}}"/>
                    @php
                        $answerId = explode(',', $question['answer_ids']);
                        $answer = explode(',', $question['answer']);
                        for($i=0;$i<count($answer);$i++){
                            echo '<div class="form-check">
                                <input class="form-check-input" type="radio" name="answer_id" id="radio-' . $i . '" value='.$answerId[$i].'>
                                <label class="form-check-label" for="radio-' . $i . '">' . $answer[$i] . '</label>
                            </div>';
                        }
                    @endphp
                </p>
                <button type="submit" class='btn btn-primary'>Submit</button>
            </form>
        </div>
        <div class="card-footer text-muted">
            Created: {{$question['created_at']}}
        </div>
    </div>
    @else
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1 class="display-4">Looking for questions?</h1>
            <p class="lead">Stay tuned more questions are coming soon!</p>
        </div>
    </div>
    @endif
</div>
@endsection
