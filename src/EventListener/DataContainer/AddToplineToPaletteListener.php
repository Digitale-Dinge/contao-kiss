<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\EventListener\DataContainer;

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\DataContainer;
use DigitaleDinge\ContaoKiss\Event\ExcludeToplineEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AddToplineToPaletteListener
{
    public function __construct(private readonly EventDispatcherInterface $eventDispatcher)
    {}

    #[AsCallback('tl_content', 'config.onload', priority: -1000)]
    public function __invoke(DataContainer|null $dc = null): void
    {
        if (null === $dc) {
            return;
        }

        $event = new ExcludeToplineEvent();
        $this->eventDispatcher->dispatch($event);
        $skip = $event->getTypes();


        foreach ($GLOBALS['TL_DCA'][$dc->table]['palettes'] as $key => $palette) {
            if (\is_array($palette)) {
                continue;
            }

            if (!\in_array($key, $skip, true)) {
                continue;
            }

            PaletteManipulator::create()
                // empty closure to prevent the fallback
                ->addField('topline', 'headline', PaletteManipulator::POSITION_AFTER, fn() => null)
                ->applyToPalette($key, $dc->table)
            ;
        }
    }

    #[AsHook('loadDataContainer')]
    public function loadDataContainer(string $table): void
    {
        if (!\in_array($table, ['tl_content', 'tl_module'], true)) {
            return;
        }

        $GLOBALS['TL_DCA'][$table]['fields']['topline'] = [
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => [
                'tl_class'  => 'w50',
                'maxlength' => 255,
                'allowHtml' => true
            ],
            'sql' => [
                'type'    => 'string',
                'length'  => 255,
                'default' => ''
            ]
        ];
    }
}
