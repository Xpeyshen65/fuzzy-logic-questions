<?php

namespace App\Domain\UseCase\CheckUserTest;

interface CheckUserTestPresenter
{
    /**
     * @param array<UserQuestionResult> $userQuestionResults
     */
    public function showSuccessUserQuestionResults(array $userQuestionResults): void;

    /**
     * @param array<UserQuestionResult> $userQuestionResults
     */
    public function showErrorUserQuestionResults(array $userQuestionResults): void;
}