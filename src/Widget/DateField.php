<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Widget;

use Contao\StringUtil;
use Contao\TextField;

class DateField extends TextField
{
    public function __construct($arrAttributes = null)
    {
        parent::__construct($arrAttributes);
    }

    public function generate()
    {
        $strType = 'date';

        if (!$this->multiple)
        {
            return \sprintf(
                '<input type="%s" name="%s" id="ctrl_%s" class="tl_text%s" value="%s"%s data-action="focus->contao--scroll-offset#store" data-contao--scroll-offset-target="autoFocus">%s',
                $strType,
                $this->strName,
                $this->strId,
                $this->strClass ? ' ' . $this->strClass : '',
                self::specialcharsValue($this->varValue),
                $this->getAttributes(),
                $this->wizard
            );
        }

        // Return if field size is missing
        if (!$this->size)
        {
            return '';
        }

        if (!\is_array($this->varValue))
        {
            $this->varValue = array($this->varValue);
        }

        $arrFields = array();
        $blnPlaceholderArray = isset($this->arrAttributes['placeholder']) && \is_array($this->arrAttributes['placeholder']);

        for ($i=0; $i<$this->size; $i++)
        {
            $arrFields[] = \sprintf(
                '<input type="%s" name="%s[]" id="ctrl_%s" class="tl_text_%s" value="%s"%s%s data-action="focus->contao--scroll-offset#store">',
                $strType,
                $this->strName,
                $this->strId . '_' . $i,
                $this->size,
                self::specialcharsValue(@$this->varValue[$i]), // see #4979
                $blnPlaceholderArray && isset($this->arrAttributes['placeholder'][$i]) ? ' placeholder="' . StringUtil::specialcharsAttribute($this->arrAttributes['placeholder'][$i]) . '"' : '',
                $this->getAttributes($blnPlaceholderArray ? array('placeholder') : array())
            );
        }

        return \sprintf(
            '<div id="ctrl_%s" class="tl_text_field%s">%s</div>%s',
            $this->strId,
            $this->strClass ? ' ' . $this->strClass : '',
            implode(' ', $arrFields),
            $this->wizard
        );
    }
}
