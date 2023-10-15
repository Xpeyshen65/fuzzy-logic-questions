<?php

namespace App\UserInterface\Controller;

use App\Domain\UseCase\CheckUserTest\CheckUserTestInteractor;
use App\Domain\UseCase\CheckUserTest\UserTestRequest;
use App\Domain\UseCase\ShowUserTest\ShowUserTestInteractor;
use App\UserInterface\ResponseCheckUserTestPresenter;
use App\UserInterface\ResponseShowUserPresenter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function array_map;

class UserTestController extends AbstractController
{
    #[Route('/user-test', name: 'user_test_index', methods: ['GET'])]
    public function startTestAction(
        ShowUserTestInteractor $interactor,
        ResponseShowUserPresenter $presenter,
    ): Response {
        $interactor->execute($presenter);

        return $presenter->createResponse();
    }

    #[Route('/user-test', name: 'user_test_check', methods: ['POST'])]
    public function checkTestResult(
        CheckUserTestInteractor $interactor,
        ResponseCheckUserTestPresenter $presenter
    ): Response {
        $request = Request::createFromGlobals();
        $questionIds = array_map('intval', $request->request->all('questionIds'));
        $answerIds = array_map('intval', $request->request->all('answerIds'));

        $interactor->execute(
            new UserTestRequest($answerIds, $questionIds, $request->getClientIp() ?? ''),
            $presenter,
        );

        return $presenter->createResponse();
    }
}