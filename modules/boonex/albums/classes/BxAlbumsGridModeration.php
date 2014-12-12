<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Albums Albums
 * @ingroup     TridentModules
 * 
 * @{
 */

bx_import('BxBaseModTextGridModeration');

class BxAlbumsGridModeration extends BxBaseModTextGridModeration
{
    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_albums';
        parent::__construct ($aOptions, $oTemplate);
    }
}

/** @} */
