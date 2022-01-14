<?php declare(strict_types = 1);

namespace Auret\MatchedBetting\Tests\Unit\Form;

use Auret\MatchedBetting\Form\BetBookmakerType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class BetBookmakerTypeTest extends TestCase
{
    private BetBookmakerType $type;

    private RouterInterface $router;
    private TranslatorInterface $translator;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->translator = $this->createMock(TranslatorInterface::class);

        $this->type = new BetBookmakerType($this->router, $this->translator);
    }

    public function testBuildForm_success(): void
    {
        $routeName = 'store_bookmaker';
        $generatedUrl = 'https://some.generated.url';
        $saveKey = 'form.button.save';
        $saveLabel = 'Some save translated label';

        $this->router->expects($this->once())->method('generate')
            ->with($routeName, [], UrlGeneratorInterface::ABSOLUTE_PATH)
            ->willReturn($generatedUrl);
        $this->translator->expects($this->once())->method('trans')->with($saveKey, [])->willReturn($saveLabel);

        $builder = $this->createMock(FormBuilderInterface::class);
        $builder->expects($this->once())->method('setMethod')->with('POST')->willReturn($builder);
        $builder->expects($this->once())->method('setAction')->with($generatedUrl)->willReturn($builder);
        $builder->expects($this->exactly(3))->method('add')
            ->withConsecutive(
                ['name', TextType::class, ['required' => true]],
                ['url', UrlType::class, ['required' => true]],
                ['save', SubmitType::class, ['label' => $saveLabel]],
            )
            ->willReturn($builder);

        $this->type->buildForm($builder, []);
    }
}
