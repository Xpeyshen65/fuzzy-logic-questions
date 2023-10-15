<?php

namespace App\Infrastructure;

use App\Domain\Answer;
use App\Domain\Question;
use App\Domain\UserTestResult;
use App\Domain\UserTestResultGateway;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DbalException;
use Doctrine\DBAL\Types\Types;
use OpsWay\Doctrine\DBAL\Types\Types as ExtraTypes;
use RuntimeException;

class DatabaseUserTestResultGateway implements UserTestResultGateway
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function save(UserTestResult $userTestResult): void
    {
        try {
            $this->connection->executeStatement(
                <<<SQL
            insert into user_test_results (user_id, question_ids, answer_ids, created_at)
                values (:userId, :questionIds, :answerIds, :createdAt)
            SQL,
                [
                    'userId' => $userTestResult->userId,
                    'questionIds' => array_map(
                        static fn(Question $question): int => $question->toId(),
                        $userTestResult->questions,
                    ),
                    'answerIds' => array_map(
                        static fn(Answer $answer): int => $answer->toId(),
                        $userTestResult->answers,
                    ),
                    'createdAt' => $userTestResult->createdAt,
                ],
                [
                    'userId' => Types::STRING,
                    'questionIds' => ExtraTypes::ARRAY_INT,
                    'answerIds' => ExtraTypes::ARRAY_INT,
                    'createdAt' => Types::DATETIME_IMMUTABLE,
                ],
            );
        } catch (DbalException $exception) {
            throw new RuntimeException('Save result failed', 0, $exception);
        }
    }
}
