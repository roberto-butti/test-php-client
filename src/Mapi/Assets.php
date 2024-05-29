<?php

namespace MyExample\Mapi;

use GuzzleHttp\Client;

class Assets extends MapiBase
{
    public function upload(
        string $filename,
        string $spaceId,
    ) {
        $size = getimagesize($filename);
        //print_r($size);
        $width = $size[0];
        $height = $size[1];
        echo "START";
        $payload = [
            "filename" =>  $filename,
            "size" =>  $width . "x" . $height,
        ];
        $response = $this->client->post('/v1/spaces/' . $spaceId . '/assets/', $payload)->getBody();
        echo "SB POST DONE";
        $postFields = $response['fields'];
        var_dump($postFields);
        $postFields['file'] = fopen($filename, 'r');
        $multipart = [];
        foreach ($postFields as $name => $contents) {
            $multipart[] = [
                'name' => $name,
                'contents' => $contents,
            ];
        }
        $client = new Client();
        var_dump($response);
        $responseSigned = $client->request('POST', $response['post_url'], [
            'multipart' => $multipart,
        ]);

        echo "SIGNED POST DONE";
        $responseFinish = $this->client->get('/v1/spaces/' . $spaceId . '/assets/' . $response["id"] . '/finish_upload')->getBody();


        return 'https://a.storyblok.com/' . $response['fields']['key'];

    }

}
