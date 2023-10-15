<?php

namespace App\Domain;

use Doctrine\ORM\Mapping as ORM;
use LogicException;

#[ORM\Entity()]
#[ORM\Table('answers')]
class Answer
{
    private function __construct(
        #[ORM\Id()]
        #[ORM\GeneratedValue(strategy: 'AUTO')]
        #[ORM\Column('id')]
        public readonly ?int $id,
        #[ORM\Column('text')]
        public readonly string $text,
        #[ORM\Column('is_right')]
        public readonly bool $isRightAnswer,
        #[ORM\ManyToOne(Question::class)]
        public readonly Question $question,
    ) {
    }

    public static function createWithId(int $id, string $text, bool $isRightAnswer, Question $question): self
    {
        return new self($id, $text, $isRightAnswer, $question);
    }

    public function toId(): int
    {
        return $this->id ?? throw new LogicException('Id cannot be blank');
    }
}
