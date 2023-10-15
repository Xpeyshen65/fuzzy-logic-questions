<?php

namespace App\Domain\UseCase\CheckUserTest;

use App\Domain\FindAnswersByIdsQuery;
use App\Domain\FindQuestionsByIdsQuery;
use App\Domain\UserTestResult;
use App\Domain\UserTestResultGateway;
use DateTimeImmutable;
use Psr\Clock\ClockInterface;
use Symfony\Component\Clock\NativeClock;

class CheckUserTestInteractor
{
    public function __construct(
        private readonly FindQuestionsByIdsQuery $questionsQuery,
        private readonly FindAnswersByIdsQuery $answerRepository,
        private readonly UserTestResultGateway $userTestResultGateway,
        private readonly ClockInterface $clock = new NativeClock(),
    ) {
    }

    public function execute(UserTestRequest $request, CheckUserTestPresenter $presenter): void
    {
        $answers = $this->answerRepository->findByIds($request->answerIds);
        $questions = $this->questionsQuery->findByIds($request->questionIds);

        $this->userTestResultGateway->save(
            new UserTestResult($request->userId, $questions, $answers, $this->clock->now()),
        );

        $testResult = new TestResult($answers, $questions);

        $presenter->showSuccessUserQuestionResults($testResult->getSuccessResult());
        $presenter->showErrorUserQuestionResults($testResult->getErrorResult());
    }
}