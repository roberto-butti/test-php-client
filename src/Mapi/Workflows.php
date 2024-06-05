<?php

namespace MyExample\Mapi;

use HiFolks\DataType\Arr;

class Workflows extends MapiBase
{
    public function list(string $spaceId): Arr
    {

        $response = $this->client->get(
            '/v1/spaces/' . $spaceId . '/workflows',
        )->getBody();
        return Arr::make($response["workflows"]);
    }

    public function listStages(string $spaceId, $workflowId): Arr
    {

        $response = $this->client->get(
            '/v1/spaces/' . $spaceId . '/workflow_stages/',
            [
                'in_workflow' => $workflowId,
            ],
        )->getBody();
        return Arr::make($response["workflow_stages"]);
    }

}
