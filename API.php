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

use Piwik\DataTable;


class API extends \Piwik\Plugin\API
{

    function getLiveSysLoadData($idSite)
    {
        $aMemInfo = $this->_getMemInfo();
        $aDiskInfo = $this->_getDiskSpace();
        $aNetInfo = $this->_getNetTraffic();
        $aNetInfo['NetTotal'] = 1000.0;
        
        $sysData = array(
            'AvgLoad' => $this->_getSysLoad(),
            'NumCores' => (int) $this->_getNumCores(),
            
            'FreeMemVal' => $aMemInfo['MemFree'],
            'FreeMemProc' => $aMemInfo['MemFree']/$aMemInfo['MemTotal']*100.0,
            'CachedMemVal' => $aMemInfo['Cached'],
            'CachedMemProc' => $aMemInfo['Cached']/$aMemInfo['MemTotal']*100.0,
            'UsedMemVal' => $aMemInfo['MemUsed'],
            'UsedMemProc' => $aMemInfo['MemUsed']/$aMemInfo['MemTotal']*100.0,
            
            'UpNetVal' => $aNetInfo['Upload'],
            'UpNetProc' => $aNetInfo['Upload']/$aNetInfo['NetTotal']*100.0,
            'DownNetVal' => $aNetInfo['Download'],
            'DownNetProc' => $aNetInfo['Download']/$aNetInfo['NetTotal']*100.0,
            //'NetFreeVal' => $aNetInfo['Free'],
            //'NetFreeProc' => $aNetInfo['Free']/$aNetInfo['NetTotal']*100.0,
            
            'FreeDiskVal' => $aDiskInfo['Free'],
            'FreeDiskProc' => $aDiskInfo['Free']/$aDiskInfo['Total']*100.0,
            'UsedDiskVal' => $aDiskInfo['Used'],
            'UsedDiskProc' => $aDiskInfo['Used']/$aDiskInfo['Total']*100.0,
        );
        /*echo '$sysData<pre>';
        print_r($sysData);
        echo '</pre>';*/
        return $sysData;
    }
    
    
    function _getSysLoad()
    {
        if (function_exists('sys_getloadavg')) {
            $aAvgLoad = sys_getloadavg();
            $sysLoad = $aAvgLoad[0]/$this->_getNumCores()*100.0;
        }
        else {
            $sysLoad = 0;
        }
        return $sysLoad;
    }
    
    
    function _getMemInfo()
    {
        if (@file_exists('/proc/meminfo')) {
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
    
    
    function _getDiskSpace()
    {
        $diskinfo = array();
        
        $diskinfo['Free'] = disk_free_space('./') / (1024*1024);
        $diskinfo['Total'] = disk_total_space('./') / (1024*1024);
        $diskinfo['Used'] = $diskinfo['Total'] - $diskinfo['Free'];
        
        return $diskinfo;
    }
    
    
    function _getNetTraffic()
    {
        $netTraffic1 = $this->_getNetStat();
        sleep(1);
        $netTraffic2 = $this->_getNetStat();
        $netinfo = array();
        $netinfo['Upload'] = ($netTraffic2['receive']-$netTraffic1['receive']) / (1024.0 * 1.0);
        $netinfo['Download'] = ($netTraffic2['transmit']-$netTraffic1['transmit']) / (1024.0 * 1.0);
        return $netinfo;
    }
    
    
    function _getNetStat()
    {
        if (@file_exists('/proc/net/dev')) {
            $netStat = array();
            $netStat['receive'] = 0.0;
            $netStat['transmit'] = 0.0;
            foreach(file('/proc/net/dev') as $ri) {
                if (strpos($ri,':') !== false) {
                    $matches = preg_split('/\s+/', trim(str_replace(':',' ',$ri)));
                    $netStat['receive'] += $matches[1];
                    $netStat['transmit'] += $matches[9];
                }
            }
        }
        return $netStat;
    }
	
}