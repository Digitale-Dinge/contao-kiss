<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\EventListener;

use Contao\CoreBundle\Translation\TranslatableLabelInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

trait TranslatableEnumTrait
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function getTranslatedOptions($enum): array
    {
        if (!is_subclass_of($enum, \BackedEnum::class)) {
            throw new \LogicException(\sprintf('Invalid usage. Class "%s" must extend BackedEnum.', $enum));
        }

        $options = [];

        foreach ($enum::cases() as $case) {
            $options[$case->name] = is_subclass_of($case, TranslatableLabelInterface::class) ? $case->label()->trans($this->translator) : $case->value;
        }

        return $options;
    }
}
