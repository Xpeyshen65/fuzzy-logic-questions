<?php

namespace App\Domain;

interface FindAnswersByIdsQuery
{
    /**
     * @param array<int> $ids
     * @return array<Answer>
     */
    public function findByIds(array $ids): array;
}