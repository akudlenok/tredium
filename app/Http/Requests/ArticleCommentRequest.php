<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleCommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'article_id' => 'required|exists:articles,id',
            'subject' => 'required|max:255',
            'body' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'article_id.required' => 'Укажите статью.',
            'subject.required' => 'Укажите тему сообщения.',
            'body.required' => 'Укажите сообщение.',
            'subject.max' => 'Тема сообщение не может быть больше :max символов.',
        ];
    }
}
