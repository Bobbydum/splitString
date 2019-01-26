<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class LocaleServiceTest
 *
 * @package LetyShops\FrontBundle\Service
 */
class CombineServiceTest extends WebTestCase
{
    private $combineService;

    /**
     *
     */
    protected function setUp()
    {
        self::bootKernel();
    }

    /**
     * @return array
     */
    public function addDataProviderCombination(): array
    {
        return [
            [
                [1, 2, 3],
            ],
        ];
    }

    /**
     * @dataProvider addDataProviderCombination
     *
     * @param $array
     * @param $combination
     */
    public function testGetCombination($array): void
    {
        $this->combineService = new CombineService();
        $result = $this->combineService->getCombination($array);

        foreach ($array as $string) {
            foreach ($result as $element) {
                $this->assertEquals(true, in_array($string, $element));
            }
        }
    }
}
