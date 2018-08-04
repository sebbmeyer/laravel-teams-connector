<?php
namespace Sebbmyr\LaravelTeams\Facades;

use Illuminate\Support\Facades\Facade as BaseFacade;

class TeamsConnector extends BaseFacade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'TeamsConnector';
    }
}
