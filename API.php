<?php

/**
 * Piwik - Open source web analytics
 * 
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * 
 * @copyright (c) 2011-2014, Joachim Barthel
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
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $sysData = array(
                'AvgLoad' => 9.99,
                'FreeMem' => 1
            );
        }
        else {
            $aMemInfo = $this->getMemInfo();
            $sysData = array(
                'AvgLoad' => $this->getSysLoad(),
                'NumCores' => $this->getNumCores(),
                'FreeMemVal' => $aMemInfo['MemFree'],
                'FreeMemProc' => $aMemInfo['MemFree']/$aMemInfo['MemTotal']*100.0,
                'UsedMemVal' => $aMemInfo['MemUsed'],
                'UsedMemProc' => $aMemInfo['MemUsed']/$aMemInfo['MemTotal']*100.0,
            );
        }

        return $sysData;
    }
    
    
    function getSysLoad()
    {
        $aAvgLoad = sys_getloadavg();
        return $aAvgLoad[0]/$this->getNumCores()*100.0;
    }
    
    
    function getMemInfo()
    {
	foreach(file('/proc/meminfo') as $ri)
		$m[strtok($ri, ':')] = intval(strtok(''));
	$meminfo['MemTotal'] = round($m['MemTotal'] / 1024);
	//$meminfo['MemFree'] = round(($m['MemTotal'] -($m['MemFree'] + $m['Buffers'] + $m['Cached'])) / 1024);
	$meminfo['MemFree'] = round($m['MemFree'] / 1024);
	$meminfo['MemUsed'] = round(($m['MemTotal']-($m['MemFree']+$m['Cached'])) / 1024);
	$meminfo['Cached'] = round($m['Cached'] / 1024);
	return $meminfo;
    }
    
    
    function getNumCores()
    {
        $cpuinfo = file_get_contents('/proc/cpuinfo');
        preg_match_all('/^processor/m', $cpuinfo, $matches);
 
        return count($matches[0]);
    }
	
}