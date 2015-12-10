<?php
/**
 * @file      UpdatePage.php
 * @date      12/7/2015
 * @time      1:45 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 Agiel K. Saputra
 * @license   http://www.writesdown.com/license/
 */


namespace tests\codeception\backend\_pages\_taxonomy;


use yii\codeception\BasePage;

/**
 * Class UpdatePage
 *
 * @property \tests\codeception\frontend\FunctionalTester | \tests\codeception\frontend\AcceptanceTester | \tests\codeception\backend\FunctionalTester | \tests\codeception\backend\AcceptanceTester $actor
 *
 * @package tests\codeception\backend\_pages\_taxonomy
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class UpdatePage extends BasePage
{
    /**
     * @var array
     */
    public $route = ['/taxonomy/update', 'id' => 2];

    /**
     * @param array $data
     */
    public function submit(array $data)
    {
        foreach ($data as $field => $value) {
            $this->actor->fillField('input[name="Taxonomy[' . $field . ']"]', $value);
        }
        $this->actor->checkOption('#taxonomy-taxonomy_hierarchical');
        $this->actor->checkOption('#taxonomy-taxonomy_smb');
        $this->actor->click('Update', '#taxonomy-form');

        if (method_exists($this->actor, 'wait')) {
            $this->actor->wait(3);
        }
    }
}