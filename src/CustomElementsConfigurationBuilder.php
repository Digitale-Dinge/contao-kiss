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
        // ToDo: Think about a better solution in the future
        Controller::loadDataContainer('tl_content');
        Controller::loadDataContainer('tl_module');
        Controller::loadDataContainer('tl_company');
        Controller::loadDataContainer('tl_member');
    }

    public function create(array $labels, string $contentCategory = 'texts', array|null $extra = []): self
    {
        $this->config['label'] = $labels;
        $this->config['contentCategory'] = $contentCategory;

        $this->config = [...$this->config, ...$extra];

        return $this;
    }

    public function startList(string $key = 'list', array|null $translations = null, array|null $elementLabel = null, int $min = 1, int|null $max = null): self
    {
        $this->applyPendingFields();

        $listConfig = [
            'label' => $translations ?? [
                    $this->translator->trans('rsce.list.label', [], 'rsce'),
                    $this->translator->trans('rsce.list.description', [], 'rsce')
            ],
            'inputType' => 'list',
            'elementLabel' => $elementLabel ?? $this->translator->trans('rsce.list.element', [], 'rsce'),
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

    public function addField(string $key, array $options, array $eval = []): self
    {
        if ([] !== $eval) {
            $options['eval'] = array_merge($options['eval'] ?? [], $eval);
        }

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

    public function addGridGroup(): self
    {
        if ($this->isListField()) {
            throw new \Exception('Using addGridGroup() is not allowed inside lists.');
        }

        $this->addGroup('grid', [$this->translator->trans('rsce.group.grid', [], 'rsce')]);
        $this->addField('gridColumns', ['inputType' => 'standardField']);
        $this->addField('gridGap', ['inputType' => 'standardField']);

        return $this;
    }

    public function addDependsOnField(string $key, array $options): self
    {
        $blankOption = false;

        // Blank option
        if ($options[array_key_first($options)] === '') {
            $blankOption = true;
            unset($options[array_key_first($options)]);
        }

        if (array_is_list($options)) {
            foreach ($options as &$option) {
                $option = $this->translator->trans(
                    "rsce.field.$key.options.$option",
                    [],
                    'rsce'
                );
            }
        }

        return $this->addField($key, [
            'label' => [
                $this->translator->trans("rsce.field.$key.label", [], 'rsce'),
                $this->translator->trans("rsce.field.$key.description", [], 'rsce'),
            ],
            'inputType' => 'select',
            'options' => $options,
            'eval' => [
                'includeBlankOption' => $blankOption,
                'tl_class' => 'w50 clr',
            ],
        ]);
    }

    public function addHeadlineField(array $eval = []): self
    {
        $options = $this->isListField() ? $GLOBALS['TL_DCA']['tl_content']['fields']['headline'] : [
            'inputType' => 'standardField',
        ];

        return $this->addField('headline', $options, $eval);
    }

    public function addToplineField(array $eval = []): self
    {
        $options = $this->isListField() ? $GLOBALS['TL_DCA']['tl_content']['fields']['topline'] : [
            'inputType' => 'standardField',
        ];

        return $this->addField('topline', $options, $eval);
    }

    public function addRichTextField(array $eval = []): self
    {
        $options = $this->isListField() ? $GLOBALS['TL_DCA']['tl_content']['fields']['text'] : [
            'inputType' => 'standardField',
        ];

        return $this->addField('text', $options, $eval);
    }

    public function addTextAlignmentField(array $eval = []): self
    {
        $options = $this->isListField() ? $GLOBALS['TL_DCA']['tl_content']['fields']['textAlignment'] : [
            'inputType' => 'standardField',
        ];

        return $this->addField('textAlignment', $options, $eval);
    }

    public function addTextAppearanceField(array $eval = []): self
    {
        $options = $this->isListField() ? $GLOBALS['TL_DCA']['tl_content']['fields']['textAppearance'] : [
            'inputType' => 'standardField',
        ];

        return $this->addField('textAppearance', $options, $eval);
    }

    public function addImageField(
        array $eval = [],
        string|null $dependsOn = null,
        bool $includeImageSizeField = false,
    ): self
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

        $this->addField('singleSRC', $options, $eval);

        if ($includeImageSizeField) {
            $this->addImageSizeField([], $dependsOn);
        }

        return $this;
    }

    public function addImageSizeField(array $eval = [], string|null $dependsOn = null): self
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

        $this->addField('size', $options, $eval);

        return $this;
    }

    public function addIconField(array $eval = [], string|null $dependsOn = null): self
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

        $this->addField('icon', $options, $eval);

        return $this;
    }

    public function addPhoneField(array $eval = []): self
    {
        $options = $GLOBALS['TL_DCA']['tl_member']['fields']['phone'];
        $options['eval']['mandatory'] = false;

        $this->addField('phone', $options, $eval);

        return $this;
    }

    public function addEmailField(array $eval = []): self
    {
        $options = $GLOBALS['TL_DCA']['tl_member']['fields']['email'];
        $options['eval']['mandatory'] = false;

        $this->addField('email', $options, $eval);

        return $this;
    }

    public function addSocialsField(array $eval = []): self
    {
        $options = $GLOBALS['TL_DCA']['tl_company']['fields']['socials'];

        $this->addField('socials', $options, $eval);

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function addCallToActionField(array $eval = []): self
    {
        if ($this->isListField()) {
            throw new \Exception('Using addCallToActionField() is not allowed inside lists.');
        }

        return $this->addField('callToAction', ['inputType' => 'standardField'], $eval);
    }

    public function addBackgroundField(array $eval = []): self
    {
        if ($this->isListField()) {
            throw new \Exception('Using addBackgroundField() is not allowed inside lists.');
        }

        return $this->addField('backgroundColor', ['inputType' => 'standardField'], $eval);
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
