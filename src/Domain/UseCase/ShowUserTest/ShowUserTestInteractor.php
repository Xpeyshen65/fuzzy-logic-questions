<?php

namespace App\Domain\UseCase\ShowUserTest;

use App\Domain\GetRandomQuestionsQuery;

class ShowUserTestInteractor
{
    private const QUESTION_COUNT = 10;

    public function __construct(
        private readonly GetRandomQuestionsQuery $questionsQuery,
    ) {
    }

    public function execute(ShowUserPresenter $presenter): void
    {
        $questions = $this->questionsQuery->getRandomQuestions(self::QUESTION_COUNT);

        $presenter->showQuestions($questions);
    }
}