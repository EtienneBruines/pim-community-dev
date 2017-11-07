<?php

namespace Pim\Bundle\ConnectorBundle\tests\integration\Export\ProductQueryBuilder;

use Pim\Bundle\ConnectorBundle\tests\integration\Export\AbstractExportTestCase;

class ExportProductsByIdentifiersIntegration extends AbstractExportTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function loadFixtures() : void
    {
        $this->createProduct('product_1');
        $this->createProduct('product_2');
    }

    public function testProductExportWithFilterOnOneIdentifier()
    {
        $expectedCsv = <<<CSV
sku;categories;enabled;family;groups;PACK-groups;PACK-products;SUBSTITUTION-groups;SUBSTITUTION-products;UPSELL-groups;UPSELL-products;X_SELL-groups;X_SELL-products
product_1;;1;;;;;;;;;;

CSV;

        $config = [
            'filters' => [
                'data'      => [
                    [
                        'field'    => 'sku',
                        'operator' => 'IN',
                        'value'    => ['product_1']
                    ],
                ],
                'structure' => [
                    'scope'   => 'tablet',
                    'locales' => ['en_US'],
                ],
            ],
        ];

        $this->assertProductExport($expectedCsv, $config);
    }

    public function testProductExportWithFilterOnAListOfIdentifiers()
    {
        $expectedCsv = <<<CSV
sku;categories;enabled;family;groups;PACK-groups;PACK-products;SUBSTITUTION-groups;SUBSTITUTION-products;UPSELL-groups;UPSELL-products;X_SELL-groups;X_SELL-products
product_1;;1;;;;;;;;;;
product_2;;1;;;;;;;;;;

CSV;

        $config = [
            'filters' => [
                'data'      => [
                    [
                        'field'    => 'sku',
                        'operator' => 'IN',
                        'value'    => ['product_1', 'product_2']
                    ],
                ],
                'structure' => [
                    'scope'   => 'tablet',
                    'locales' => ['en_US'],
                ],
            ],
        ];

        $this->assertProductExport($expectedCsv, $config);
    }
}
