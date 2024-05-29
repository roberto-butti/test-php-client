<?php

namespace MyExample\Mapi;

use Codewithkyrian\Transformers\Transformers;
use GuzzleHttp\Client;

use function Codewithkyrian\Transformers\Pipelines\pipeline;

class Assets extends MapiBase
{
    public function upload(
        string $filename,
        string $spaceId,
        bool $imageToText = false,
    ) {
        $size = getimagesize($filename);
        //print_r($size);
        $width = $size[0];
        $height = $size[1];
        $payload = [
            "filename" =>  $filename,
            "size" =>  $width . "x" . $height,
        ];
        $response = $this->client->post('/v1/spaces/' . $spaceId . '/assets/', $payload)->getBody();
        $postFields = $response['fields'];
        $postFields['file'] = fopen($filename, 'r');
        $multipart = [];
        foreach ($postFields as $name => $contents) {
            $multipart[] = [
                'name' => $name,
                'contents' => $contents,
            ];
        }
        $client = new Client();

        $responseSigned = $client->request('POST', $response['post_url'], [
            'multipart' => $multipart,
        ]);


        $responseFinish = $this->client->get('/v1/spaces/' . $spaceId . '/assets/' . $response["id"] . '/finish_upload')->getBody();

        if ($imageToText) {
            error_reporting(0);

            Transformers::setup()->setCacheDir('./.transformers-cache')->apply();
            $captioner = @pipeline('image-to-text');

            $result = $captioner($filename);

            $this->update(
                $spaceId,
                $response['id'],
                [
                    "meta_data" => [
                        "alt" => $result[0]['generated_text'],
                    ],
                ],
            );

        }
        $result = [
            "spaceid" => $spaceId,
            "id" => $response["id"],
            "text" => $result[0]['generated_text'],
            "url" => 'https://a.storyblok.com/' . $response['fields']['key'],
        ];
        return $result;

    }

    public function update($spaceId, $imageId, $payload)
    {
        $response = $this->client->put('/v1/spaces/' . $spaceId . '/assets/' . $imageId, $payload)->getBody();

        return true;

    }

}
