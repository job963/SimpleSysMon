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

use Piwik\DataTable;


class API extends \Piwik\Plugin\API
{

    function getLiveSysLoadData($idSite)
    {
        $aMemInfo = $this->_getMemInfo();
        $sysData = array(
            'AvgLoad' => $this->_getSysLoad(),
            'NumCores' => (int) $this->_getNumCores(),
            'FreeMemVal' => $aMemInfo['MemFree'],
            'FreeMemProc' => $aMemInfo['MemFree']/$aMemInfo['MemTotal']*100.0,
            'UsedMemVal' => $aMemInfo['MemUsed'],
            'UsedMemProc' => $aMemInfo['MemUsed']/$aMemInfo['MemTotal']*100.0,
        );

        return $sysData;
    }
    
    
    function _getSysLoad()
    {
        if (function_exists('sys_getloadavg')) {
            $aAvgLoad = sys_getloadavg();
            $sysLoad = $aAvgLoad[0]/$this->getNumCores()*100.0;
        }
        else {
            $sysLoad = 0;
        }
        return $sysLoad;
    }
    
    
    function _getMemInfo()
    {
        if (@file_exists('/proc/cpuinfo')) {
            foreach(file('/proc/meminfo') as $ri)
                    $m[strtok($ri, ':')] = intval(strtok(''));
            $meminfo['MemTotal'] = round($m['MemTotal'] / 1024);
            $meminfo['MemFree'] = round($m['MemFree'] / 1024);
            $meminfo['MemUsed'] = round(($m['MemTotal']-($m['MemFree']+$m['Cached'])) / 1024);
            $meminfo['Cached'] = round($m['Cached'] / 1024);
        }
        else {
            $meminfo['MemTotal'] = 0.001; // for avoiding div0
            $meminfo['MemFree'] = 0;
            $meminfo['MemUsed'] = 0;
            $meminfo['Cached'] = 0;
        }		
        return $meminfo;
    }
    
    
    function _getNumCores()
    {
        if (@file_exists('/proc/cpuinfo')) {
            $cpuinfo = file_get_contents('/proc/cpuinfo');
            preg_match_all('/^processor/m', $cpuinfo, $matches);
            $numCores = count($matches[0]);
        }
        else {
            $numCores = 0.001; // for avoiding div0
        }

        return $numCores;
    }
	
}