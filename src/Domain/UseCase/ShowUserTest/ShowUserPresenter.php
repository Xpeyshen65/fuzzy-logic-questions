<?php

namespace App\Domain\UseCase\ShowUserTest;

use App\Domain\Question;

interface ShowUserPresenter
{
    /**
     * @param array<Question> $questions
     */
    public function showQuestions(array $questions): void;
}