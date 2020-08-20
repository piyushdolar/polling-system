<?php
namespace App\Http\Controllers;

use App\Answers;
use App\Questions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Throwable;
use Validator;
use Yajra\DataTables\Facades\DataTables;

class QuestionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            if ($request->post()) {
                $validator = Validator::make($request->all(), [
                    'question' => 'required|max:255',
                    'answer.*' => 'required|distinct|min:2'
                ], [
                    'answer.*.distinct' => 'Answers can not be similar.',
                    'answer.*.array' => 'Do not manipulate input fields.',
                    'answer.*.min' => 'Enter minimum 1 word.',
                    'answer.*.required' => 'Answer can not be empty.'
                ]);

                if ($validator->fails()) {
                    return back()->withErrors($validator);
                } else {
                    $model = new Questions();
                    \DB::beginTransaction();
                    $model->question = $request->question;
                    $model->user_id = \Auth::id();
                    if ($model->save()) {
                        $lastId = $model->id;
                        $answer = new Answers();
                        $data = array();
                        foreach ($request->answer as $each) {
                            $data[] = array(
                                'question_id' => $lastId,
                                'answer' => $each,
                                'created_at' => date('Y-m-d H:i:s')
                            );
                        }
                        if ($answer::insert($data)) {
                            \DB::commit();

                            return Redirect::back()->with('success', 'Your question has been created.');
                        }
                    }
                }
            }
        } catch (Throwable $e) {
            \DB::rollback();

            return Redirect::back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int                         $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        try {
            if ($request->ajax()) {
                $model = new Questions();
                $questions = $model->getMyPoll()->where('questions.user_id', \Auth::id());

                return Datatables::of($questions)
                    ->editColumn('created_at', function ($question) {
                        return date('d-m-Y H:i A', strtotime($question->created_at));
                    })
                    ->editColumn('answer', function ($question) {
                        $explode = explode(',', $question->answer);
                        $tags = '';
                        foreach ($explode as $each) {
                            $tags .= '<li class="list-group-item">' . $each . '</li>';
                        }

                        return '<ul class="list-group list-group-flush">' . $tags . '</ul>';
                    })
                    ->rawColumns(['answer'])
                    ->make(true);
            }
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }
}
