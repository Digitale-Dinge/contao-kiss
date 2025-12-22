<?php

namespace DigitaleDinge\ContaoKiss\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use DigitaleDinge\ContaoKiss\Event\SocialMediaOptionsEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

readonly class SocialMediaOptionsListener
{
    public function __construct(private EventDispatcherInterface $eventDispatcher)
    {}

    #[AsCallback('tl_kiss_company', 'fields.socials.attributes')]
    public function setSocialMediaOptions(array $attributes, DataContainer $dc): array
    {
        $event = new SocialMediaOptionsEvent();
        $this->eventDispatcher->dispatch($event);

        $attributes['fields']['social']['options'] = $event->getSocialMedia();

        return $attributes;
    }
}
