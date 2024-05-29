<?php

namespace MyExample\Mapi;

use Storyblok\ManagementClient;

class MapiBase
{
    public function __construct(
        protected ManagementClient $client,
    ) {}

}
