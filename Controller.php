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

use Piwik\API\Request;
use Piwik\Piwik;
//use Piwik\Plugin\Settings;
//use Piwik\Settings\UserSetting;
//use Piwik\Settings\SystemSetting;
use Piwik\View;


class Controller extends \Piwik\Plugin\Controller
{
    /** @var UserSetting */
    //public $autoRefresh;

    /** @var UserSetting */
    //public $refreshInterval;
    
        
    /**
     * Container for the Live Revenue widget, which add a scheduled refreshing
     **/
    function widgetLiveLoad()
    {
        /*
        $this->autoRefresh = new UserSetting('autoRefresh', Piwik::translate('SimpleSysMon_AutoRefreshLabel') );
        echo Settings::getSettingValue($this->autoRefresh);
        */
        //$this->refreshInterval = new UserSetting('refreshInterval', Piwik::translate('SimpleSysMon_RefreshInterval') );
        //echo Settings::getSettingValue('refreshInterval');
        
        $settings = new Settings('SimpleSysMon');
        $autoRefresh  = $settings->autoRefresh->getValue();
        $refreshInterval  = $settings->refreshInterval->getValue();

        
        $output = '';
        if ($autoRefresh == 1) {
            $output .= "<SCRIPT LANGUAGE='javascript'>
                    var reloadLiveLoad;
                    $('document').ready(function(){
                        if (typeof reloadLiveLoad === 'undefined')
                            reloadLiveLoad = setInterval(refreshLiveLoad,{$refreshInterval});
                    });
                    function refreshLiveLoad () {
                        $('[widgetid=widgetSimpleSysMonwidgetLiveLoad]').dashboardWidget('reload', false, true);
                    }
                </SCRIPT>";
        }

        $output .= $this->widgetLiveLoadTable();

        return $output;
    }
    

    /**
     * This widget shows a table which displays the last orders
     **/
    function widgetLiveLoadTable()
    {
        $result = Request::processRequest('SimpleSysMon.getLiveSysLoadData');

        $view = new View('@SimpleSysMon/widgetLiveSysLoad.twig');
        $this->setBasicVariablesView($view);
        
        $settings = new Settings('SimpleSysMon');
        $memoryDisplay  = $settings->memoryDisplay->getValue();

        $view->sysLoad = $result['AvgLoad'];
        $view->numCores = $result['NumCores'];
        if ($memoryDisplay == 'free') {
            $view->memLabel = Piwik::translate('SimpleSysMon_FreeMemory');
            $view->memVal = $result['FreeMemVal'];
            $view->memProc = $result['FreeMemProc'];
        }
        else {
            $view->memLabel = Piwik::translate('SimpleSysMon_UsedMemory');
            $view->memVal = $result['UsedMemVal'];
            $view->memProc = $result['UsedMemProc'];
        }

        return $view->render();
    }
    
}