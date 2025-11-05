<?php

namespace Adeliom\EasyCommonBundle\Enum;

enum ThreeStateStatusEnum: string
{
    case UNPUBLISHED = 'unpublished';

    case PENDING = 'pending';

    case PUBLISHED = 'published';
}
