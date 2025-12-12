<?php

declare(strict_types=1);

namespace DigitaleDinge\ContaoKiss\Widget;

use Contao\System;
use Contao\Widget;

class OpeningTimesTable extends Widget
{
    protected $blnSubmitInput = true;

    protected $strTemplate = 'be_widget';

    public function __construct($arrAttributes = null)
    {
        parent::__construct($arrAttributes);

        $this->preserveTags = true;
        $this->decodeEntities = true;
    }

    public function generate()
    {
        $rows = [];

        $rowCount = \count($this->varValue);

        while ($rowCount < 7) {
            $this->varValue[] = [''];
            ++$rowCount;
        }

        for ($i=0, $c=\count($this->varValue); $i<$c; $i++) {
            for ($j=0; $j<=3; $j++) {
                $rows[$i][$j] = self::specialcharsValue($this->varValue[$i][$j] ?? '');
            }
        }

        return System::getContainer()->get('twig')->render('@Contao/backend/widget/opening_times.html.twig', [
            'id' => $this->strId,
            'rows' => $rows,
        ]);
    }
}
