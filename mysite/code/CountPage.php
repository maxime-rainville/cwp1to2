<?php

class CountPage extends Page {
	private static $description = 'Entry to the site. Has carousel, featured panels, quicklinks and news summaries';

    public function requireDefaultRecords() {
        parent::requireDefaultRecords();


        // default pages
        if (self::get()->filter('URLSegment', 'count-me')->count() === 0) {
            $countPage = new self();
            $countPage->Title = _t('SiteTree.CountPageTitle', 'Maths is fun');
            $countPage->Content = _t('SiteTree.CountPageCount', 'Lets do some basic maths');
            $countPage->URLSegment = 'count-me';
            $countPage->write();
            $countPage->publish('Stage', 'Live');
            $countPage->flushCache();
            DB::alteration_message('Count page created', 'created');
        }
    }

    public function getDataObjectString()
    {
        return "DataObject";
    }

    /**
     * @return string
     * @skipUpgrade
     */
    public function getImageString()
    {
        return "Image";
    }
}

class CountPage_Controller extends Page_Controller {

    private static $allowed_actions = array (
        'Form'
    );

    public function IncrementByTen($value)
    {
        return $value + 10;
    }

    public function Form()
    {
        return Form::create(
            $this,
            'Form',
            $this->getFormFields(),
            $this->getFormActions()
        );
    }

    /**
     * @return FieldList
     */
    public function getFormFields()
    {
        $group = FieldGroup::create(
            NumericField::create('LeftNumber'),
            NumericField::create('RightNumber')
        )->setID('MathGroup');

        return FieldList::create(
            $group
        );
    }

    public function getFormActions()
    {
        return FieldList::create(
            FormAction::create('doAdd', '+'),
            FormAction::create('doSub', '-'),
            FormAction::create('doMul', '×'),
            FormAction::create('doDiv', '÷')
        );
    }

    public function doAdd($data, $form) {
        $results = $this->maths('add', intval($data['LeftNumber']), intval($data['RightNumber']));
        return $this->renderResults($results);
    }

    public function doSub($data, $form) {
        $results = $this->maths('sub', intval($data['LeftNumber']), intval($data['RightNumber']));
        return $this->renderResults($results);
    }

    public function doMul($data, $form) {
        $results = $this->maths('mul', intval($data['LeftNumber']), intval($data['RightNumber']));
        return $this->renderResults($results);
    }

    public function doDiv($data, $form) {
        $results = $this->maths('div', intval($data['LeftNumber']), intval($data['RightNumber']));
        return $this->renderResults($results);
    }

    public function maths($ops, $a, $b) {
        $val = 0;
        switch ($ops) {
            case 'add':
                $val = $a+$b;
                break;
            case 'sub':
                $val = $a-$b;
                break;
            case 'mul':
                $val = $a*$b;
                break;
            case 'div':
                $val = $a/$b;
                break;
        }

        return $val;
    }

    public function renderResults($results)
    {
        $content = ArrayData::create(['Value' => $results])->renderWith('ComputedResults');
        return $this->customise(['Content' => $content])->render();
    }

    public function requireDefaultRecords() {
        parent::requireDefaultRecords();

        // default pages
        if($this->class == 'SiteTree' && $this->config()->create_default_pages) {
            if(!SiteTree::get_by_link(Config::inst()->get('RootURLController', 'default_homepage_link'))) {
                $homepage = new Page();
                $homepage->Title = _t('SiteTree.DEFAULTHOMETITLE', 'Home');
                $homepage->Content = _t('SiteTree.DEFAULTHOMECONTENT', '<p>Welcome to SilverStripe! This is the default homepage. You can edit this page by opening <a href="admin/">the CMS</a>.</p><p>You can now access the <a href="http://docs.silverstripe.org">developer documentation</a>, or begin the <a href="http://www.silverstripe.org/learn/lessons">SilverStripe lessons</a>.</p>');
                $homepage->URLSegment = Config::inst()->get('RootURLController', 'default_homepage_link');
                $homepage->Sort = 1;
                $homepage->write();
                $homepage->publish('Stage', 'Live');
                $homepage->flushCache();
                DB::alteration_message('Home page created', 'created');
            }

            if(DB::query("SELECT COUNT(*) FROM \"SiteTree\"")->value() == 1) {
                $aboutus = new Page();
                $aboutus->Title = _t('SiteTree.DEFAULTABOUTTITLE', 'About Us');
                $aboutus->Content = _t('SiteTree.DEFAULTABOUTCONTENT', '<p>You can fill this page out with your own content, or delete it and create your own pages.<br /></p>');
                $aboutus->Sort = 2;
                $aboutus->write();
                $aboutus->publish('Stage', 'Live');
                $aboutus->flushCache();
                DB::alteration_message('About Us page created', 'created');

                $contactus = new Page();
                $contactus->Title = _t('SiteTree.DEFAULTCONTACTTITLE', 'Contact Us');
                $contactus->Content = _t('SiteTree.DEFAULTCONTACTCONTENT', '<p>You can fill this page out with your own content, or delete it and create your own pages.<br /></p>');
                $contactus->Sort = 3;
                $contactus->write();
                $contactus->publish('Stage', 'Live');
                $contactus->flushCache();
                DB::alteration_message('Contact Us page created', 'created');
            }
        }

        // schema migration
        // @todo Move to migration task once infrastructure is implemented
        if($this->class == 'SiteTree') {
            $conn = DB::get_schema();
            // only execute command if fields haven't been renamed to _obsolete_<fieldname> already by the task
            if($conn->hasField('SiteTree' ,'Viewers')) {
                $task = new UpgradeSiteTreePermissionSchemaTask();
                $task->run(new SS_HTTPRequest('GET','/'));
            }
        }
    }
}