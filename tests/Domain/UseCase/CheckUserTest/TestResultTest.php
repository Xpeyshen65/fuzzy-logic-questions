<?php

namespace App\Tests\Domain\UseCase\CheckUserTest;

use App\Domain\Answer;
use App\Domain\Question;
use App\Domain\UseCase\CheckUserTest\TestResult;
use App\Domain\UseCase\CheckUserTest\UserQuestionResult;
use PHPUnit\Framework\TestCase;
use Traversable;

/**
 * @covers \App\Domain\UseCase\CheckUserTest\TestResult
 * @codeCoverageIgnore
 */
class TestResultTest extends TestCase
{
    /**
     * @test
     * @dataProvider successDataProvider
     */
    public function userTestLogicWorkRight(
        array $questions,
        array $answers,
        array $expectedSuccess,
        array $expectedError,
    ): void {
        $actual = new TestResult($answers, $questions);

        self::assertEquals($expectedSuccess, $actual->getSuccessResult(), 'Успешные вопросы');
        self::assertEquals($expectedError, $actual->getErrorResult(), 'Вопросы с ошибками');
    }

    public function successDataProvider(): Traversable
    {
        yield 'Все ответы правильные' => [
            $questions = [
                Question::createWithId(1, 'Вопрос 1'),
                Question::createWithId(2, 'Вопрос 2'),
            ],
            $answers = [
                Answer::createWithId(1, 'Ответ 1', true, $questions[0]),
                Answer::createWithId(2, 'Ответ 2', true, $questions[0]),
                Answer::createWithId(3, 'Ответ 3', true, $questions[1]),
            ],
            [
                new UserQuestionResult($questions[0], [$answers[0], $answers[1]]),
                new UserQuestionResult($questions[1], [$answers[2]]),
            ],
            [],
        ];

        yield 'Все ответы неправильные' => [
            $questions = [
                Question::createWithId(1, 'Вопрос 1'),
                Question::createWithId(2, 'Вопрос 2'),
            ],
            $answers = [
                Answer::createWithId(1, 'Ответ 1', false, $questions[0]),
                Answer::createWithId(2, 'Ответ 2', false, $questions[0]),
                Answer::createWithId(3, 'Ответ 3', false, $questions[1]),
            ],
            [],
            [
                new UserQuestionResult($questions[0], [$answers[0], $answers[1]]),
                new UserQuestionResult($questions[1], [$answers[2]]),
            ],
        ];

        yield 'Есть вопрос с неправильным ответом' => [
            $questions = [
                Question::createWithId(1, 'Вопрос 1'),
                Question::createWithId(2, 'Вопрос 2'),
            ],
            $answers = [
                Answer::createWithId(1, 'Ответ 1', false, $questions[0]),
                Answer::createWithId(2, 'Ответ 2', true, $questions[0]),
                Answer::createWithId(3, 'Ответ 3', true, $questions[1]),
            ],
            [
                new UserQuestionResult($questions[1], [$answers[2]]),
            ],
            [
                new UserQuestionResult($questions[0], [$answers[0], $answers[1]]),
            ],
        ];

        yield 'Неправильный ответ встречается после правильного' => [
            $questions = [
                Question::createWithId(1, 'Вопрос 1'),
                Question::createWithId(2, 'Вопрос 2'),
            ],
            $answers = [
                Answer::createWithId(1, 'Ответ 1', true, $questions[0]),
                Answer::createWithId(2, 'Ответ 2', false, $questions[0]),
                Answer::createWithId(3, 'Ответ 3', true, $questions[1]),
            ],
            [
                new UserQuestionResult($questions[1], [$answers[2]]),
            ],
            [
                new UserQuestionResult($questions[0], [$answers[0], $answers[1]]),
            ],
        ];

        yield 'Есть вопрос без ответа' => [
            $questions = [
                Question::createWithId(1, 'Вопрос 1'),
                Question::createWithId(2, 'Вопрос 2'),
            ],
            $answers = [
                Answer::createWithId(3, 'Ответ 3', true, $questions[1]),
            ],
            [
                new UserQuestionResult($questions[1], [$answers[0]]),
            ],
            [
                new UserQuestionResult($questions[0], []),
            ],
        ];

        yield 'Все вопросы без ответа' => [
            $questions = [
                Question::createWithId(1, 'Вопрос 1'),
                Question::createWithId(2, 'Вопрос 2'),
            ],
            [],
            [],
            [
                new UserQuestionResult($questions[0], []),
                new UserQuestionResult($questions[1], []),
            ],
        ];

        yield 'Вопросы и ответы не найдены' => [
            [],
            [],
            [],
            [],
        ];
    }
}