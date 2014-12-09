<?php

/**
 * Piwik - Open source web analytics
 * 
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * 
 * @copyright (c) 2014, Joachim Barthel
 * @author Joachim Barthel <jobarthel@gmail.com>
 * @category Piwik_Plugins
 * @package SimpleSysMon
 **/

namespace Piwik\Plugins\SimpleSysMon;

use Piwik\Piwik;
use Piwik\WidgetsList;


class SimpleSysMon extends \Piwik\Plugin
{
    /**
     * @see Piwik\Plugin::getListHooksRegistered
     */
    public function getListHooksRegistered()
    {
        return array(
            'WidgetsList.addWidgets'   => 'addWidgets'
        );
    }

    
    /**
     * Register the widgets
     */
    public function  addWidgets()
    {
        WidgetsList::add('Simple System Monitor', 'SimpleSysMon_widgetLiveLoad', 'SimpleSysMon', 'widgetLiveLoad');
        WidgetsList::add('Simple System Monitor', 'SimpleSysMon_widgetLiveLoadBars', 'SimpleSysMon', 'widgetLiveLoadBars');
    }
    
}
