<?php

namespace App\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

#[ORM\Entity()]
#[ORM\Table('questions')]
class Question
{
    /**
     * @phpstan-param Collection<int, Answer> $answers
     */
    private function __construct(
        #[ORM\Id()]
        #[ORM\Column('id')]
        #[ORM\GeneratedValue(strategy: 'AUTO')]
        public readonly ?int $id,
        #[ORM\Column('text')]
        public readonly string $text,
        #[ORM\OneToMany('question', Answer::class)]
        public readonly Collection $answers = new ArrayCollection(),
    ) {
    }

    public static function createWithId(int $id, string $text): self
    {
        return new self($id, $text);
    }

    public function toId(): int
    {
        return $this->id ?? throw new LogicException('Id cannot be blank');
    }
}
