<?php


namespace App\Services;


use Illuminate\Support\Facades\Http;

class OpenAiApi
{
    public static function getAnswerFromOpenAI(array $messages){
        $messages = OpenAiApi::transformMessagesForOpenAI($messages);

        $response = Http::withHeaders([
            'Content-Type'=> 'application/json',
            'Authorization' => 'Bearer '. config('services.openai.api_key'),
        ])->post('https://api.openai.com/v1/chat/completions', [
            "model" => "gpt-3.5-turbo",
            "messages" => $messages,
        ]);

        if ($response->successful()) {
            return $response->json()['choices'][0]['message']['content'];
        }

        return $response->json()['error']['message'];
    }

    public static function transformMessagesForOpenAI(array $messages): array
    {
        $messagesForOpenAI = array();
        foreach ($messages as $message){
            $messagesForOpenAI[] = ['role' => 'user', 'content' => $message['question']];
            if (isset($message['answer'])){
                $messagesForOpenAI[] = ['role' => 'assistant', 'content' => $message['answer']];
            }
        }
        return $messagesForOpenAI;
    }
}
