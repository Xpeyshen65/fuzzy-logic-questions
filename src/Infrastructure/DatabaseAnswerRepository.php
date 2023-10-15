<?php

namespace App\Infrastructure;

use App\Domain\Answer;
use App\Domain\FindAnswersByIdsQuery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Answer>
 */
class DatabaseAnswerRepository extends ServiceEntityRepository implements FindAnswersByIdsQuery
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Answer::class);
    }

    public function findByIds(array $ids): array
    {
        if ($ids === []) {
            return [];
        }

        /** @var array<Answer> $result */
        $result = $this->findBy(['id' => $ids]);

        return $result;
    }
}