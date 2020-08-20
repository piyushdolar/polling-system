<?php
namespace App\Http\Controllers;

use App\Questions;
use App\Replies;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Request;
use Throwable;
use Validator;
use Yajra\DataTables\Facades\DataTables;

class AnswerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = new Questions();
        $question = $model->getMyPoll()
            ->where('questions.user_id', '!=', \Auth::id())
            ->select('questions.id', 'questions.question', 'questions.created_at', \DB::raw('GROUP_CONCAT(answers.id) as answer_ids'), \DB::raw('GROUP_CONCAT(answers.answer) as answer'))
            ->get();
        $export = [];
        $replies = new Replies();
        foreach ($question as $each) {
            $row = $replies->select('replies.id as reply_id')->where('question_id', $each['id'])->where('user_id', \Auth::id())->first();
            if (!$row) {
                $export = array(
                    'id' => $each['id'],
                    'question' => $each['question'],
                    'created_at' => $each['question'],
                    'answer_ids' => $each['answer_ids'],
                    'answer' => $each['answer']
                );
                break;
            }
        }

        return view('poll')->with('question', $export);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'answer_id' => 'required'
            ], [
                'answer_id.required' => 'Answer can not be empty.'
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator);
            } else {
                $model = new Replies();
                $model->user_id = \Auth::id();
                $model->question_id = $request->question_id;
                $model->answer_id = $request->answer_id;
                if ($model->save()) {
                    return Redirect::back()->with('success', 'Your answer has been submitted.');
                }
            }
        } catch (Throwable $e) {
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
        if ($request->ajax()) {
            $model = new Questions();
            $result = $model->getMyResult();

            return Datatables::of($result)
                ->editColumn('created_at', function ($question) {
                    return date('d-m-Y H:i A', strtotime($question->created_at));
                })->make(true);
        }

        return view('result');
    }
}
