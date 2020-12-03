<?php

namespace Tests\Browser;

use App\Lib\Message;
use App\Lib\Util;
use App\Models\NoticeData;
use Illuminate\Foundation\Testing\Assert as PHPUnit;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class NoticeListTest extends DuskTestCase
{
    protected $test_url = "/admin/notice_data";
    protected $login_field = "#login-id";
    protected $pass_field = "#password";

    protected $search_name_field = '#search-name';
    protected $search_title_field = '#search-title';
    protected $search_body_field = '#search-body';
    protected $detail_search_submit = '#notice_data-detailed-search-submit';
    protected $checkbox_select_all = '#notice_data-select-all';

    protected $duskTable;
    protected $table_name = 'notice_data-table';

    protected $table_length = '#notice_data-table_length';

    const EMPTY_DATA = 1;
    const NOTICE_CHECKBOX = 1;
    const NOTICE_NAME = 2;
    const NOTICE_EDIT = 3;
    const NOTICE_TITLE = 4;
    const NOTICE_BODY = 5;
    const NOTICE_DELIVERY_START = 6;
    const NOTICE_DELIVERY_END = 7;
    const NOTICE_DELETE = 8;
    
    /**
     * Create a new Notice List Test instance
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->duskTable = new Util();
    }

    /**
     * Verify start session
     */
    public function testStartUserSession()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit('/admin/login')
                    ->assertSee(Util::langtext('LOGIN_T_001'))
                    ->type($this->login_field, env('TEST_LOGIN_ID'))
                    ->type($this->pass_field, env('TEST_PASSWORD'))
                    ->click('button[type="submit"]')
                    ->assertPathIs('/admin/index') //check if logged in and see dashboard index
                    ->pause(3000)
                    ->assertVisible('div.app-body > div.sidebar > nav.sidebar-nav > ul > li:nth-child(5) > a')
                    ->click('div.app-body > div.sidebar > nav.sidebar-nav > ul > li:nth-child(5) > a')
                    ->assertPathIs($this->test_url); 
        });
    }

    /*
     * Test read intial loaded data from visiting /notice_data page
     * check table loaded and search bar empty
     * @return void
     */
    public function testNoticeDataInitialPage()
    {
        $notice_data = NoticeData::first();
        $this->browse(function (Browser $browser)
        {
            $browser->assertPathIs($this->test_url)
                    ->pause(1000) //give time to load elements first                    
                    // check user table visible with a loaded data row
                    ->assertSeeIn('main.main > ol > li',Util::langtext('NOTICE_L_011'))
                    ->click('#search-toggle-button')
                    // check search fields as empty
                    ->value($this->search_name_field,"")
                    ->value($this->search_title_field,"")
                    ->value($this->search_body_field,"")
                    // check user table visible with a loaded data row
                    ->assertVisible($this->duskTable->table_row_data(
                        $this->table_name,1,self::NOTICE_NAME)
                        ->getSelector())
                    ->assertVisible($this->duskTable->table_row_data(
                        $this->table_name,1,self::NOTICE_EDIT)
                        ->href()->getSelector());
        }); 
        return $notice_data;      
    }

    /**
     * Test no selected row bulk delete 
     * popup dialog
     */
    public function testNoSelectedBulkDeleteDialog()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->test_url)
                    ->pause(1000)
                    ->assertSee(Util::langtext('SIDEBAR_LI_005'))
                    ->click('#notice_data-multiple-delete-button')
                    ->assertDialogOpened('No data selected')
                    ->acceptDialog();
        });
    }

    /**
     * Test multiple selected row bulk delete dialog
     */
    public function testSelectedBulkDeletedDialog()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->test_url)
                    ->pause(1000);
            // Check
            for ($i = 1; $i <= 5 ; $i++)
            {
                $browser->check($this->duskTable->table_row_data($this->table_name, $i, self::NOTICE_CHECKBOX)->getSelector())
                        ->assertVisible($this->duskTable->table_row_selected($this->table_name, $i, self::NOTICE_CHECKBOX)->getSelector());
            }
            $browser->click('#notice_data-multiple-delete-button')
                    ->pause(1000)
                    ->assertVisible('#message_body', Message::INFO_004)
                    ->press('Close')
                    ->pause(1000);
            // uncheck
            for ($i = 1; $i <= 5 ; $i++)
            {
                $browser->check($this->duskTable->table_row_data($this->table_name, $i, self::NOTICE_CHECKBOX)->getSelector())
                        ->assertMissing($this->duskTable->table_row_selected($this->table_name, $i)->getSelector());
            }
        });
    }

    /**
     * Test delete row confirm pop up dialog
     */
    public function testRowDeleteConfirmDialog()
    {
        $this->browse(function (Browser $browser) {
            $notice_data = NoticeData::first();
            $browser->visit($this->test_url)
                    ->assertSee(Util::langtext('SIDEBAR_LI_003'))
                    ->pause(1000)
                    ->assertVisible("#remove-id-$notice_data->id")
                    ->click("#remove-id-$notice_data->id")
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::INFO_004, [Util::langtext('SIDEBAR_LI_005')]))
                    ->press('Close')
                     //pause closing the dialog
                    ->pause(1000);
        });
    }

    /*
     * validate create redirection and go to /create url
     * @return void
     */
    public function testRedirectSelectNoticeCreate()
    {
        $this->browse( function(Browser $browser)
        {
            $browser->visit($this->test_url)
                    ->assertPathIs($this->test_url)
                    ->pause(1000)                    
                    ->clickLink(Util::langtext('NOTICE_B_002'))
                    ->assertPathIs($this->test_url.'/create');
        });
    }

    /**
     * Test notice edit redirect
     * @depends testNoticeDataInitialPage
     */
    public function testRedirectSelectNoticeDataEdit($notice_data)
    {
        $this->browse( function (Browser $browser) use ($notice_data)
        {
            // check edit link
            $browser->visit($this->test_url)
                    ->pause(1000)
                    ->assertVisible("#edit-id-$notice_data->id")
                    ->click("#edit-id-$notice_data->id")
                    ->screenshot('notice-list');

           // verify returned id + redirection URL
            PHPUnit::assertStringContainsString($this->test_url.'/edit', $browser->driver->getCurrentURL());
        });
    }

    /*
     * Validate user table checkbox for select all / unselect all
     * and validate check/ uncheck box for individual item
     * @return void
     */
    public function testNoticeDataBatchRowsChecked()
    {
        $this->browse( function (Browser $browser)
        {
            $browser->visit($this->test_url)
                    ->pause(3000) //wait for elements to load
                    ->assertVisible("#$this->table_name > tbody");

            //check / uncheck small sample data of 5 rows loaded                    
            for($i=1; $i <= 5; $i++)
            {
                $browser->assertVisible($this->duskTable->table_row_data(
                        $this->table_name, strval($i), self::NOTICE_CHECKBOX)->getSelector())                        
                        ->click($this->duskTable->table_row($this->table_name, strval($i))->getSelector().'> td.select-checkbox') //checked
                        ->pause(3000)
                        ->assertNotSelected( $this->duskTable->table_row($this->table_name, strval($i))->getSelector().'> td.select-checkbox', NULL) //checked
                        ->click($this->duskTable->table_row($this->table_name, strval($i))->getSelector().'> td.select-checkbox') //checked
                        ->pause(3000)
                        ->assertNotChecked($this->duskTable->table_row($this->table_name, strval($i))->getSelector().'> td.select-checkbox');
            }

            // check select-all-check box checked
            $browser->check($this->checkbox_select_all)
                    ->pause(5000)
                    ->assertChecked($this->checkbox_select_all)
                    // verify all unchecked
                    ->uncheck($this->checkbox_select_all)
                    ->pause(5000)
                    ->assertNotChecked($this->checkbox_select_all);
        });
    }

    /**
     * Test search name valid function
     * @depends testNoticeDataInitialPage
     */
    public function testNoticeDataSearchValidName($notice_data)
    {
        $this->browse(function (Browser $browser) use ($notice_data)
        {
            $browser->visit($this->test_url);
            $browser = $this->searchAssertTable($browser,
                $this->search_name_field, $notice_data->name, self::NOTICE_NAME);
            $browser = $this->searchReset($browser);
        });
    }

    /**
     * Test search name invalid function
     */
    public function testNoticeDataSearchInvalidName()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->test_url);
            $browser = $this->searchAssertTable($browser, 
                    $this->search_name_field, '@a#sdzxcq', self::EMPTY_DATA);
            $browser = $this->searchReset($browser);
        });
    }

    /**
     * Test search title valid function
     * @depends testNoticeDataInitialPage
     */
    public function testNoticeDataSearchValidTitle($notice_data)
    {
        $this->browse(function (Browser $browser) use ($notice_data)
        {
            $browser->visit($this->test_url);
            $browser = $this->searchAssertTable($browser,
                $this->search_title_field, $notice_data->title, self::NOTICE_TITLE);
            $browser = $this->searchReset($browser);
        });
    }

    /**
     * Test search title invalid function
     */
    public function testNoticeDataSearchInvalidTitle()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->test_url);
            $browser = $this->searchAssertTable($browser, 
                    $this->search_title_field, '@a#sdzxcq', self::EMPTY_DATA);
            $browser = $this->searchReset($browser);
        });
    }

    /**
     * Test search body valid function
     * @depends testNoticeDataInitialPage
     */
    public function testNoticeDataSearchValidBody($notice_data)
    {
        $this->browse(function (Browser $browser) use ($notice_data)
        {
            $browser->visit($this->test_url);
            $browser = $this->searchAssertTable($browser,
                $this->search_body_field, $notice_data->body, self::NOTICE_BODY);
            $browser = $this->searchReset($browser);
        });
    }

    /**
     * Test search body invalid function
     */
    public function testNoticeDataSearchInvalidBody()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->test_url);
            $browser = $this->searchAssertTable($browser, 
                    $this->search_body_field, '@a#sdzxcq', self::EMPTY_DATA);
            $browser = $this->searchReset($browser);
        });
    }
    
    /**
     * Test changing page
     */
    public function testNoticeDataPageChange()
    {
        $this->browse(function (Browser $browser)
        {
            $notice_data = NoticeData::skip(25)->first();
            $notice_data2 = NoticeData::skip(50)->first();
            $browser->visit($this->test_url)
                    ->pause(1000)
                    ->assertVisible('div.dataTables_paginate > ul.pagination > li:nth-child(2) > a')
                    // Click page 2
                    ->click('div.dataTables_paginate > ul.pagination > li:nth-child(3) > a')
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::NOTICE_NAME)->getSelector(), $notice_data->name)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::NOTICE_TITLE)->getSelector(), $notice_data->title)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::NOTICE_BODY)->getSelector(), $notice_data->body)
                    
                    // Click next page
                    ->clickLink(Util::langtext('DATA_TABLE_PAGINATE_NEXT'))
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::NOTICE_NAME)->getSelector(), $notice_data2->name)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::NOTICE_TITLE)->getSelector(), $notice_data2->title)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::NOTICE_BODY)->getSelector(), $notice_data2->body)
                    // Click prev page
                    ->clickLink(Util::langtext('DATA_TABLE_PAGINATE_PREVIOUS'))
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::NOTICE_NAME)->getSelector(), $notice_data->name)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::NOTICE_TITLE)->getSelector(), $notice_data->title)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::NOTICE_BODY)->getSelector(), $notice_data->body);
        });
    }

    /**
     * test checkbox row per page
     * rows by 25, 50 and 100
     * @return void
     */
    public function testNoticeDataSelectRowsPerPage()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->test_url);
                    
            $row_per_page = ['25', '50', '100'];
            
            for($i=0; $i < 3; $i++)
            {                    
                // check rows per page by 25, 50 and 100
                $browser->pause(1000)
                        ->assertVisible("$this->table_length > label > select")
                        ->value("$this->table_length > label > select", $row_per_page[$i])
                        ->pause(3000)
                        ->assertSelected("$this->table_length > label > select", $row_per_page[$i])
                        //check rows after rows per page changed
                        ->assertVisible($this->duskTable->table_row_data(
                            $this->table_name, 1, self::NOTICE_CHECKBOX)->getSelector())
                        ->check($this->duskTable->table_row_data(
                            $this->table_name, 1, self::NOTICE_CHECKBOX)->getSelector())
                        ->assertNotSelected($this->duskTable->table_row_data(
                            $this->table_name, 1, self::NOTICE_CHECKBOX)->getSelector(), NULL)
                        ->clickLink(Util::langtext('DATA_TABLE_PAGINATE_NEXT')) //page change
                        ->pause(1000)
                        ->assertVisible($this->duskTable->table_row_data(
                            $this->table_name, 5, self::NOTICE_CHECKBOX)->getSelector())
                        ->check($this->duskTable->table_row_data(
                            $this->table_name, 5, self::NOTICE_CHECKBOX)->getSelector())
                        ->assertNotSelected($this->duskTable->table_row_data(
                            $this->table_name, 1, self::NOTICE_CHECKBOX)->getSelector(), NULL)
                        ->clickLink(Util::langtext('DATA_TABLE_PAGINATE_NEXT')) //page change
                        ->pause(1000)
                        ->assertVisible($this->duskTable->table_row_data(
                            $this->table_name, 3, self::NOTICE_CHECKBOX)->getSelector())
                        ->check($this->duskTable->table_row_data(
                            $this->table_name, 3, self::NOTICE_CHECKBOX)->getSelector())
                        ->assertNotSelected($this->duskTable->table_row_data(
                            $this->table_name, 3, self::NOTICE_CHECKBOX)->getSelector(), NULL);
            }
                        
            // apply 25 again, as default and check table loaded
            $browser->pause(1000)
                    ->assertVisible("$this->table_length > label > select")
                    ->value("$this->table_length > label > select", $row_per_page[0])
                    ->pause(3000)
                    ->assertSelected("$this->table_length > label > select", $row_per_page[0])
                    ->assertVisible("#$this->table_name > tbody");
        });
    }


    /**
     * Assert search table
     */
    private function searchAssertTable($browser, $field, $value, $code)
    {
        $valueAssert = ($code == self::EMPTY_DATA) ? Util::langtext('DATA_TABLE_EMPTY_TEXT') : $value;
        return $browser->click('#search-toggle-button')
                ->pause(1000)
                ->value($field, $value)
                ->click($this->detail_search_submit)
                ->pause(2000)
                ->assertSeeIn($this->duskTable->table_row_data(
                    $this->table_name,1, $code)->getSelector(), $valueAssert);
    }

    /**
     * Search Reset
     */
    private function searchReset($browser)
    {        
        return $browser->click('#notice_data-detailed-search-reset')
                    ->click('#search-toggle-button')
                    ->pause(1000);
    }
}
