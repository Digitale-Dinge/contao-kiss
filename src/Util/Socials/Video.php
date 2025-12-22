<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Util\Socials;

enum Video: string
{
    case youtube = 'YouTube';
    case vimeo   = 'Vimeo';
    case tiktok  = 'TikTok';
    case twitch  = 'Twitch';
}
