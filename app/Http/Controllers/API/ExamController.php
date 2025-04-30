<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use App\Models\UserAnswer;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;


/**
 * @OA\Schema(
 *     schema="Exam",
 *     type="object",
 *     title="Exam",
 *     required={"id", "name"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Midterm Exam"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-04-01T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-04-15T10:00:00Z")
 * )
 */
class ExamController extends Controller
{
    use ApiResponseTrait;

    /**
     * @OA\Get(
     *     path="/api/exams",
     *     summary="List all exams",
     *     security={{"bearer":{}}},
     *     tags={"Exams"},
     *     @OA\Response(
     *         response=200,
     *         description="All exam list",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="All exam list!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Exam")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {

            $exams = Exam::all();

            return $this->success($exams, 'All exam list!', 200);
        } catch (Exception $exception) {
            return $this->error($exception->getMessage(), 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/exams/{id}",
     *     operationId="getExamById",
     *     tags={"Exams"},
     *     summary="Get a specific exam by ID with questions and options",
     *     description="Returns an exam with its related questions and options",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the exam to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Specific exam!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Math Final Exam"),
     *                 @OA\Property(
     *                     property="questions",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=101),
     *                         @OA\Property(property="text", type="string", example="What is 2 + 2?"),
     *                         @OA\Property(
     *                             property="options",
     *                             type="array",
     *                             @OA\Items(
     *                                 @OA\Property(property="id", type="integer", example=201),
     *                                 @OA\Property(property="option_text", type="string", example="4")
     *                             )
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Exam not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Exam] 123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="An error occurred")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {

            $exam = Exam::with(['questions.options'])->findOrFail($id);

            return $this->success($exam, 'Specific exam!', 200);
        } catch (Exception $exception) {
            return $this->error($exception->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/exams/{id}/submit",
     *     summary="Submit exam answers",
     *     description="Submits answers for a specific exam and returns results with score.",
     *     operationId="submitExam",
     *     tags={"Exams"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the exam",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Answers submitted by the user",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"answers"},
     *             @OA\Property(
     *                 property="answers",
     *                 type="object",
     *                 additionalProperties={
     *                     "type"="string"
     *                 },
     *                 example={"1": "A", "2": "Paris", "3": "True"}
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Exam submitted successfully!",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Exam submitted successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="results", type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="question", type="string", example="What is 2 + 2?"),
     *                         @OA\Property(property="your_answer", type="string", example="4"),
     *                         @OA\Property(property="correct_answer", type="string", example="4"),
     *                         @OA\Property(property="is_correct", type="boolean", example=true)
     *                     )
     *                 ),
     *                 @OA\Property(property="score", type="integer", example=3),
     *                 @OA\Property(property="total_questions", type="integer", example=5),
     *                 @OA\Property(property="correct_answers", type="integer", example=3)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Something went wrong"),
     *             @OA\Property(property="data", type="null", example=null)
     *         )
     *     )
     * )
     */
    public function submit(Request $request, $id)
    {
        $validated = $request->validate(['answers' => 'required|array']);

        try {
            $userId = $request->user()->id;
            $questions = $this->getExamQuestions($id);

            $results = $this->processAnswers($questions, $validated['answers'], $userId);

            $data = [
                'results' => $results,
                'score' => $this->calculateScore($results),
                'total_questions' => count($results),
                'correct_answers' => collect($results)->where('is_correct', true)->count(),
            ];

            return $this->success($data, 'Exam submitted successfully!', 200);
        } catch (Exception $exception) {
            return $this->error($exception->getMessage(), 500);
        }
    }

    protected function getExamQuestions($examId)
    {
        return Question::where('exam_id', $examId)->get();
    }

    protected function processAnswers($questions, $submittedAnswers, $userId)
    {
        $results = [];

        foreach ($questions as $question) {
            $submitted = $submittedAnswers[$question->id] ?? null;

            UserAnswer::create([
                'user_id' => $userId,
                'question_id' => $question->id,
                'answer_text' => $submitted,
            ]);

            $results[] = [
                'question' => $question->question_text,
                'your_answer' => $submitted,
                'correct_answer' => $question->correct_answer,
                'is_correct' => $submitted == $question->correct_answer,
            ];
        }

        return $results;
    }

    protected function calculateScore(array $results): int
    {
        return collect($results)->where('is_correct', true)->count();
    }
}
