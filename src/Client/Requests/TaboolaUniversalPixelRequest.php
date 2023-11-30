<?php
/*
 * @copyright (C) 2020 Michiel Keijts, Normit
 *
 *
 * @licence MIT
 */

namespace TaboolaApi\Client\Requests;

use TaboolaApi\Client\Requests\BaseRequest;

class TaboolaUniversalPixelRequest extends BaseRequest {

    /**
     * The Endpoint URL
     * @var string
     */
    protected $endpoint = "%s/universal_pixel/conversion_rule";

    public function getConversionRules(string $account_id)
    {
        return $this->request('get',"", sprintf($this->endpoint, $account_id));
    }
}
