<?php

namespace MyExample\Mapi;

use HiFolks\DataType\Arr;

class Stories extends MapiBase
{
    public function list($spaceId): Arr
    {

        $response = $this->client->get(
            '/v1/spaces/' . $spaceId . '/stories',
            [
                "in_workflow_stages" => null,
                "filter_query" => [
                    "component" => [
                        "in" => "default-page",
                    ],
                ],
            ],
        )->getBody();
        return Arr::make($response["stories"]);
    }
    public function applyWorkflow($spaceId, $storyId, $workflowId)
    {
        $payload = [
            "workflow_stage_change" =>
            [
                "story_id" => $storyId,
                "workflow_stage_id" => $workflowId,

            ],
        ];
        $this->client->post(
            '/v1/spaces/' . $spaceId . '/workflow_stage_changes',
            $payload,
        )->getBody();

        return true;
    }
}
