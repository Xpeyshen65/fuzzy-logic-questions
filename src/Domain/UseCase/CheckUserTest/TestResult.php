<?php

namespace App\Domain\UseCase\CheckUserTest;

use App\Domain\Answer;
use App\Domain\Question;
use function array_values;

/**
 * @phpstan-type UserResult array{question: Question, answers: array<Answer>}
 */
class TestResult
{
    /**
     * @var array<int, UserResult>
     */
    private array $successAnswersByQuestionIdMap = [];

    /**
     * @var array<int, UserResult>
     */
    private array $errorAnswersByQuestionIdMap = [];

    /**
     * @param array<Answer> $answers
     * @param array<Question> $questions
     */
    public function __construct(array $answers, array $questions)
    {
        foreach ($answers as $answer) {
            $this->addAnswer($answer);
        }

        foreach ($questions as $question) {
            $this->addQuestion($question);
        }
    }

    /**
     * @return array<UserQuestionResult>
     */
    public function getSuccessResult(): array
    {
        return array_map(
            [$this, 'createUserQuestionResult'],
            array_values($this->successAnswersByQuestionIdMap),
        );
    }

    /**
     * @return array<UserQuestionResult>
     */
    public function getErrorResult(): array
    {
        return array_map(
            [$this, 'createUserQuestionResult'],
            array_values($this->errorAnswersByQuestionIdMap),
        );
    }

    private function addAnswer(Answer $answer): void
    {
        $question = $answer->question;

        if (!$answer->isRightAnswer || isset($this->errorAnswersByQuestionIdMap[$question->toId()])) {
            $this->addAnswerToErrorMap($answer);
            $this->moveSuccessAnswersToErrorAnswers($question);

            return;
        }

        $this->addAnswerToSuccessMap($answer);
    }

    private function addQuestion(Question $question): void
    {
        if (
            isset($this->successAnswersByQuestionIdMap[$question->toId()])
            || isset($this->errorAnswersByQuestionIdMap[$question->toId()])
        ) {
            return;
        }

        $this->addQuestionToErrorMap($question);
    }

    private function addAnswerToSuccessMap(Answer $answer): void
    {
        $this->addAnswerToMap($answer, $this->successAnswersByQuestionIdMap);
    }

    private function addAnswerToErrorMap(Answer $answer): void
    {
        $this->addAnswerToMap($answer, $this->errorAnswersByQuestionIdMap);
    }

    /**
     * @phpstan-param array<int, UserResult> $map
     */
    private function addAnswerToMap(Answer $answer, array &$map): void
    {
        $question = $answer->question;

        if (!isset($map[$question->toId()])) {
            $this->addQuestionToMap($question, $map);
        }

        $map[$question->toId()]['answers'][] = $answer;
    }

    /**
     * @phpstan-param UserResult $map
     */
    private function addQuestionToMap(Question $question, array &$map): void
    {
        $map[$question->toId()] = [
            'question' => $question,
            'answers' => [],
        ];
    }

    private function addQuestionToErrorMap(Question $question): void
    {
        $this->addQuestionToMap($question, $this->errorAnswersByQuestionIdMap);
    }

    private function moveSuccessAnswersToErrorAnswers(Question $question): void
    {
        $successAnswers = $this->successAnswersByQuestionIdMap[$question->toId()]['answers'] ?? [];
        $errorAnswers = $this->errorAnswersByQuestionIdMap[$question->toId()]['answers'] ?? [];
        $this->errorAnswersByQuestionIdMap[$question->toId()]['answers'] = [...$successAnswers, ...$errorAnswers];
        unset($this->successAnswersByQuestionIdMap[$question->toId()]);
    }

    /**
     * @phpstan-param UserResult $userResult
     */
    private function createUserQuestionResult(array $userResult): UserQuestionResult
    {
        return new UserQuestionResult($userResult['question'], $userResult['answers']);
    }
}