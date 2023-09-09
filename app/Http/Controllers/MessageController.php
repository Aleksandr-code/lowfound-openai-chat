<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Models\Chat;
use App\Models\Question;
use App\Services\OpenAiApi;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index(): array
    {

        $chat = Chat::where('user_id', auth()->user()->id)->first();

        $messages = $chat->getChatHistory();

        return $messages;
    }

    public function store(MessageRequest $request): array
    {
        $data = $request->validated();

        $chat = Chat::where('user_id', auth()->user()->id)->first();

        $last_message = null;

        DB::transaction(function () use ($data, $chat, &$last_message) {

            $question = $chat->questions()->create($data);

            $messages = $chat->getChatHistory();

            $messages[] = ['question_id' => $question->id, 'question' => $question->message, 'created_at' => $question->created_at];

            //$responseFromOpenAi = 'test!';
            $responseFromOpenAi = OpenAiApi::getAnswerFromOpenAI($messages);
            $answer = $question->answers()->create(['message'=>$responseFromOpenAi]);

            $last_message = array_pop($messages);
            $last_message['answer'] = $answer->message;
        });

        return $last_message;
    }

    public function destroy(Question $question): Response
    {
        // Sqlite - cascading deletion doesn't work,
        // Delete answers it manually
        $question->answers()->delete();

        $question->delete();
        return response([], 204);
    }
}
