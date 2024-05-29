<?php

namespace MyExample\Mapi;

use HiFolks\DataType\Arr;

class Spaces extends MapiBase
{
    public function all()
    {
        return Arr::make($this->client->get('/v1/spaces')->getBody())->getArr('spaces');
    }
}
