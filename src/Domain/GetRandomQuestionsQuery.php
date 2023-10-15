<?php

namespace App\Domain;

interface GetRandomQuestionsQuery
{
    /**
     * @return array<Question>
     */
    public function getRandomQuestions(int $limit): array;
}