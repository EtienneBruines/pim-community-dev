<?php

namespace Pim\Bundle\ConnectorBundle\tests\integration\Export\ProductQueryBuilder;

use Pim\Bundle\ConnectorBundle\tests\integration\Export\AbstractExportTestCase;

class ExportProductsByMultiSelectIntegration extends AbstractExportTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function loadFixtures() : void
    {
        $this->createProduct('product_option_A', [
            'values'     => [
                'a_multi_select' => [
                    ['data' => ['optionA'], 'locale' => null, 'scope' => null]
                ]
            ]
        ]);

        $this->createProduct('product_option_B', [
            'values'     => [
                'a_multi_select' => [
                    ['data' => ['optionB'], 'locale' => null, 'scope' => null]
                ]
            ]
        ]);

        $this->createProduct('product_option_A_B', [
            'values'     => [
                'a_multi_select' => [
                    ['data' => ['optionA', 'optionB'], 'locale' => null, 'scope' => null]
                ]
            ]
        ]);

        $this->createProduct('product_without_option', [
            'values'     => [
                'a_multi_select' => [
                    ['data' => [], 'locale' => null, 'scope' => null]
                ]
            ]
        ]);

        $this->createProduct('product_without_option_attribute');

    }

    public function testProductExportByFilteringOnOneOption()
    {
        $expectedCsv = <<<CSV
sku;categories;enabled;family;groups;a_multi_select;PACK-groups;PACK-products;SUBSTITUTION-groups;SUBSTITUTION-products;UPSELL-groups;UPSELL-products;X_SELL-groups;X_SELL-products
product_option_A;;1;;;optionA;;;;;;;;
product_option_A_B;;1;;;optionA,optionB;;;;;;;;

CSV;

        $config = [
            'filters' => [
                'data'      => [
                    [
                        'field'    => 'a_multi_select',
                        'operator' => 'IN',
                        'value'    => ['optionA'],
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

    public function testProductExportByFilteringOnTwoOptions()
    {
        $expectedCsv = <<<CSV
sku;categories;enabled;family;groups;a_multi_select;PACK-groups;PACK-products;SUBSTITUTION-groups;SUBSTITUTION-products;UPSELL-groups;UPSELL-products;X_SELL-groups;X_SELL-products
product_option_A;;1;;;optionA;;;;;;;;
product_option_B;;1;;;optionB;;;;;;;;
product_option_A_B;;1;;;optionA,optionB;;;;;;;;

CSV;

        $config = [
            'filters' => [
                'data'      => [
                    [
                        'field'    => 'a_multi_select',
                        'operator' => 'IN',
                        'value'    => ['optionA', 'optionB'],
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

    public function testProductExportByFilteringWithEmpty()
    {
        $expectedCsv = <<<CSV
sku;categories;enabled;family;groups;PACK-groups;PACK-products;SUBSTITUTION-groups;SUBSTITUTION-products;UPSELL-groups;UPSELL-products;X_SELL-groups;X_SELL-products
product_without_option;;1;;;;;;;;;;
product_without_option_attribute;;1;;;;;;;;;;

CSV;

        $config = [
            'filters' => [
                'data'      => [
                    [
                        'field'    => 'a_multi_select',
                        'operator' => 'EMPTY',
                        'value'    => [],
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

    public function testProductExportByFilteringWithAnEmptyList()
    {
        $expectedCsv = <<<CSV

CSV;

        $config = [
            'filters' => [
                'data'      => [
                    [
                        'field'    => 'a_multi_select',
                        'operator' => 'IN',
                        'value'    => [],
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
