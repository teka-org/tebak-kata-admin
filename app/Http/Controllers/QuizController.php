<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Http\Response; 
use Illuminate\Http\JsonResponse;
use App\Http\Requests\QuizRequest;
use App\Http\Resources\QuizResource;

class QuizController extends Controller
{
    public function createQuiz(QuizRequest $request): JsonResponse
    {
        $this-> validate($request, [
            'question'=> 'required|string',
            'answer'=> 'required|string',
        ]);


        $quiz = Quiz::create([
            'question'      => $request->question,
            'answer'         => $request->answer,
        ]);

        return (new QuizResource($quiz))->response()->setStatusCode(201);
    }

    public function getQuiz()
    {
        $quiz = Quiz::All();
        return response()->json(['quiz' => $quiz], 200);
    }

    public function updateQuiz(Request $request, $id)
    {
        $this->validate($request, [
            
            'question'=> 'required|string',
            'answer'=> 'required|string',
        ]);

        $quiz = Quiz::find($id);

        if(!$quiz){
            return response()->json(['message' => 'Quiz not found'], response::HTTP_NOT_FOUND);
        }


        $quiz->update([
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        return (new QuizResource($quiz))->response()->setStatusCode(201);
    }

    public function deleteQuiz(Request $request, $id)
    {
        $quiz = Quiz::find ($id);

        if($quiz){
            $quiz->delete();
            return response()->json(['message' => 'Quiz Deleted'], Response::HTTP_NOT_FOUND);
        }
    }
}
