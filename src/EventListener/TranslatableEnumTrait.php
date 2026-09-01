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

        return $this->getTranslatedCases(...$enum::cases());
    }

    public function getTranslatedCases(\BackedEnum ...$cases): array
    {
        $options = [];

        foreach ($cases as $case) {
            $options[$case->name] = $case instanceof TranslatableLabelInterface ? $case->label()->trans($this->translator) : $case->value;
        }

        return $options;
    }
}
