<?php

namespace App\Domain;

interface UserTestResultGateway
{
    public function save(UserTestResult $userTestResult): void;
}