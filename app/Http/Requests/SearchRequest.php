<?php

namespace App\Http\Requests;

use App\Rules\IsbnRule;
use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'author' => ['nullable', 'string'],
            'isbn' => ['nullable', 'string', new IsbnRule],
            'title' => ['nullable', 'string'],
            'offset' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function getAuthor(): ?string
    {
        return $this->input('author');
    }

    public function getIsbn(): ?string
    {
        return $this->input('isbn');
    }

    public function getTitle(): ?string
    {
        return $this->input('title');
    }

    public function getOffset(): int
    {
        return $this->input('offset', 0);
    }
}
