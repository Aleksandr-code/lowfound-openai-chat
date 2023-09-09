<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chats';
    protected $with = ['questions'];

    public function questions(){
        return $this->hasMany(Question::class);
    }

    public function getChatHistory(): array
    {
        $messages = array();
        foreach ($this->questions as $key => $question){
            $messages[$key]['question_id'] = $question->id;
            $messages[$key]['question'] = $question->message;
            if (isset($question->answers)){
                $messages[$key]['answer'] = $question->answers[0]->message;
            }
            $messages[$key]['created_at'] = $question->created_at;
        }

        return $messages;
    }


}
