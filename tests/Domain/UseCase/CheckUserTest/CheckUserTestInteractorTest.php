<?php

namespace App\Tests\Domain\UseCase\CheckUserTest;

use App\Domain\Answer;
use App\Domain\FindAnswersByIdsQuery;
use App\Domain\FindQuestionsByIdsQuery;
use App\Domain\Question;
use App\Domain\UseCase\CheckUserTest\CheckUserTestInteractor;
use App\Domain\UseCase\CheckUserTest\CheckUserTestPresenter;
use App\Domain\UseCase\CheckUserTest\UserQuestionResult;
use App\Domain\UseCase\CheckUserTest\UserTestRequest;
use App\Domain\UserTestResult;
use App\Domain\UserTestResultGateway;
use DateTimeImmutable;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\MockClock;
use Traversable;

/**
 * @covers \App\Domain\UseCase\CheckUserTest\CheckUserTestInteractor
 * @codeCoverageIgnore
 */
class CheckUserTestInteractorTest extends TestCase
{
    private const NOW = '2023-10-15 00:00:00';
    private const USER_ID = 'userIdExample';

    private FindQuestionsByIdsQuery & Mockery\MockInterface $questionsQuery;
    private FindAnswersByIdsQuery & Mockery\MockInterface $answerQuery;
    private UserTestResultGateway & Mockery\MockInterface $userTestResultGateway;
    private MockClock $clock;
    private CheckUserTestInteractor $interactor;

    /**
     * @test
     * @dataProvider successDataProvider
     */
    public function executeUseCaseAndGotRightResult(
        array $questions,
        array $answers,
        array $successExpected,
        array $errorExpected,
    ): void {
        $request = new UserTestRequest([], [], self::USER_ID);
        $this->questionsQuery
            ->expects('findByIds')
            ->andReturn($questions);
        $this->answerQuery
            ->expects('findByIds')
            ->andReturn($answers);
        $this->userTestResultGateway
            ->expects('save')
            ->withArgs(
                fn ($argument) =>
                    new UserTestResult($request->userId, $questions, $answers, $this->clock->now()) == $argument,
            );

        $presenter = Mockery::mock(CheckUserTestPresenter::class);
        $presenter
            ->expects('showSuccessUserQuestionResults')
            ->withArgs(static function ($actual) use ($successExpected) {
                self::assertEquals($successExpected, $actual, 'Успешные вопросы');

                return $successExpected == $actual;
            });
        $presenter
            ->expects('showErrorUserQuestionResults')
            ->withArgs(static function ($actual) use ($errorExpected) {
                self::assertEquals($errorExpected, $actual, 'Вопросы с ошибками');

                return $errorExpected == $actual;
            });

        $this->interactor->execute($request, $presenter);
    }

    public function successDataProvider(): Traversable
    {
        yield 'Все вопросы решены правильно' => [
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

        yield 'Все вопросы решены неправильно' => [
            $questions = [
                Question::createWithId(1, 'Вопрос 1'),
                Question::createWithId(2, 'Вопрос 2'),
            ],
            $answers = [
                Answer::createWithId(1, 'Ответ 1', false, $questions[0]),
                Answer::createWithId(2, 'Ответ 2', true, $questions[0]),
                Answer::createWithId(3, 'Ответ 3', false, $questions[1]),
            ],
            [],
            [
                new UserQuestionResult($questions[0], [$answers[0], $answers[1]]),
                new UserQuestionResult($questions[1], [$answers[2]]),
            ],
        ];

        yield 'Часть вопросов решена правильно, часть нет' => [
            $questions = [
                Question::createWithId(1, 'Вопрос 1'),
                Question::createWithId(2, 'Вопрос 2'),
            ],
            $answers = [
                Answer::createWithId(1, 'Ответ 1', true, $questions[0]),
                Answer::createWithId(2, 'Ответ 2', true, $questions[0]),
                Answer::createWithId(3, 'Ответ 3', false, $questions[1]),
            ],
            [
                new UserQuestionResult($questions[0], [$answers[0], $answers[1]]),
            ],
            [
                new UserQuestionResult($questions[1], [$answers[2]]),
            ],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->questionsQuery = Mockery::mock(FindQuestionsByIdsQuery::class);
        $this->answerQuery = Mockery::mock(FindAnswersByIdsQuery::class);
        $this->userTestResultGateway = Mockery::mock(UserTestResultGateway::class);
        $this->clock = new MockClock(new DateTimeImmutable(self::NOW));
        $this->interactor = new CheckUserTestInteractor(
            $this->questionsQuery,
            $this->answerQuery,
            $this->userTestResultGateway,
            $this->clock,
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        Mockery::close();
    }
}
