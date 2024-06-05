<?php

namespace MyExample\Mapi;

use HiFolks\DataType\Arr;

class Spaces extends MapiBase
{
    public function all(): \HiFolks\DataType\Arr
    {
        return Arr::make($this->client->get('/v1/spaces')->getBody())->getArr('spaces');
    }

    public function get(string $spaceId): \HiFolks\DataType\Arr
    {
        return Arr::make($this->client->get('/v1/spaces/' . $spaceId)->getBody())->getArr('space');
    }

}
