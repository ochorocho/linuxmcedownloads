<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Linuxmce.' . $_EXTKEY,
	'Listdownloads',
	array(
		'Listing' => 'show'
	),
	// non-cacheable actions
	array(
		'Listing' => 'show'		
	)
);
