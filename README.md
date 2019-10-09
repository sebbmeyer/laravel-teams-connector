## Laravel Microsoft Teams Connector

A Laravel 5/6 package to send notifications to Microsoft Teams by using "Incoming Webhook". 
This package also contains a card to send a Laravel Forge deployment notification, because Microsoft Teams notification is not supported at the moment. The cards in this package only use the old MessageCard format, because AdpativeCard is not supported by Microsoft Teams at the moment. I will update the card format, when AdpativeCard format is supported.

![Forge card](https://preview.ibb.co/dFzDR8/forge_card.png)

## Package Installation - Composer

You can install the package via composer:

```bash
composer require sebbmeyer/laravel-teams-connector
```

### Laravel
If you are using Laravel 5.5 and up, the service provider will automatically get registered.

For older versions of Laravel (<5.5), you have to add the service provider and alias to config/app.php:

```php
Sebbmyr\LaravelTeams\TeamsConnectorServiceProvider::class,
```

You can optionally use the facade for shorter code. Add this to your facades:

```php
'TeamsConnector' => Sebbmyr\LaravelTeams\Facades\TeamsConnector::class,
```

### Lumen

For Lumen usage, the service provider should be registered manually by adding the following line in `bootstrap/app.php`:  

```php
$app->register(Sebbmyr\LaravelTeams\TeamsConnectorServiceProvider::class);
```

### Configuration

For this package to work, you need to configure an "Incomming Webhook" connector in your targeted Teams channel and copy the url into a config file that you can publish like this:

```bash
php artisan vendor:publish --provider="Sebbmyr\LaravelTeams\TeamsConnectorServiceProvider"
```

... or you simple add the following to your `.env` file:

```
MICROSOFT_TEAMS_WEBHOOK=<incoming_webhook_url>
```

### Usage

How to send simple notification look at the readme of this package [sebbmeyer/php-microsoft-teams-connector](https://github.com/sebbmeyer/php-microsoft-teams-connector)

### Custom card - ForgeCard

You can create your own cards for every purpose you need, just extend the **AbstractCard** class and implement the `getMessage()` function.

```php
\\ Sebbmyr\LaravelTeams\Cards\ForgeCard.php
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
```

### License

This Microsoft Teams connector for Laravel is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
