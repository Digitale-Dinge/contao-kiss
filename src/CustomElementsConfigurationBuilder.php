<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @experimental
 */
final class CustomElementsConfigurationBuilder
{
    public array $fields = [];

    public array $listFields = [];

    public function __construct(private TranslatorInterface $translator)
    {}

    public function create(string|null $type = null): self|array
    {
        $this->fields = [];

        if (null === $type) {
            return $this;
        }

        return match ($type) {
            'media_text' => self::getMediaTextConfig(),
            'media_text_list' => self::getMediaTextConfig(),
            default => throw new \RuntimeException('Unsupported rock solid custom elements type: ' . $type),
        };
    }

    public function addGroup(string $key, array $translations = []): self
    {
        $this->fields[$key] = [
            'inputType' => 'group',
            'label' => $translations,
        ];

        return $this;
    }

    public function addDependsOnField(string $key, array $options): self
    {
        $blankOption = false;

        if ($options[array_key_first($options)] === '') {
            $blankOption = true;
            unset($options[array_key_first($options)]);
        }

        $translatedOptions = [];

        foreach ($options as $option) {
            $translatedOptions[$option] = $this->translator->trans("rsce.field.$key.options.$option", [], 'rsce') ?? $option;
        }

        $this->fields[$key] = [
            'label' => [
                $this->translator->trans("rsce.field.$key.label", [], 'rsce'),
                $this->translator->trans("rsce.field.$key.description", [], 'rsce'),
            ],
            'inputType' => 'select',
            'options' => $translatedOptions,
            'eval' => [
                'includeBlankOption' => $blankOption,
                'tl_class' => 'w50 clr',
            ],
        ];

        return $this;
    }

    public function addHeadlineField(): self
    {
        $this->fields['headline'] = [
            'inputType' => 'standardField',
        ];

        return $this;
    }

    public function addToplineField(): self
    {
        $this->fields['topline'] = [
            'inputType' => 'standardField',
        ];

        return $this;
    }

    public function addTextField(): self
    {
        $this->fields['text'] = [
            'inputType' => 'standardField',
        ];

        return $this;
    }

    public function addImageField(string|null $dependsOn = null, bool $includeImageSizeField = false): self
    {
        $this->fields['singleSRC'] = [
            'inputType' => 'standardField',
            'eval' => [
                'tl_class' => 'w50 clr',
            ],
        ];

        if (null !== $dependsOn) {
            $this->fields['singleSRC']['dependsOn'] = [
                'field' => $dependsOn,
                'value' => 'image',
            ];
        }

        if ($includeImageSizeField) {
            $this->addImageSizeField($dependsOn);
        }

        return $this;
    }

    public function addImageSizeField(string|null $dependsOn = null): self
    {
        $this->fields['size'] = [
            'inputType' => 'standardField',
        ];

        if (null !== $dependsOn) {
            $this->fields['size']['dependsOn'] = [
                'field' => $dependsOn,
                'value' => 'image',
            ];
        }

        return $this;
    }

    public function addIconField(string|null $dependsOn = null): self
    {
        $this->fields['icon'] = [
            'label' => [
                'de' => ['Icon', 'Hier können Sie ein Icon auswählen.'],
                'en' => ['Icon', 'Here you can choose an icon.'],
            ],
            'inputType' => 'svgIconPicker',
            'eval' => [
                'sourceDirectory' => 'public/kiss_icons/svg',
                'metadataDirectory' => 'public/kiss_icons',
                'tl_class' => 'long clr',
            ],
        ];

        if (null !== $dependsOn) {
            $this->fields['icon']['dependsOn'] = [
                'field' => $dependsOn,
                'value' => 'icon',
            ];
        }

        return $this;
    }

    private function getMediaTextConfig(): array
    {
        $this
            ->addGroup('settings', [
                $this->translator->trans('rsce.group.settings', [], 'rsce'),
            ])
            ->addDependsOnField('type', ['image', 'icon', 'separated'])
            ->addImageSizeField('type')
            ->addIconField('type')
            ->addGroup('media', [
                $this->translator->trans('rsce.group.media', [], 'rsce'),
            ])
            ->addImageField('type')
            ->addHeadlineField()
            ->addToplineField()
            ->addTextField()
        ;

        return $this->fields;
    }
}
