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
        // Make sure there is at least an empty array
        if (!\is_array($this->varValue) || !$this->varValue[0])
        {
            $this->varValue = array(array(''));
        }

        $rows = [];

        $rowCount = \count($this->varValue);

        while ($rowCount < 7) {
            $this->varValue[] = [''];
            ++$rowCount;
        }

        for ($i=0, $c=\count($this->varValue); $i<$c; $i++) {
            $rows[] = [
                'time_from' => self::specialcharsValue($this->varValue[$i]['time_from'] ?? ''),
                'time_to' => self::specialcharsValue($this->varValue[$i]['time_to'] ?? ''),
            ];
        }

        return System::getContainer()->get('twig')->render('@Contao/backend/widget/opening_times.html.twig', [
            'id' => $this->strId,
            'rows' => $rows,
        ]);
    }
}
