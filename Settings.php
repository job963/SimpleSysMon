<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Piwik\Plugins\SimpleSysMon;

use Piwik\Piwik;
use Piwik\Settings\SystemSetting;
use Piwik\Settings\UserSetting;

class Settings extends \Piwik\Plugin\Settings
{
    /** @var UserSetting */
    public $autoRefresh;

    /** @var UserSetting */
    public $refreshInterval;

    /** @var UserSetting */
    public $memoryDisplay;
    
    
    protected function init()
    {
        $this->setIntroduction( Piwik::translate('SimpleSysMon_SettingsDescription') );

        $this->createAutoRefreshSetting();
        $this->createRefreshIntervalSetting();
        $this->createMemoryDisplaySetting();
    }
    

    private function createAutoRefreshSetting()
    {
        $this->autoRefresh        = new UserSetting('autoRefresh', Piwik::translate('SimpleSysMon_AutoRefreshLabel') );
        $this->autoRefresh->type  = static::TYPE_BOOL;
        $this->autoRefresh->uiControlType = static::CONTROL_CHECKBOX;
        $this->autoRefresh->description   = Piwik::translate('SimpleSysMon_AutoRefreshDescription');
        $this->autoRefresh->defaultValue  = TRUE;

        $this->addSetting($this->autoRefresh);
    }
    

    private function createRefreshIntervalSetting()
    {
        $this->refreshInterval        = new UserSetting('refreshInterval', Piwik::translate('SimpleSysMon_RefreshIntervalLabel') );
        $this->refreshInterval->type  = static::TYPE_INT;
        $this->refreshInterval->uiControlType = static::CONTROL_TEXT;
        $this->refreshInterval->uiControlAttributes = array('size' => 3);
        $this->refreshInterval->description     = Piwik::translate('SimpleSysMon_RefreshIntervalDescription');
        $this->refreshInterval->inlineHelp      = Piwik::translate('SimpleSysMon_RefreshIntervalHelp');
        $this->refreshInterval->defaultValue    = '30';
        $this->refreshInterval->validate = function ($value, $setting) {
            if ($value < 5) {
                throw new \Exception( Piwik::translate('SimpleSysMon_ErrMsgWrongValue') );
            }
        };

        $this->addSetting($this->refreshInterval);
    }

    
    private function createMemoryDisplaySetting()
    {
        $this->memoryDisplay        = new UserSetting('memoryDisplay', Piwik::translate('SimpleSysMon_MemoryDisplayLabel') );
        $this->memoryDisplay->uiControlType = static::CONTROL_RADIO;
        $this->memoryDisplay->description   = Piwik::translate('SimpleSysMon_MemoryDisplayDescription');
        $this->memoryDisplay->availableValues = array(
                                            'free' => Piwik::translate('SimpleSysMon_FreeMemory'), 
                                            'used' => Piwik::translate('SimpleSysMon_UsedMemory')
                                            );

        $this->addSetting($this->memoryDisplay);
    }
    
}