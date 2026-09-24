<?php

declare(strict_types=1);

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

return new Configuration()
    // These package are used for the kiss content elements
    ->ignoreErrorsOnPackage('lukasbableck/contao-svg-icon-picker-bundle', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('madeyourday/contao-rocksolid-custom-elements', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('mvo/contao-group-widget', [ErrorType::UNUSED_DEPENDENCY])

    // These packages are used within the backend UI
    ->ignoreErrorsOnPackage('zoglo/contao-collection-widget', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackage('zoglo/contao-radio-image-widget', [ErrorType::UNUSED_DEPENDENCY])

    // This package is required for QoL reasons
    ->ignoreErrorsOnPackage('twig/intl-extra', [ErrorType::UNUSED_DEPENDENCY])

    // The manager plugin is a dev dependency because it is only required in the
    // managed edition.
    ->ignoreErrorsOnPackage('contao/manager-plugin', [ErrorType::DEV_DEPENDENCY_IN_PROD])

    // The Twig CS Fixer rules are autoloaded for projects that lint with them,
    // twig-cs-fixer itself is installed in vendor-bin/
    ->ignoreErrorsOnPath(__DIR__.'/vendor-bin/twig-cs-fixer/src', [ErrorType::UNKNOWN_CLASS, ErrorType::SHADOW_DEPENDENCY])
;
