<?php

class CountPageTest extends SapphireTest {

	protected static $fixture_file = 'CountPageTest.yml';

	public function testCount() {
		$this->assertTrue(true, "Silly test");
	}

	public function testNameSpaceString()
    {
        $page = $this->objFromFixture('CountPage', 'page1');
        $this->assertEquals(DataObject::class, $page->getDataObjectString());

        $this->assertEquals('Ima' . 'ge', $page->getImageString());

    }

    public function testGetFields()
    {
        $controller = CountPage_Controller::singleton();

        $fields = $controller->getFormFields();

        // There's only on field
        foreach ($fields as $fieldGroup) {
            $this->assertEquals('MathGroup', $fieldGroup->id);
        }

    }

}
