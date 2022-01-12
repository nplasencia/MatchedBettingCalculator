<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Tests\Unit\Service;

use Auret\MatchedBetting\Service\FlashMessageService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\Translation\TranslatorInterface;

final class FlashMessageServiceTest extends TestCase
{
    private const FLASH_INFO = 'info';
    private const FLASH_WARN = 'warning';
    private const FLASH_ERROR = 'error';

    private FlashMessageService $service;

    private RequestStack $requestStack;
    private TranslatorInterface $translator;

    protected function setUp(): void
    {
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->translator = $this->createMock(TranslatorInterface::class);

        $this->service = new FlashMessageService($this->requestStack, $this->translator);
    }

    /**
     * @dataProvider flashMessageDataProvider
     * @param array $messageParameters
     */
    public function testAddInfoMessage_withNoParameters_success(array $messageParameters): void
    {
        $messageKey = 'whatever.key';
        $translatedMessage = 'Some translated test message';

        $this->translator->expects($this->once())->method('trans')->with($messageKey, $messageParameters)
            ->willReturn($translatedMessage);

        $session = $this->createSessionMockAndSetExpectations(self::FLASH_INFO, $translatedMessage);
        $this->requestStack->expects($this->once())->method('getSession')->willReturn($session);

        $this->service->addInfoMessage($messageKey, $messageParameters);
    }

    /**
     * @dataProvider flashMessageDataProvider
     * @param array $messageParameters
     */
    public function testAddWarningMessage_withNoParameters_success(array $messageParameters): void
    {
        $messageKey = 'whatever.key';
        $translatedMessage = 'Some translated test message';

        $this->translator->expects($this->once())->method('trans')->with($messageKey, $messageParameters)
            ->willReturn($translatedMessage);

        $session = $this->createSessionMockAndSetExpectations(self::FLASH_WARN, $translatedMessage);
        $this->requestStack->expects($this->once())->method('getSession')->willReturn($session);

        $this->service->addWarningMessage($messageKey, $messageParameters);
    }

    /**
     * @dataProvider flashMessageDataProvider
     * @param array $messageParameters
     */
    public function testErrorInfoMessage_success(array $messageParameters): void
    {
        $messageKey = 'whatever.key';
        $translatedMessage = 'Some translated test message';

        $this->translator->expects($this->once())->method('trans')->with($messageKey, $messageParameters)
            ->willReturn($translatedMessage);

        $session = $this->createSessionMockAndSetExpectations(self::FLASH_ERROR, $translatedMessage);
        $this->requestStack->expects($this->once())->method('getSession')->willReturn($session);

        $this->service->addErrorMessage($messageKey, $messageParameters);
    }

    private function createSessionMockAndSetExpectations(string $flashType, string $translatedMessage): MockObject
    {
        $session = $this->createMock(Session::class);
        $flashBag = $this->createMock(FlashBagInterface::class);

        $flashBag->expects($this->once())->method('add')->with($flashType, $translatedMessage);
        $session->expects($this->once())->method('getFlashBag')->willReturn($flashBag);

        return $session;
    }

    private function flashMessageDataProvider(): array
    {
        return [
            'Test without key' => [[]],
            'Test with one key' => [['messageParamKey' => 'messageParamValue']],
            'Test with some keys' => [
                ['messageParamKey1' => 'messageParamValue1'],
                ['messageParamKey2' => 'messageParamValue2'],
                ['messageParamKey3' => 'messageParamValue3']
            ],
        ];
    }
}
