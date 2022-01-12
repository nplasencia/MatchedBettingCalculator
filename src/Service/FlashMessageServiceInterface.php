<?php declare(strict_types = 1);

namespace App\Service;

interface FlashMessageServiceInterface
{
    public function addInfoMessage(string $messageKey, array $parameters = []): void;

    public function addWarningMessage(string $messageKey, array $parameters = []): void;

    public function addErrorMessage(string $messageKey, array $parameters = []): void;
}