/**
 * KISS Components - Stimulus Controllers
 *
 * Import this file in your app.js and register the controllers:
 *
 * import {
 *   RangeController,
 *   InputNumberController,
 *   ThemeController,
 *   PopoverController,
 *   BreadcrumbController
 * } from '../_contao-kiss/assets/js';
 *
 * // or from vendor:
 * // import { ... } from '../vendor/digitaledinge/contao-kiss/assets/js';
 *
 * application.register('range', RangeController);
 * application.register('input-number', InputNumberController);
 * application.register('theme', ThemeController);
 * application.register('popover', PopoverController);
 * application.register('breadcrumb', BreadcrumbController);
 */

export { default as RangeController } from './controllers/range-controller';
export { default as InputNumberController } from './controllers/input-number-controller';
export { default as ThemeController } from './controllers/theme-controller';
export { default as PopoverController } from './controllers/popover-controller';
export { default as BreadcrumbController } from './controllers/breadcrumb-controller';
