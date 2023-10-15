<?php

namespace App\Infrastructure;

use App\Domain\Answer;
use App\Domain\FindQuestionsByIdsQuery;
use App\Domain\GetRandomQuestionsQuery;
use App\Domain\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Question>
 */
class DatabaseQuestionRepository extends ServiceEntityRepository implements
    GetRandomQuestionsQuery,
    FindQuestionsByIdsQuery
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function getRandomQuestions(int $limit): array
    {
        $builder = new ResultSetMappingBuilder($this->getEntityManager(), ResultSetMappingBuilder::COLUMN_RENAMING_INCREMENT);
        $builder->addRootEntityFromClassMetadata(Question::class, 'q');
        $builder->addJoinedEntityFromClassMetadata(Answer::class, 'a', 'q', 'answers');

        $sql = <<<SQL
        with questionsTemp AS (
            select *
            from questions
            order by random()
            limit :limit
        )
        select {$builder->generateSelectClause()}
        from answers a
        inner join questionsTemp q on a.question_id = q.id
        order by random();
        SQL;

        $query = $this->getEntityManager()->createNativeQuery($sql, $builder);
        $query->setParameter('limit', $limit, Types::INTEGER);

        /** @var array<Question> $result */
        $result = $query->getResult();

        return $result;
    }

    public function findByIds(array $ids): array
    {
        if ($ids === []) {
            return [];
        }

        /** @var array<Question> $result */
        $result = $this->findBy(['id' => $ids]);

        return $result;
    }
}