<?php

use Contao\System;
use Symfony\Component\HttpFoundation\Request;
if (System::getContainer()->get('contao.routing.scope_matcher')
	->isBackendRequest(System::getContainer()->get('request_stack')->getCurrentRequest() ?? Request::create(''))
)
{
	$GLOBALS['TL_CSS'][] = 'bundles/digitaledingecontaokiss/dist/backend/css/contao-kiss.css';
	$GLOBALS['TL_JAVASCRIPT'][] = 'bundles/digitaledingecontaokiss/dist/backend/js/contao-kiss.js';
} 

/*

<?php

if (TL_MODE == 'BE')
{
	$objUser = \BackendUser::getInstance();
	
	if (!$objUser->isAdmin)
	{
		$GLOBALS['TL_CSS'][] = 'files/layout/css/backendLayoutRedakteur.css';
		$GLOBALS['TL_JAVASCRIPT'][] = 'files/layout/js/backendLayoutRedakteur.js';
	}
	else
	{
		$GLOBALS['TL_CSS'][] = 'files/layout/css/backendLayoutAdmin.css';    
		$GLOBALS['TL_JAVASCRIPT'][] = 'files/layout/js/backendLayoutAdmin.js';
	}
}  

*/