<?php

namespace Tests\Browser;

use App\Lib\Message;
use App\Lib\Util;
use App\Models\Message as ModelMessage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MessageListTest extends DuskTestCase
{
    protected $test_url = "/admin/message";
    protected $test_create_url = "/admin/message/create";
    protected $test_edit_url = "/admin/message/edit/";
    protected $login_field = "#login-id";
    protected $pass_field = "#password";

    protected $search_name_field = '#search-name';
    protected $search_key_field = '#search-key';
    protected $search_value_field = '#search-value';

    protected $detail_search_submit = '#message-detailed-search-submit';
    protected $checkbox_select_all = '#message-select-all';
    protected $multiple_delete_btn = '#message-multiple-delete-button';
    protected $search_reset_btn = '#message-detailed-search-reset';
    protected $search_toggle_btn = '#search-toggle-button';

    protected $duskTable;
    protected $table_name = 'message-table';

    protected $table_length = '#message-table_length';

    const EMPTY_DATA = 1;
    const COL_CHECKBOX = 1;
    const COL_NAME = 2;
    const COL_EDIT = 3;
    const COL_KEY = 4;
    const COL_VALUE = 5;
    const COL_DELETE = 6;
    
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
                    ->assertVisible('div.app-body > div.sidebar > nav.sidebar-nav > ul > li:nth-child(4) > a')
                    ->click('div.app-body > div.sidebar > nav.sidebar-nav > ul > li:nth-child(10) > a')
                    ->assertPathIs($this->test_url); 
        });
    }

    /*
     * Test read intial loaded data from visiting page
     * check table loaded and search bar empty
     * @return void
     */
    public function testInitialPage()
    {
        $table_data = $this->getMessageModel()->where('deleted_at', null)->first();
        $this->browse(function (Browser $browser)
        {
            $browser->assertPathIs($this->test_url)
                    ->pause(1000) //give time to load elements first                    
                    // check user table visible with a loaded data row
                    ->assertSeeIn('main.main > ol > li',Util::langtext('MESSAGE_L_010'))
                    ->click($this->search_toggle_btn)
                    // check search fields as empty
                    ->value($this->search_name_field,"")
                    ->value($this->search_key_field,"")
                    ->value($this->search_value_field,"")
                    // check user table visible with a loaded data row
                    ->assertVisible($this->duskTable->table_row_data(
                        $this->table_name,1,self::COL_NAME)
                        ->getSelector())
                    ->assertVisible($this->duskTable->table_row_data(
                        $this->table_name,1,self::COL_EDIT)
                        ->href()->getSelector());
        }); 
        return $table_data;      
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
                    ->click($this->multiple_delete_btn)
                    ->assertDialogOpened(Util::langtext('NO_DATA_SELECTED'))
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
            $browser->check($this->duskTable->table_row_data(
                    $this->table_name, 1, self::COL_CHECKBOX)->getSelector())
                    ->assertVisible($this->duskTable->table_row_selected(
                    $this->table_name, 1, self::COL_CHECKBOX)->getSelector());
            
            $browser->click($this->multiple_delete_btn)
                    ->pause(1000)
                    ->assertVisible('#message_body', Message::INFO_004)
                    ->press('Close')
                    ->pause(1000);
            // uncheck
            $browser->check($this->duskTable->table_row_data(
                    $this->table_name, 1, self::COL_CHECKBOX)->getSelector())
                    ->assertMissing($this->duskTable->table_row_selected(
                    $this->table_name, 1)->getSelector());
            
        });
    }

    /**
     * Test delete row confirm pop up dialog
     */
    public function testRowDeleteConfirmDialog()
    {
        $this->browse(function (Browser $browser) {
            $table_data = $this->getMessageModel()->first();
            $browser->visit($this->test_url)
                    ->pause(1000)
                    ->assertVisible("#remove-id-$table_data->id")
                    ->click("#remove-id-$table_data->id")
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::INFO_004, [Util::langtext('SIDEBAR_LI_010')]))
                    ->press('Close')
                     //pause closing the dialog
                    ->pause(1000);
        });
    }

    /*
     * validate create redirection and go to /create url
     * @return void
     */
    public function testRedirectCreate()
    {
        $this->browse( function(Browser $browser)
        {
            $browser->visit($this->test_url)
                    ->assertPathIs($this->test_url)
                    ->pause(1000)                    
                    ->clickLink(Util::langtext('MESSAGE_B_002'))
                    ->assertPathIs($this->test_url.'/create');
        });
    }

    /**
     * Test edit redirect
     * @depends testInitialPage
     */
    public function testRedirectSelectEdit($table_data)
    {
        $this->browse( function (Browser $browser) use ($table_data)
        {
            // check edit link
            $browser->visit($this->test_url)
                    ->pause(1000)
                    ->assertVisible("#edit-id-$table_data->id")
                    ->click("#edit-id-$table_data->id")
                    ->assertPathIs($this->test_edit_url.$table_data->id);
        });
    }

    /*
     * Validate user table checkbox for select all / unselect all
     * and validate check/ uncheck box for individual item
     * @return void
     */
    public function testBatchRowsChecked()
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
                        $this->table_name, strval($i), self::COL_CHECKBOX)->getSelector())                        
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
     * @depends testInitialPage
     */
    public function testSearchValidName($table_data)
    {
        $this->browse(function (Browser $browser) use ($table_data)
        {
            $browser->visit($this->test_url);
            $browser = $this->searchAssertTable($browser,
                $this->search_name_field, $table_data->name, self::COL_NAME);
            $browser = $this->searchReset($browser);
        });
    }

    /**
     * Test search name invalid function
     */
    public function testSearchInvalidName()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->test_url);
            $browser = $this->searchAssertTable($browser, 
                    $this->search_name_field, '@a#sd#####@@@@zxcq', self::EMPTY_DATA);
            $browser = $this->searchReset($browser);
        });
    }

    /**
     * Test search name valid function
     * @depends testInitialPage
     */
    public function testSearchValidKey($table_data)
    {
        $this->browse(function (Browser $browser) use ($table_data)
        {
            $browser->visit($this->test_url);
            $browser = $this->searchAssertTable($browser,
                $this->search_key_field, $table_data->key, self::COL_KEY);
            $browser = $this->searchReset($browser);
        });
    }

    /**
     * Test search name invalid function
     */
    public function testSearchInvalidKey()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->test_url);
            $browser = $this->searchAssertTable($browser, 
                    $this->search_key_field, '@a#sd#####@@@@zxcq', self::EMPTY_DATA);
            $browser = $this->searchReset($browser);
        });
    }

    /**
     * Test search name valid function
     * @depends testInitialPage
     */
    public function testSearchValidValue($table_data)
    {
        $this->browse(function (Browser $browser) use ($table_data)
        {
            $browser->visit($this->test_url);
            $browser = $this->searchAssertTable($browser,
                $this->search_value_field, $table_data->value, self::COL_VALUE);
            $browser = $this->searchReset($browser);
        });
    }

    /**
     * Test search name invalid function
     */
    public function testSearchInvalidValue()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->test_url);
            $browser = $this->searchAssertTable($browser, 
                    $this->search_value_field, '@a#sd#####@@@@zxcq', self::EMPTY_DATA);
            $browser = $this->searchReset($browser);
        });
    }
    
    /**
     * Test changing page     * 
     */
    public function testPageChange()
    {
        $this->browse(function (Browser $browser)
        {
            $message_data = $this->getMessageModel()->skip(25)->first();
            $message_data2 = $this->getMessageModel()->skip(50)->first();
            $browser->visit($this->test_url)
                    ->pause(1000)
                    ->assertVisible('div.dataTables_paginate > ul.pagination > li:nth-child(2) > a')
                    // Click page 2
                    ->click('div.dataTables_paginate > ul.pagination > li:nth-child(3) > a')
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::COL_NAME)->getSelector(), $message_data->name)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::COL_KEY)->getSelector(), $message_data->key)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::COL_VALUE)->getSelector(), $message_data->value)
                    
                    // Click next page
                    ->clickLink(Util::langtext('DATA_TABLE_PAGINATE_NEXT'))
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::COL_NAME)->getSelector(), $message_data2->name)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::COL_KEY)->getSelector(), $message_data2->key)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::COL_VALUE)->getSelector(), $message_data2->value)
                    // Click prev page
                    ->clickLink(Util::langtext('DATA_TABLE_PAGINATE_PREVIOUS'))
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::COL_NAME)->getSelector(), $message_data->name)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::COL_KEY)->getSelector(), $message_data->key)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::COL_VALUE)->getSelector(), $message_data->value);
        });
    }

    /**
     * test checkbox row per page
     * rows by 25, 50 and 100
     * @return void
     */
    public function testSelectRowsPerPage()
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
                            $this->table_name, 1, self::COL_CHECKBOX)->getSelector())
                        ->check($this->duskTable->table_row_data(
                            $this->table_name, 1, self::COL_CHECKBOX)->getSelector())
                        ->assertNotSelected($this->duskTable->table_row_data(
                            $this->table_name, 1, self::COL_CHECKBOX)->getSelector(), NULL)
                        ->clickLink(Util::langtext('DATA_TABLE_PAGINATE_NEXT')) //page change
                        ->pause(1000)
                        ->assertVisible($this->duskTable->table_row_data(
                            $this->table_name, 1, self::COL_CHECKBOX)->getSelector())
                        ->check($this->duskTable->table_row_data(
                            $this->table_name, 1, self::COL_CHECKBOX)->getSelector())
                        ->assertNotSelected($this->duskTable->table_row_data(
                            $this->table_name, 1, self::COL_CHECKBOX)->getSelector(), NULL)
                        ->clickLink(Util::langtext('DATA_TABLE_PAGINATE_NEXT')) //page change
                        ->pause(1000)
                        ->assertVisible($this->duskTable->table_row_data(
                            $this->table_name, 1, self::COL_CHECKBOX)->getSelector())
                        ->check($this->duskTable->table_row_data(
                            $this->table_name, 1, self::COL_CHECKBOX)->getSelector())
                        ->assertNotSelected($this->duskTable->table_row_data(
                            $this->table_name, 1, self::COL_CHECKBOX)->getSelector(), NULL);
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
        return $browser->click($this->search_toggle_btn)
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
        return $browser->click($this->search_reset_btn)
                    ->click($this->search_toggle_btn)
                    ->pause(1000);
    }

    /**
     * Return message model
     */
    private function getMessageModel()
    {
        return ModelMessage::where('deleted_at', null);
    }
}
