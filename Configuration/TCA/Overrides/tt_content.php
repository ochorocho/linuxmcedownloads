<?php

$pluginSignature = 'linuxmcedownloads_listdownloads';

 $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';

 \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:linuxmcedownloads/Configuration/FlexForms/ff_linuxmcedownloads.xml'
);

