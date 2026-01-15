/**
 * KISS Components - Stimulus Controllers
 * 
 * Import this file in your app.js and register the controllers:
 * 
 * import { RangeController, InputNumberController, ThemeController } from '../_contao-kiss/assets/js';
 * // or from vendor:
 * // import { RangeController, InputNumberController, ThemeController } from '../vendor/digitaledinge/contao-kiss/assets/js';
 * 
 * application.register('range', RangeController);
 * application.register('input-number', InputNumberController);
 * application.register('theme', ThemeController);
 */

export { default as RangeController } from './controllers/range-controller';
export { default as InputNumberController } from './controllers/input-number-controller';
export { default as ThemeController } from './controllers/theme-controller';
