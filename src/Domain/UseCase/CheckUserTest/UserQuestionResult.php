<?php

namespace App\Domain\UseCase\CheckUserTest;

use App\Domain\Answer;
use App\Domain\Question;

class UserQuestionResult
{
    /**
     * @param array<Answer> $answers
     */
    public function __construct(
        public readonly Question $question,
        public readonly array $answers
    ) {
    }
}