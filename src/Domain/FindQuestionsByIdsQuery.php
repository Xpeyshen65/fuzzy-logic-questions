<?php

namespace App\Domain;

interface FindQuestionsByIdsQuery
{
    /**
     * @param array<int> $ids
     * @return array<Question>
     */
    public function findByIds(array $ids): array;
}