<?php

namespace BuildTestDataPlugin;

use Codeages\PluginBundle\System\PluginBase;

class BuildTestDataPlugin extends PluginBase
{
    public function boot()
    {
        parent::boot();
    }

    public function getEnabledExtensions()
    {
        return array('DataTag', 'StatusTemplate', 'DataDict', 'NotificationTemplate');
    }

}
