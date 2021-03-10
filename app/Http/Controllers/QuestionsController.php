<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\QuestionBank\Entities\quiz;
// use Modules\QuestionBank\Entities\Questions;
use App\Repositories\ChainRepositoryInterface;
use App\Enroll;
use App\Paginate;
// use App\Q_T_F;
// use App\Q_Essay;
use App\Questions;
use Modules\QuestionBank\Entities\quiz_questions;
use App\CourseSegment;
use Illuminate\Support\Facades\Auth;
use DB;

class QuestionsController extends Controller
{
    public function __construct(ChainRepositoryInterface $chain)
    {
        $this->chain = $chain;
        $this->middleware('auth');
        $this->middleware(['permission:question/get' , 'ParentCheck'],   ['only' => ['index']]);
        
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$quiz_id=null,$question=null)
    {
        $request->validate([
            'courses'    => 'nullable|array',
            'courses.*'  => 'nullable|integer|exists:courses,id',
            'Question_Category_id' => 'array',
            'Question_Category_id.*' => 'integer|exists:questions_categories,id',
            'type' => 'array',
            'type.*' => 'strng|in:MCQ,Essay,T_F,Match,Comprehension',
            'search' => 'nullable|string',
        ]);

        $questions=Questions::query();
        // foreach($questions->get() as $one){
        //     // if($one['type'] != 'Comprehension')
        //         $questions->with($one['type'].'_question');
        //     if($one['type'] == 'MCQ')
        //         $questions->with($one['type'].'_question.MCQ_Choices');
        // }

        $types=['MCQ','Essay','T_F'];
        // foreach($types as $one){
        foreach($questions->get() as $one){
            // if($one['type'] != 'Comprehension')
                $questions->with($one['type'].'_question');
            // $questions->whereHas($one.'_question')->with($one.'_question');
            // if($one == 'MCQ')
            //     $questions->with($one['type'].'_question.MCQ_Choices');
        }
        // return $questions->get();

        if($request->filled('courses'))
            $questions->whereIn('course_id',$request->courses);

        if($request->filled('Question_Category_id'))
            $questions->whereIn('q_cat_id',$request->Question_Category_id);
            
        if($request->filled('search'))
            $questions->where('text', 'LIKE' , "%$request->search%");
    
        if($request->filled('type'))
            $questions->whereIn('type', 'LIKE' ,$request->type);

        //to get all questions in quiz id //quizzes/{quiz_id}/{questions}'
        if($question=='questions'){
            $quiz_shuffle = Quiz::where('id', $quiz_id)->pluck('shuffle')->first();
            // $quiz = Quiz::find( $quiz_id);
            $questions = quiz_questions::where('quiz_id',$quiz_id)
                    ->with(['Question.T_F_question','Question.MCQ_question.MCQ_Choices','Question.Essay_question'])->get()
                    ->pluck('Question.*')->collapse();
            if($quiz_shuffle == 'Questions'|| $quiz_shuffle == 'Questions and Answers')
                $questions =$questions->shuffle();
            
            if($quiz_shuffle == 'Answers'|| $quiz_shuffle == 'Questions and Answers')
                foreach($questions as $question)                    
                    if($question['type'] == 'MCQ')
                        $questions->MCQ_question->MCQ_Choices->shuffle();
            
            
            return response()->json(['message' => __('messages.question.list'), 'body' => $questions->paginate(Paginate::GetPaginate($request))], 200);
        }

        if($question == 'count'){
            // return 'hi';
            $counts = collect();
            $counts['essay'] = 0;
            $counts['tf'] = 0;
            $counts['mcq'] = 0;
            // return $questions->get();
            foreach($questions->get() as $one)
            {
                if($one['type'] == 'MCQ')
                    $counts['mcq'] =+1;
                if($one['type'] == 'T_F')
                    $counts['tf'] =+1;
                if($one['type'] == 'Essay')
                    $counts['essay'] = +1;
            }

            return response()->json(['message' => __('messages.question.count'), 'body' => $counts], 200);
        }

        return response()->json(['message' => __('messages.question.list'), 'body' => $questions->get()->paginate(Paginate::GetPaginate($request))], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$type=null)
    {
        $request->validate([
            //for interface model
            'course_id' => 'required|integer|exists:courses,id',
            'q_cat_id' => 'required|integer|exists:questions_categories,id',
            //for request of creation multi type questions
            'Question' => 'required|array',
            'Question.*.type' => 'required|in:MCQ,Essay,T_F,Match,Comprehension', 
            'Question.*.text' => 'required|string', //need in every type_question
            'Question.*.is_true' => 'required_if:Question.*.type,==,T_F|boolean', //for true-false
            'Question.*.and_why' => 'boolean', //if question t-f and have and_why question
            //MCQ validation
            'Question.*.MCQ_Choices' => 'required_if:Question.*.type,==,MCQ|array',
            'Question.*.MCQ_Choices.*.is_true' => 'required_if:Question.*.type,==,MCQ|boolean',
            'Question.*.MCQ_Choices.*.content' => 'required_if:Question.*.type,==,MCQ|string',
            //Comprehension 
            'Question.*.subQuestion' => 'array|required_if:Question.*.type,==,Comprehension',
            'Question.*.subQuestion.*.type' => 'required_if:Question.*.type,==,Comprehension||in:MCQ,Essay,T_F,Match',
            'Question.*.subQuestion.*.text' => 'required_if:Question.*.type,==,Comprehension||string',
            'Question.*.subQuestion.*.is_true' => 'required_if:Question.*.subQuestion.*.type,==,T_F|boolean', //for true-false
            'Question.*.subQuestion.*.and_why' => 'boolean', //if question t-f and have and_why question
            'Question.*.subQuestion.*.MCQ_Choices' => 'required_if:Question.*.subQuestion.*.type,==,MCQ|array',
            'Question.*.subQuestion.*.MCQ_Choices.*.is_true' => 'required_if:Question.*.subQuestion.*.type,==,MCQ|boolean',
            'Question.*.subQuestion.*.MCQ_Choices.*.content' => 'required_if:Question.*.subQuestion.*.type,==,MCQ|string',
        ]);

        foreach ($request->Question as $question) {
            $quest=Questions::firstOrCreate([
                'course_id' => $request->course_id,
                'q_cat_id' => $request->q_cat_id,
                'created_by' => Auth::id(),
                'text' => $question['text'],
                'type' => $question['type']
            ]);
            $quests[]=$quest->id;
            if($question['type'] == 'Comprehension'){
                // return $question['subQuestion'];
                foreach($question['subQuestion'] as $sub){
                    $q= $quest->{$sub['type'].'_question'}()->create($sub); 
                }
            }
            else
                $q= $quest->{$question['type'].'_question'}()->create($question); //firstOrNew //insertOrIgnore doen't work

            if($question['type'] == 'MCQ')
                foreach($question['MCQ_Choices'] as $choice)
                    $test=$q->MCQ_Choices()->create($choice);
        }
        if($type == 1)  
            return $quests;

        return HelperController::api_response_format(200, [], __('messages.question.add'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
