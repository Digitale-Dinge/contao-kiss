<?php 

namespace DigitaleDinge\ContaoKiss;

use Composer\Script\Event;

class ComposerScripts
{
	public static function copyFiles(Event $event)
	{
		$vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
		$projectRoot = dirname($vendorDir);
		
		$source = __DIR__ . '/Twig/AppExtension.php';
		$target = $projectRoot . '/src/Twig/AppExtension.php';

		if (!file_exists($target)) {
			if (!is_dir(dirname($target))) {
				mkdir(dirname($target), 0777, true);
			}
			copy($source, $target);
			echo "✅ AppExtension.php wurde erfolgreich nach $target kopiert.\n";
		} else {
			echo "⚠️ AppExtension.php existiert bereits – kein Kopiervorgang.\n";
		}
	}
}
