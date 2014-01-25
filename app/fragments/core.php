<?php

use Curious\Fragment;
use Nette\Templating\FileTemplate;

/**
 * @fragment initTemplate
 */
function initTemplate(FileTemplate $template, Fragment $fragment, Directories $directories)
{
	$file = $directories->getTemplatesDirectory() . '/' . $fragment->getName() . '.latte';
	if (file_exists($file)) {
		$template->setFile($file);
	}
}

/**
 * @fragment initNavigation
 * @following initTemplate
 */
function initNavigation(FileTemplate $template, Navigation $navigation, Fragment $fragment)
{
	if ($navigation->hasFragment($fragmentName = $fragment->getName())) {
		$navigation->setActiveFragment($fragmentName);
	}
	$template->navigation = $navigation;
}

/**
 * @fragment 404
 * @following initNavigation
 */
function render404(Directories $directories, FileTemplate $template)
{
	http_response_code(404);
	$template->setFile($directories->getTemplatesDirectory() . '/404.latte');
}