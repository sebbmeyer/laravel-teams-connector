<?php

namespace Sebbmyr\Teams\Cards;

use Sebbmyr\Teams\AbstractCard as Card;

/**
 * Forge card for microsoft teams
 */
class ForgeCard extends Card
{
    const STATUS_SUCCESS = '01BC36';
    const STATUS_ERROR = 'FF0000';

    public function getMessage()
    {
        return [
            "@type" => "MessageCard",
            "@context" => "http://schema.org/extensions",
            "summary" => "Forge Card",
            "themeColor" => ($this->data["status"] === 'success') ? self::STATUS_SUCCESS : self::STATUS_ERROR,
            "title" => "Forge deployment message",
            "sections" => [
                [
                    "activityTitle" => "",
                    "activitySubtitle" => "",
                    "activityImage" => "",
                    "facts" => [
                        [
                            "name" => "Server:",
                            "value" => $this->data["server"]['name']
                        ],
                        [
                            "name" => "Site",
                            "value" => "[". $this->data["site"]["name"] ."](http://". $this->data["site"]["name"] .")"
                        ],                        [
                            "name" => "Commit hash:",
                            "value" => "[". $this->data["commit_hash"] ."](". $this->data["commit_url"] .")"
                        ],
                        [
                            "name" => "Commit message",
                            "value" => $this->data["commit_message"]
                        ]
                    ],
                    "text" => ($this->data["status"] === 'success') ? $this->data["commit_author"] ." deployed some fresh code!" : "Something went wrong :/"
                ]
            ]
        ];
    }
}
