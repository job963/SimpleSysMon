<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */

namespace Piwik\Plugins\SimpleSysMon;

use Piwik\Piwik;
use Piwik\WidgetsList;


/**
 */
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

    public function  addWidgets()
    {
        WidgetsList::add('Simple System Monitor', 'SimpleSysMon_widgetLiveLoad', 'SimpleSysMon', 'widgetLiveLoad');
    }
    
}
