<?php

namespace App\Domain\UseCase\CheckUserTest;

class UserTestRequest
{
    /**
     * @param array<int> $answerIds
     * @param array<int> $questionIds
     * @param string $userId
     */
    public function __construct(
        public readonly array $answerIds,
        public readonly array $questionIds,
        public readonly string $userId,
    ) {
    }
}