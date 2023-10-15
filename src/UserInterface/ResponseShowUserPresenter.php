<?php

namespace App\UserInterface;

use App\Domain\Question;
use App\Domain\UseCase\ShowUserTest\ShowUserPresenter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ResponseShowUserPresenter extends AbstractController implements ShowUserPresenter
{
    /**
     * @var Question[]
     */
    private array $questions;

    public function showQuestions(array $questions): void
    {
        $this->questions = $questions;
    }

    public function createResponse(): Response
    {
        return $this->render('test-page.html.twig', ['questions' => $this->questions]);
    }
}