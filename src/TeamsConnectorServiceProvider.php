<?php

namespace Sebbmyr\Teams;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

/**
 * Teams connector service provider
 */
class TeamsConnectorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__ . '/../config/teams.php';
        $this->publishes([$configPath => config_path('teams.php')], 'config');

        // Don't boot teams connector if it is not configured.
        if ($this->stop() === true) {
            return;
        }

        $this->app->bind('TeamsConnector', function () {
            $webhookUrl = getenv('MICROSOFT_TEAMS_WEBHOOK') ?: $this->app->config->get('teams.webhook', null);
        
            return new \Sebbmyr\Teams\TeamsConnector($webhookUrl);
        });
        $this->app->alias('TeamsConnector', 'Sebbmyr\Teams\TeamsConnector');
    }
 
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Don't register teams connector if it is not configured.
        if ($this->stop() === true) {
            return;
        }

        $configPath = __DIR__ . '/../config/teams.php';
        $this->mergeConfigFrom($configPath, 'teams');
    }

    /**
     * Check if we should prevent the service from registering
     *
     * @return boolean
     */
    public function stop()
    {

        $webhook = getenv('MICROSOFT_TEAMS_WEBHOOK') ?: $this->app->config->get('teams.webhook', null);
        $hasWebhook = empty($webhook) === false;

        return $hasWebhook === false;
    }
}
