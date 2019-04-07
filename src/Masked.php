<?php

namespace Jobcerto\NovaMasked;

use Laravel\Nova\Fields\Field;

class Masked extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'masked-field';

    public function format(...$masks)
    {
        return $this->withMeta(['format' => $masks]);
    }

    private function mask($val, $mask)
    {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k])) {
                    $maskared .= $val[$k++];
                }
            } else {

                if (isset($mask[$i])) {
                    $maskared .= $mask[$i];
                }
            }
        }

        return $maskared;
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $format = collect($this->meta['format'])->filter(function ($format) {
            return strlen(preg_replace('/[^#]/', '', $format)) === strlen($this->value);
        })->first();
        return array_merge(parent::jsonSerialize(), [
            'value' => $this->mask($this->value, $format),
        ]);
    }
}
