<?php

namespace App\Http\Requests;

use App\Models\Thread;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $body
 */
class ReplyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $thread = Thread::find($this->input('thread_id'));
        if (!$thread || auth()->check() == false) {
            return false;
        }
        return $thread->delete_flag == 0;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'thread_id' => 'required|integer|exists:threads,id',
            'body' => 'required|string',
        ];
    }
}
