<?php

/**
 * Piwik - Open source web analytics
 * 
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * 
 * @copyright (c) 2014, 2015 Joachim Barthel
 * @author Joachim Barthel <jobarthel@gmail.com>
 * @category Piwik_Plugins
 * @package SimpleSysMon
 **/

namespace Piwik\Plugins\SimpleSysMon;

use Piwik\API\Request;
use Piwik\Piwik;
use Piwik\View;


class Controller extends \Piwik\Plugin\Controller
{

    /**
     * Container for the Live System Load widget, which adds the auto-refreshing by javascript
     **/
    function widgetLiveLoad()
    {
        $settings = new Settings('SimpleSysMon');
        $autoRefresh  = $settings->autoRefresh->getValue();
        $refreshInterval  = $settings->refreshInterval->getValue() * 1000;
        
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
     * This widget shows a table with cpu load and memory use
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

    
    /**
     * Container for the Live System Load Bars widget, which adds the auto-refreshing
     **/
    function widgetLiveLoadBars()
    {
        $settings = new Settings('SimpleSysMon');
        $autoRefresh  = $settings->autoRefresh->getValue();
        $refreshInterval  = $settings->refreshInterval->getValue() * 1000;
        
        $output = '';
        if ($autoRefresh == 1) {
            $output .= "<SCRIPT LANGUAGE='javascript'>
                    var reloadLiveLoadBars;
                    $('document').ready(function(){
                        if (typeof reloadLiveLoadBars === 'undefined')
                            reloadLiveLoadBars = setInterval(refreshLiveLoadBars,{$refreshInterval});
                    });
                    function refreshLiveLoadBars () {
                        $('[widgetid=widgetSimpleSysMonwidgetLiveLoadBars]').dashboardWidget('reload', false, true);
                    }
                </SCRIPT>";
        }

        $output .= $this->elementLiveLoadBars();

        return $output;
    }
    

    /**
     * This widget shows horizontal bars with cpu load and memory use
     **/
    function elementLiveLoadBars()
    {
        $result = Request::processRequest('SimpleSysMon.getLiveSysLoadData');

        $view = new View('@SimpleSysMon/widgetLiveSysLoadBars.twig');
        $this->setBasicVariablesView($view);
        
        $view->sysLoad = array(
                            'avgload' => array( 'used' => round($result['AvgLoad'],0),
                                                'free' => round(100.0 - $result['AvgLoad'],0) ),
                            'memory'  => array( 'used' => round($result['UsedMemProc'],0),
                                                'cached' => round($result['CachedMemProc'],0),
                                                'free' => round(100.0 - $result['UsedMemProc'],0) ),
                            'net'     => array( 'upload' => round($result['UpNetProc'],0),
                                                'download' => round($result['DownNetProc'],0),
                                                'free' => round(100.0 - $result['DownNetProc'],0) ),
                            'disk'    => array( 'used' => round($result['UsedDiskProc'],0),
                                                'free' => round($result['FreeDiskProc'],0) )
                            );
        return $view->render();
    }
    
}