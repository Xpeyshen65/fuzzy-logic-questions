<?php

namespace App\UserInterface;

use App\Domain\UseCase\CheckUserTest\CheckUserTestPresenter;
use App\Domain\UseCase\CheckUserTest\UserQuestionResult;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ResponseCheckUserTestPresenter extends AbstractController implements CheckUserTestPresenter
{
    /**
     * @var array<UserQuestionResult>
     */
    private array $successUserQuestionResults;

    /**
     * @var array<UserQuestionResult>
     */
    private array $errorUserQuestionResults;

    public function showSuccessUserQuestionResults(array $userQuestionResults): void
    {
        $this->successUserQuestionResults = $userQuestionResults;
    }

    public function showErrorUserQuestionResults(array $userQuestionResults): void
    {
        $this->errorUserQuestionResults = $userQuestionResults;
    }

    public function createResponse(): Response
    {
        return $this->render(
            'test-page-result.html.twig',
            [
                'successQuestionResults' => $this->successUserQuestionResults,
                'errorQuestionResults' => $this->errorUserQuestionResults,
                'retryUrl' => $this->generateUrl('user_test_index'),
            ]
        );
    }
}