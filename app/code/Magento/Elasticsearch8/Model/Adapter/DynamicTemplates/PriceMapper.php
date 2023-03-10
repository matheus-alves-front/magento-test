<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Elasticsearch8\Model\Adapter\DynamicTemplates;

/**
 * @inheridoc
 */
class PriceMapper implements MapperInterface
{
    /**
     * @inheritdoc
     */
    public function processTemplates(array $templates): array
    {
        $templates[] = [
            'price_mapping' => [
                'match' => 'price_*',
                'match_mapping_type' => 'string',
                'mapping' => [
                    'type' => 'double',
                    'store' => true,
                ],
            ],
        ];

        return $templates;
    }
}
