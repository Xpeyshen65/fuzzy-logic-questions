<?php

namespace App\Domain;

use DateTimeImmutable;

class UserTestResult
{
    /**
     * @param array<Question> $questions
     * @param array<Answer> $answers
     */
    public function __construct(
        public readonly string $userId,
        public readonly array $questions,
        public readonly array $answers,
        public readonly DateTimeImmutable $createdAt,
    ) {
    }
}