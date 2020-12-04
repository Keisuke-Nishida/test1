<?php

namespace Tests\Browser;

use App\Lib\Message;
use App\Lib\Util;
use App\Models\Menu;
use App\Models\Role;
use App\Models\RoleMenu;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RoleListTest extends DuskTestCase
{
    protected $test_url = "/admin/role_menu";
    protected $test_edit_url = "/admin/role_menu/edit/";
    protected $login_field = "#login-id";
    protected $pass_field = "#password";

    protected $search_name_field = '#search-name';
    protected $search_type_field = '#search-type';
    protected $search_menu_field = '#search-menu';
    protected $detail_search_submit = '#role-menu-detailed-search-submit';
    protected $checkbox_select_all = '#role-menu-select-all';
    protected $multiple_delete_btn = '#role-menu-multiple-delete-button';

    protected $duskTable;
    protected $table_name = 'role-menu-table';

    protected $table_length = '#role-menu-table_length';

    const EMPTY_DATA = 1;
    const COL_CHECKBOX = 1;
    const COL_NAME = 2;
    const COL_EDIT = 3;
    const COL_AUTH_TYPE = 4;
    const COL_AVAIL_MENU = 5;
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
                    ->click('div.app-body > div.sidebar > nav.sidebar-nav > ul > li:nth-child(4) > a')
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
        $table_data = Role::where('deleted_at', null)->first();
        $this->browse(function (Browser $browser)
        {
            $browser->assertPathIs($this->test_url)
                    ->pause(1000) //give time to load elements first                    
                    // check user table visible with a loaded data row
                    ->assertSeeIn('main.main > ol > li',Util::langtext('SIDEBAR_LI_004'))
                    ->click('#search-toggle-button')
                    // check search fields as empty
                    ->value($this->search_name_field,"")
                    ->value($this->search_type_field,"")
                    ->value($this->search_menu_field,"")
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
            $table_data = RoleMenu::where('deleted_at', null)->first();
            $browser->visit($this->test_url)
                    ->pause(1000)
                    ->assertVisible("#remove-id-$table_data->id")
                    ->click("#remove-id-$table_data->id")
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::INFO_004, [Util::langtext('SIDEBAR_LI_004')]))
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
                    ->clickLink(Util::langtext('ROLE_MENU_B_004'))
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
            
            $browser->assertVisible($this->duskTable->table_row_data(
                    $this->table_name, strval(1), self::COL_CHECKBOX)->getSelector())                        
                    ->click($this->duskTable->table_row($this->table_name, strval(1))->getSelector().'> td.select-checkbox') //checked
                    ->pause(3000)
                    ->assertNotSelected( $this->duskTable->table_row($this->table_name, strval(1))->getSelector().'> td.select-checkbox', NULL) //checked
                    ->click($this->duskTable->table_row($this->table_name, strval(1))->getSelector().'> td.select-checkbox') //checked
                    ->pause(3000)
                    ->assertNotChecked($this->duskTable->table_row($this->table_name, strval(1))->getSelector().'> td.select-checkbox');
            

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
                    $this->search_name_field, '@a#sdzxcq', self::EMPTY_DATA);
            $browser = $this->searchReset($browser);
        });
    }

    /**
     * Test search menu valid function
     * @depends testInitialPage
     */
    public function testSearchValidMenu($table_data)
    {
        $this->browse(function (Browser $browser) use ($table_data)
        {
            $role_menu_data = RoleMenu::where('role_id', $table_data->id)
                            ->where('deleted_at', null)->first();
            $menu_data = Menu::where('id', $role_menu_data->id)
                        ->where('deleted_at', null)->first();
            $browser->visit($this->test_url)
                ->click('#search-toggle-button')
                ->pause(1000)
                ->select($this->search_menu_field, $menu_data->id)
                ->click($this->detail_search_submit)
                ->pause(2000)
                ->assertSeeIn($this->duskTable->table_row_data(
                    $this->table_name,1, self::COL_AVAIL_MENU)->getSelector(),
                    $menu_data->name);
            $browser = $this->searchReset($browser);
        });
    }

    /**
     * Test search menu invalid function
     * @depends testInitialPage
     */
    public function testSearchInvalidMenu($table_data)
    {
        $this->browse(function (Browser $browser) use ($table_data)
        {
            $role_menu_data = RoleMenu::where('role_id', '!=' , $table_data->id)
                                    ->where('deleted_at', null)->first();
            $menu_data = Menu::where('id', $role_menu_data->id)
                            ->where('deleted_at', null)->first();
            $browser->visit($this->test_url)
                ->click('#search-toggle-button')
                ->pause(1000)
                ->value($this->search_name_field, $table_data->name)
                ->select($this->search_menu_field, $menu_data->id)
                ->click($this->detail_search_submit)
                ->pause(2000)
                ->assertSeeIn($this->duskTable->table_row_data(
                    $this->table_name,1, self::EMPTY_DATA)->getSelector(),
                    Util::langtext('DATA_TABLE_EMPTY_TEXT'));
            $browser = $this->searchReset($browser);
        });
    }

    /**
     * Test search type valid function
     * @depends testInitialPage
     */
    public function testSearchValidType($table_data)
    {
        $this->browse(function (Browser $browser) use ($table_data)
        {
            $role_menu_data = RoleMenu::where('role_id', $table_data->id)
                                    ->where('deleted_at', null)->first();
            $browser->visit($this->test_url)
                ->click('#search-toggle-button')
                ->pause(1000)
                ->select($this->search_type_field, $role_menu_data->type)
                ->click($this->detail_search_submit)
                ->pause(2000)
                ->assertSeeIn($this->duskTable->table_row_data(
                    $this->table_name,1, self::COL_NAME)->getSelector(),
                    $table_data->name);
            $browser = $this->searchReset($browser);
        });
    }

    /**
     * Test search type invalid function
     * @depends testInitialPage
     */
    public function testSearchInvalidType($table_data)
    {
        $this->browse(function (Browser $browser) use ($table_data)
        {
            $role_menu_data = RoleMenu::where('role_id', '!=' , $table_data->id)
                                    ->where('deleted_at', null)->first();
            $menu_data = Menu::where('id', $role_menu_data->id)
                            ->where('deleted_at', null)->first();
            $browser->visit($this->test_url)
                ->click('#search-toggle-button')
                ->pause(1000)
                ->value($this->search_name_field, $table_data->name)
                ->select($this->search_type_field, $menu_data->type)
                ->click($this->detail_search_submit)
                ->pause(2000)
                ->assertSeeIn($this->duskTable->table_row_data(
                    $this->table_name,1, self::EMPTY_DATA)->getSelector(),
                    Util::langtext('DATA_TABLE_EMPTY_TEXT'));
            $browser = $this->searchReset($browser);
        });
    }
    
    /**
     * Test changing page
     */
    public function testPageChange()
    {
        $this->browse(function (Browser $browser)
        {
            $role_menu_avail = RoleMenu::select('role_id')
                            ->where('deleted_at',null)
                            ->groupBy('role_id')->get();
            $role_data = Role::where('deleted_at', null)
                        ->whereIn('id', $role_menu_avail)->skip(25)->first();
            $role_menu_data = RoleMenu::where('deleted_at',null)
                            ->where('role_id', $role_data->id)->first();
            $menu = Menu::where('deleted_at',null)->where('id', $role_menu_data->menu_id)->first();
            $role_data2 = Role::where('deleted_at', null)->whereIn('id', $role_menu_avail)->skip(50)->first();
            $role_menu_data2 = RoleMenu::where('deleted_at',null)->where('role_id', $role_data2->id)->first();
            $menu2 = Menu::where('deleted_at',null)->where('id', $role_menu_data2->menu_id)->first();
            $browser->visit($this->test_url)
                    ->pause(1000)
                    ->assertVisible('div.dataTables_paginate > ul.pagination > li:nth-child(2) > a')
                    // Click page 2
                    ->click('div.dataTables_paginate > ul.pagination > li:nth-child(3) > a')
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::COL_NAME)->getSelector(), $role_data->name)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::COL_AUTH_TYPE)->getSelector(), $role_data->type)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::COL_AVAIL_MENU)->getSelector(), $menu->name)
                    
                    // Click next page
                    ->clickLink(Util::langtext('DATA_TABLE_PAGINATE_NEXT'))
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::COL_NAME)->getSelector(), $role_data2->name)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::COL_AUTH_TYPE)->getSelector(), $role_data2->type)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::COL_AVAIL_MENU)->getSelector(), $menu2->name)
                    // Click prev page
                    ->clickLink(Util::langtext('DATA_TABLE_PAGINATE_PREVIOUS'))
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::COL_NAME)->getSelector(), $role_data->name)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::COL_AUTH_TYPE)->getSelector(), $role_data->type)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::COL_AVAIL_MENU)->getSelector(), $menu->name);
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
        return $browser->click('#role-menu-detailed-search-reset')
                    ->click('#search-toggle-button')
                    ->pause(1000);
    }
}
