<?php

namespace MyExample\Mapi;

use HiFolks\DataType\Arr;

class User extends MapiBase
{
    public function me()
    {
        return Arr::make($this->client->get('/v1/users/me')->getBody())->getArr('user');
    }

}
