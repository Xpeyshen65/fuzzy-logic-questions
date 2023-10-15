<?php

namespace App\Tests\Domain\UseCase\ShowUserTest;

use App\Domain\GetRandomQuestionsQuery;
use App\Domain\Question;
use App\Domain\UseCase\ShowUserTest\ShowUserPresenter;
use App\Domain\UseCase\ShowUserTest\ShowUserTestInteractor;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Domain\UseCase\ShowUserTest\ShowUserTestInteractor
 * @codeCoverageIgnore
 */
class ShowUserTestInteractorTest extends TestCase
{
    private GetRandomQuestionsQuery & MockInterface $questionsQuery;
    private ShowUserTestInteractor $interactor;

    /**
     * @test
     */
    public function showQuestionsRightWork(): void
    {
        $questions = [Question::createWithId(1, 'Вопрос 1')];
        $this->questionsQuery
            ->expects('getRandomQuestions')
            ->andReturn($questions);

        $presenter = Mockery::mock(ShowUserPresenter::class);
        $presenter
            ->expects('showQuestions')
            ->withArgs(static function ($actual) use ($questions) {
                self::assertEquals($questions, $actual);

                return $questions == $actual;
            });

        $this->interactor->execute($presenter);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->questionsQuery = Mockery::mock(GetRandomQuestionsQuery::class);
        $this->interactor = new ShowUserTestInteractor(
            $this->questionsQuery,
        );
    }
}