<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCoreBaseRepresentation UNA Core Base Representation Classes
 * @{
 */

/**
 * System service for login form functionality.
 */
class BxBaseServiceLogin extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    public function serviceTest ($n = 1)
    {
        return $n*2;
    }

    public function serviceMemberAuthCode($aAuthTypes)
    {
        $aTmplButtons = array();
        foreach($aAuthTypes as $iKey => $aItems) {
            $sTitle = _t($aItems['Title']);

            $aTmplButtons[] = array(
                'href' => !empty($aItems['Link']) ? BX_DOL_URL_ROOT . $aItems['Link'] : 'javascript:void(0)',
                'bx_if:show_onclick' => array(
                    'condition' => !empty($aItems['OnClick']),
                    'content' => array(
                        'onclick' => 'javascript:' . $aItems['OnClick']
                    )
                ),
                'bx_if:show_icon' => array(
                    'condition' => !empty($aItems['Icon']),
                    'content' => array(
                        'icon' => $aItems['Icon']
                    )
                ),
                'title' => !empty($sTitleKey) ? _t($sTitleKey, $sTitle) : $sTitle
            );
        }

        BxDolTemplate::getInstance()->addCss(array('auth.css'));

        return BxDolTemplate::getInstance()->parseHtmlByName('auth.html', array(
            'bx_repeat:buttons' => $aTmplButtons
        ));
    }

    public function serviceLoginFormOnly ($sParams = '', $sForceRelocate = '')
    {
    	if(strpos($sParams, 'no_join_text') === false)
    		$sParams = ($sParams != '' ? ' ' : '') . 'no_join_text';

    	return $this->serviceLoginForm($sParams, $sForceRelocate);
    }

    public function serviceLoginForm ($sParams = '', $sForceRelocate = '')
    {
        if (isLogged()) {
            return false;
        }
        // get all auth types
        $aAuthTypes = BxDolDb::getInstance()->fromCache('sys_objects_auths', 'getAll', 'SELECT * FROM `sys_objects_auths`');

        $oForm = BxDolForm::getObjectInstance('sys_login', 'sys_login');

        $sCustomHtmlBefore = '';
        $sCustomHtmlAfter = '';
        bx_alert('profile', 'show_login_form', 0, 0, array('oForm' => $oForm, 'sParams' => &$sParams, 'sCustomHtmlBefore' => &$sCustomHtmlBefore, 'sCustomHtmlAfter' => &$sCustomHtmlAfter, 'aAuthTypes' => &$aAuthTypes));

        if ($sForceRelocate && 0 === mb_stripos($sForceRelocate, BX_DOL_URL_ROOT))
            $oForm->aInputs['relocate']['value'] = $sForceRelocate;
        elseif ('homepage' == $sForceRelocate)
            $oForm->aInputs['relocate']['value'] = BX_DOL_URL_ROOT;

        $sFormCode = $oForm->getCode();

        $sJoinText = '';
        if (strpos($sParams, 'no_join_text') === false) {
            $sJoinText = '<hr class="bx-def-hr bx-def-margin-sec-topbottom" /><div>' . _t('_sys_txt_login_description', BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=create-account')) . '</div>';
        }

        BxDolTemplate::getInstance()->addJs(array('jquery.form.min.js'));

        $sAuth = $this->serviceMemberAuthCode($aAuthTypes);

        return $sCustomHtmlBefore . $sAuth . $sFormCode . $sCustomHtmlAfter . $sJoinText;

    }

}

/** @} */
