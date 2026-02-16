<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss;

use Contao\Controller;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @experimental
 */
final class CustomElementsConfigurationBuilder
{
    private array $config = [];

    private array $listStack = [];

    private array $fields = [];

    private array $pendingFields = [];

    public function __construct(private TranslatorInterface $translator)
    {
        Controller::loadDataContainer('tl_content');
        Controller::loadDataContainer('tl_module');
    }

    public function create(array $labels, string $contentCategory = 'texts', array|null $extra = []): self
    {
        $this->config['label'] = $labels;
        $this->config['contentCategory'] = $contentCategory;

        $this->config = [...$this->config, ...$extra];

        return $this;
    }

    public function startList(string $key, array $label, array|null $elementLabel = null, int $min = 1, int|null $max = null): self
    {
        $this->applyPendingFields();

        $listConfig = [
            'label' => $label,
            'inputType' => 'list',
            'elementLabel' => $elementLabel ?? $this->translator->trans("rsce.label.element", [], 'rsce'),
            'fields' => [],
            'minItems' => $min,
        ];

        if (null !== $max) {
            $listConfig['maxItems'] = $max;
        }

        $this->listStack[] = [
            'key' => $key,
            'config' => $listConfig,
        ];

        return $this;
    }

    public function endList(): self
    {
        $this->applyPendingFields();

        if ([] === $this->listStack) {
            throw new \LogicException('No list to close');
        }

        $current = array_pop($this->listStack);

        if ([] === $this->listStack) {
            $this->fields[$current['key']] = $current['config'];
        } else {
            $index = array_key_last($this->listStack);
            $this->listStack[$index]['config']['fields'][$current['key']] = $current['config'];
        }

        return $this;
    }

    public function applyPendingFields(): self
    {
        if ([] === $this->pendingFields) {
            return $this;
        }

        if ([] === $this->listStack) {
            $this->fields = [
                ...$this->fields,
                ...$this->pendingFields,
            ];
        } else {
            $index = array_key_last($this->listStack);

            $this->listStack[$index]['config']['fields'] = [
                ...$this->listStack[$index]['config']['fields'],
                ...$this->pendingFields,
            ];
        }

        $this->pendingFields = [];

        return $this;
    }

    public function addField(string $key, array $options): self
    {
        $this->pendingFields[$key] = $options;

        return $this;
    }

    public function addGroup(string $key, array $translations = []): self
    {
        return $this->addField($key, [
            'inputType' => 'group',
            'label' => $translations,
        ]);
    }

    public function addDependsOnField(string $key, array $options): self
    {
        $blankOption = false;

        // ToDo: Could allow translated options directly
        if ($options[array_key_first($options)] === '') {
            $blankOption = true;
            unset($options[array_key_first($options)]);
        }

        $translatedOptions = [];

        foreach ($options as $option) {
            $translatedOptions[$option] = $this->translator->trans("rsce.field.$key.options.$option", [], 'rsce') ?? $option;
        }

        return $this->addField($key, [
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
        ]);
    }

    public function addHeadlineField(): self
    {
        $options = $this->isListField() ? $GLOBALS['TL_DCA']['tl_content']['fields']['headline'] : [
            'inputType' => 'standardField',
        ];

        return $this->addField('headline', $options);
    }

    public function addToplineField(): self
    {
        $options = $this->isListField() ? $GLOBALS['TL_DCA']['tl_content']['fields']['topline'] : [
            'inputType' => 'standardField',
        ];

        return $this->addField('topline', $options);
    }

    public function addTextField(): self
    {
        $options = $this->isListField() ? $GLOBALS['TL_DCA']['tl_content']['fields']['text'] : [
            'inputType' => 'standardField',
        ];

        return $this->addField('text', $options);
    }

    public function addImageField(string|null $dependsOn = null, bool $includeImageSizeField = false): self
    {
        $options = $this->isListField() ? $GLOBALS['TL_DCA']['tl_content']['fields']['singleSRC'] : [
            'inputType' => 'standardField',
            'eval' => [
                'tl_class' => 'w50 clr',
            ],
        ];

        if (null !== $dependsOn) {
            $options['dependsOn'] = [
                'field' => $dependsOn,
                'value' => 'image',
            ];
        }

        $this->addField('singleSRC', $options);

        if ($includeImageSizeField) {
            $this->addImageSizeField($dependsOn);
        }

        return $this;
    }

    public function addImageSizeField(string|null $dependsOn = null): self
    {
        $options = $this->isListField() ? $GLOBALS['TL_DCA']['tl_content']['fields']['size'] : [
            'inputType' => 'standardField',
        ];

        $options['eval']['tl_class'] = 'w50';

        if (null !== $dependsOn) {
            $options['dependsOn'] = [
                'field' => $dependsOn,
                'value' => 'image',
            ];
        }

        $this->addField('size', $options);

        return $this;
    }

    public function addIconField(string|null $dependsOn = null): self
    {
        $options = [
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
            $options['dependsOn'] = [
                'field' => $dependsOn,
                'value' => 'icon',
            ];
        }

        $this->addField('icon', $options);

        return $this;
    }

    public function build(): array
    {
        $this->applyPendingFields();

        $this->config['fields'] = $this->fields;
        $this->fields = [];

        return $this->config;
    }

    private function isListField(): bool
    {
        return [] !== $this->listStack;
    }
}
