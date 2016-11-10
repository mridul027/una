<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCoreBaseRepresentation UNA Core Base Representation Classes
 * @{
 */

/**
 * Site main menu representation.
 */
class BxBaseMenuSwitchLanguage extends BxTemplMenu
{
    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    public function getMenuItems ()
    {
        $this->loadData();

        return parent::getMenuItems();
    }

    protected function loadData()
    {
        $sLanguage = BxDolLanguages::getInstance()->getCurrentLangName();

        $this->setSelected('', $sLanguage);

        $oPermalink = BxDolPermalinks::getInstance();

        $aBaseLink = parse_url(BX_DOL_URL_ROOT);
        $sBaseLink = (!empty($aBaseLink['scheme']) ? $aBaseLink['scheme'] : 'http') . '://' . $aBaseLink['host'];
        $sPageLink = $oPermalink->unpermalink($sBaseLink . $_SERVER['REQUEST_URI'], false);

        $sPageParams = '';
        if(strpos($sPageLink, '?') !== false)
        	list($sPageLink, $sPageParams) = explode('?', $sPageLink);

        $aPageParams = array();
        if(!empty($sPageParams))
        	parse_str($sPageParams, $aPageParams);

		$aPageParamsAdd = array();
		if(!empty($_SERVER['QUERY_STRING'])) {
			parse_str($_SERVER['QUERY_STRING'], $aPageParamsAdd);
			if(!empty($aPageParamsAdd) && is_array($aPageParamsAdd))
				$aPageParams = array_merge($aPageParams, $aPageParamsAdd);
		}

        $aLanguages = BxDolLanguagesQuery::getInstance()->getLanguages(false, true);

        $aItems = array();
        foreach( $aLanguages as $sName => $sLang ) {
            $aPageParams['lang'] = $sName;

            $aItems[] = array(
                'id' => $sName,
                'name' => $sName,
                'class' => '',
                'title' => $this->getItemTitle($sName, $sLang),
                'target' => '_self',
                'icon' => '',
                'link' => bx_html_attribute(bx_append_url_params($oPermalink->permalink($sPageLink), $aPageParams)),
                'onclick' => ''
            );
        }

        $this->_aObject['menu_items'] = $aItems;
    }

    protected function getItemTitle($sName, $sTitle)
    {
    	return genFlag($sName);
    }
}

/** @} */
