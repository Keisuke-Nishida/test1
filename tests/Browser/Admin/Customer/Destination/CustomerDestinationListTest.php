<?php

namespace Tests\Browser;

use App\Lib\Message;
use App\Lib\Util;
use App\Models\Customer;
use App\Models\CustomerDestination;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CustomerDestinationListTest extends DuskTestCase
{
    protected $test_url = "/admin/customer";
    protected $login_field = "#login-id";
    protected $pass_field = "#password";
    protected $search_code_field = '#search-code';
    protected $search_name_field = '#search-name';
    protected $search_prefecture_field = '#search-prefecture';
    protected $search_addr_field = '#search-address';
    protected $search_tel_field = '#search-tel';
    protected $check_select_all = '#customer-destination-select-all';
    protected $detail_search_submit = '#customer-destination-detailed-search-submit';

    protected $duskTable;
    protected $table_name = "customer-destination-table";

    const EMPTY_DATA = 1;
    const CUST_CHECKBOX = 1;
    const CUST_CODE = 2;
    const CUST_NAME = 3;
    const CUST_EDIT = 4;
    const CUST_PREF = 5;
    const CUST_ADDR1 = 6;
    const CUST_ADDR2 = 7;
    const CUST_PHONE = 8;
    const CUST_NOTICE_KANJI = 9;
    const CUST_DEST_EDIT = 12;
    
    /**
     * Create a new Customer List Test instance
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
                    ->assertVisible('div.app-body > div.sidebar > nav.sidebar-nav > ul > li:nth-child(2) > a')
                    ->click('div.app-body > div.sidebar > nav.sidebar-nav > ul > li:nth-child(3) > a')
                    ->assertPathIs($this->test_url); 
        });
    }

    /*
     * Test read intial loaded data from visiting /customer page
     * check table loaded and search bar empty
     * @return void
     */
    public function testCustomerDestinationInitialPage()
    {
        $customer = Customer::first();
        $customer["destination"] = CustomerDestination::where('customer_id', $customer->id)->first();
        $this->browse(function (Browser $browser) use ($customer)
        {
            $browser->assertPathIs($this->test_url)
                    ->pause(1000) //give time to load elements first                    
                    // check user table visible with a loaded data row
                    ->assertSeeIn('main.main > ol > li',Util::langtext('SIDEBAR_LI_003'))
                    ->assertVisible("#destination-id-$customer->id")
                    ->click("#destination-id-$customer->id")
                    ->click('#search-toggle-button')
                    // check search fields as empty
                    ->value($this->search_code_field,"")
                    ->value($this->search_name_field,"")
                    ->value($this->search_prefecture_field,"")
                    ->value($this->search_addr_field, "")
                    ->value($this->search_tel_field, "")
                    ->assertPathIs("$this->test_url/$customer->id/destination");
        }); 
        return $customer;      
    }

    /**
     * Test no selected row bulk delete popup dialog
     * @depends testCustomerDestinationInitialPage
     */
    public function testCustomerDestinationNoSelectedBulkDeleteDialog($customer)
    {
        $this->browse(function (Browser $browser) use ($customer) {
            $browser->visit("$this->test_url/$customer->id/destination")
                    ->pause(1000)
                    ->assertSee(Util::langtext('SIDEBAR_LI_003'))
                    ->click('#customer-destination-multiple-delete-button')
                    ->assertDialogOpened('No data selected')
                    ->acceptDialog();
        });
    }

    /**
     * Test multiple selected row bulk delete dialog
     * @depends testCustomerDestinationInitialPage
     */
    public function testCustomerDestinationSelectedBulkDeletedDialog($customer)
    {
        $this->browse(function (Browser $browser) use ($customer) {
            $browser->visit("$this->test_url/$customer->id/destination")
                    ->pause(1000);
            
            $browser->check($this->duskTable->table_row_data($this->table_name, 1, self::CUST_CHECKBOX)->getSelector())
                    ->assertVisible($this->duskTable->table_row_selected($this->table_name, 1, self::CUST_CHECKBOX)->getSelector())
                    ->click('#customer-destination-multiple-delete-button')
                    ->pause(1000)
                    ->assertVisible('#message_body', Message::INFO_004)
                    ->press('Close')
                    ->pause(1000);

            // uncheck
            $browser->check($this->duskTable->table_row_data($this->table_name, 1, self::CUST_CHECKBOX)->getSelector())
                    ->assertMissing($this->duskTable->table_row_selected($this->table_name, 1)->getSelector());
            
        });
    }

    /**
     * Test delete row confirm pop up dialog
     * @depends testCustomerDestinationInitialPage
     */
    public function testCustomerDestinationRowDeleteConfirmDialog($customer)
    {
        $this->browse(function (Browser $browser) use ($customer) {
            $cust_dest = $customer["destination"];
            $browser->visit("$this->test_url/$customer->id/destination")
                    ->assertSee(Util::langtext('SIDEBAR_LI_003'))
                    ->pause(1000)
                    ->assertVisible("#remove-id-$cust_dest->id")
                    ->click("#remove-id-$cust_dest->id")
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::INFO_004, [Util::langtext('SIDEBAR_SUB_LI_001')]))
                    ->press('Close')
                     //pause closing the dialog
                    ->pause(1000);
        });
    }

    /**
     * validate create redirection and go to /create url
     * @depends testCustomerDestinationInitialPage
     */
    public function testRedirectSelectCustomerDestinationCreate($customer)
    {
        $this->browse(function (Browser $browser) use ($customer) {
            $browser->visit("$this->test_url/$customer->id/destination")
                    ->pause(1000)                    
                    ->click('#customer-destination-create')
                    ->assertPathIs("$this->test_url/$customer->id/destination/create");
        });
    }

    /**
     * validate edit redirection for selected row and proceed to edit url
     * @depends testCustomerDestinationInitialPage
     */
    public function testRedirectSelectCustomerDestinationEdit($customer)
    {
        $this->browse(function (Browser $browser) use ($customer) {
            $cust_dest = $customer["destination"];
            $browser->visit("$this->test_url/$customer->id/destination")
                    ->pause(1000)                    
                    ->click("#edit-id-$cust_dest->id")
                    ->assertPathIs("$this->test_url/$customer->id/destination/$cust_dest->id/edit/");
        });
    }

    /**
     * validate edit redirection for selected row and proceed to edit url
     * @depends testCustomerDestinationInitialPage
     */
    public function testCustomerDestinationBatchRowsChecked($customer)
    {
        $this->browse(function (Browser $browser) use ($customer) {
            $browser->visit("$this->test_url/$customer->id/destination")
                    ->pause(1000)
                    ->assertVisible('#'.$this->table_name.'> tbody');

            //check / uncheck small sample data of 5 rows loaded                    
            for($i=1; $i <= 5; $i++)
            {
                $browser->assertVisible($this->duskTable->table_row_data(
                        $this->table_name, strval($i), self::CUST_CHECKBOX)->getSelector())                        
                        ->click($this->duskTable->table_row($this->table_name, strval($i))->getSelector().'> td.select-checkbox') //checked
                        ->pause(3000)
                        ->assertNotSelected( $this->duskTable->table_row($this->table_name, strval($i))->getSelector().'> td.select-checkbox', NULL) //checked
                        ->click($this->duskTable->table_row($this->table_name, strval($i))->getSelector().'> td.select-checkbox') //checked
                        ->pause(3000)
                        ->assertNotChecked($this->duskTable->table_row($this->table_name, strval($i))->getSelector().'> td.select-checkbox');
            }

            // check select-all-check box checked
            $browser->check($this->check_select_all)
                    ->pause(1000)
                    ->assertChecked($this->check_select_all)
                    // verify all unchecked
                    ->uncheck($this->check_select_all)
                    ->pause(1000)
                    ->assertNotChecked($this->check_select_all);
        });
    }

    /**
     * validate edit redirection for selected row and proceed to edit url
     * @depends testCustomerDestinationInitialPage
     */
    public function testCustomerDestinationSearchValidCode($customer)
    {
        $this->browse(function (Browser $browser) use ($customer) {
            $cust_dest = $customer["destination"];
            $browser->visit("$this->test_url/$customer->id/destination");
            $browser = $this->searchAssertTable($browser, 
                $this->search_code_field, $cust_dest->code, self::CUST_CODE);
            $browser = $this->customerSearchReset($browser);
        });
    }

    /**
     * Test customer search code invalid function
     * @depends testCustomerDestinationInitialPage
     */
    public function testCustomerDestinationSearchInvalidCode($customer)
    {
        $this->browse(function (Browser $browser) use ($customer)
        {
            $browser->visit("$this->test_url/$customer->id/destination");
            $browser = $this->searchAssertTable($browser, 
                    $this->search_code_field, '@asdzxcq', self::EMPTY_DATA);
            $browser = $this->customerSearchReset($browser);
        });
    }

    /**
     * Test search name valid function
     * @depends testCustomerDestinationInitialPage
     */
    public function testCustomerDestinationSearchValidName($customer)
    {
        $this->browse(function (Browser $browser) use ($customer)
        {
            $cust_dest = $customer["destination"];
            $browser->visit("$this->test_url/$customer->id/destination");
            $browser = $this->searchAssertTable($browser,
                $this->search_name_field, $cust_dest->name, self::CUST_NAME);
            $browser = $this->customerSearchReset($browser);
        });
    }

    /**
     * Test search name invalid function
     * @depends testCustomerDestinationInitialPage
     */
    public function testCustomerDestinationSearchInvalidName($customer)
    {
        $this->browse(function (Browser $browser) use ($customer)
        {
            $browser->visit("$this->test_url/$customer->id/destination");
            $browser = $this->searchAssertTable($browser, 
                    $this->search_code_field, '@a#sdzxcq', self::EMPTY_DATA);
            $browser = $this->customerSearchReset($browser);
        });
    }

    /**
     * Test search prefecture valid function
     * @depends testCustomerDestinationInitialPage
     */
    public function testCustomerDestinationSearchValidPrefecture($customer)
    {
        $this->browse(function (Browser $browser) use ($customer)
        {
            $cust_dest = $customer["destination"];
            $browser->visit("$this->test_url/$customer->id/destination")
                    ->click('#search-toggle-button')
                    ->pause(1000)
                    ->select($this->search_prefecture_field, $cust_dest->prefecture_id)
                    ->press(Util::langtext('CUSTOMER_B_002'))
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_CODE)
                        ->getSelector(), $cust_dest->code)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_NAME)
                        ->getSelector(), $cust_dest->name);
            $browser = $this->customerSearchReset($browser);
        });
    }

    /**
     * Test search prefecture invalid function
     * @depends testCustomerDestinationInitialPage
     */
    public function testCustomerDestinationSearchInvalidPrefecture($customer)
    {
        $this->browse(function (Browser $browser) use ($customer)
        {
            $browser->visit("$this->test_url/$customer->id/destination")
                    ->click('#search-toggle-button')
                    ->pause(1000)
                    ->value($this->search_addr_field, '123122@@zasd')
                    ->select($this->search_prefecture_field, 1)
                    ->press(Util::langtext('CUSTOMER_B_002'))
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::EMPTY_DATA)
                        ->getSelector(), Util::langtext('DATA_TABLE_EMPTY_TEXT'));
            $browser = $this->customerSearchReset($browser);
        });
    }

    /**
     * Test search address valid function
     * @depends testCustomerDestinationInitialPage
     */
    public function testCustomerDestinationSearchValidAddress($customer)
    {
        $this->browse(function (Browser $browser) use ($customer)
        {
            $cust_dest = $customer["destination"];
            $browser->visit("$this->test_url/$customer->id/destination");
            $browser = $this->searchAssertTable($browser,
                $this->search_addr_field, $cust_dest->address_1, self::CUST_ADDR1);
            $browser = $this->customerSearchReset($browser);
        });
    }

    /**
     * Test search address invalid function
     * @depends testCustomerDestinationInitialPage
     */
    public function testCustomerDestinationSearchInvalidAddress($customer)
    {
        $this->browse(function (Browser $browser) use ($customer)
        {
            $browser->visit("$this->test_url/$customer->id/destination");
            $browser = $this->searchAssertTable($browser, 
                    $this->search_addr_field, '@a#sdzxcq', self::EMPTY_DATA);
            $browser = $this->customerSearchReset($browser);
        });
    }

    /**
     * Test search tel valid function
     * @depends testCustomerDestinationInitialPage
     */
    public function testCustomerDestinationSearchValidTel($customer)
    {
        $this->browse(function (Browser $browser) use ($customer)
        {
            $cust_dest = $customer["destination"];
            $browser->visit("$this->test_url/$customer->id/destination");
            $browser = $this->searchAssertTable($browser,
                $this->search_tel_field, $cust_dest->tel, self::CUST_PHONE);
            $browser = $this->customerSearchReset($browser);
        });
    }

    /**
     * Test search tel invalid function
     * @depends testCustomerDestinationInitialPage
     */
    public function testCustomerDestinationSearchInvalidTel($customer)
    {
        $this->browse(function (Browser $browser) use ($customer)
        {
            $browser->visit("$this->test_url/$customer->id/destination");
            $browser = $this->searchAssertTable($browser, 
                    $this->search_tel_field, '@a#sdzxcq', self::EMPTY_DATA);
            $browser = $this->customerSearchReset($browser);
        });
    }
    
    /**
     * Test changing page
     * @depends testCustomerDestinationInitialPage
     */
    public function testCustomerDestinationPageChange($customer)
    {
        $this->browse(function (Browser $browser) use ($customer)
        {
            $cust_dest = CustomerDestination::where('customer_id', $customer->id)->skip(25)->first();
            $cust_dest2 = CustomerDestination::where('customer_id', $customer->id)->skip(50)->first();
            $browser->visit("$this->test_url/$customer->id/destination")
                    ->pause(1000)
                    ->assertVisible('div.dataTables_paginate > ul.pagination > li:nth-child(2) > a')
                    // Click page 2
                    ->click('div.dataTables_paginate > ul.pagination > li:nth-child(3) > a')
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_CODE)->getSelector(), $cust_dest->code)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_NAME)->getSelector(), $cust_dest->name)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_ADDR1)->getSelector(), $cust_dest->address_1)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_ADDR2)->getSelector(), $cust_dest->address_2)
                    
                    // Click next page
                    ->clickLink(Util::langtext('DATA_TABLE_PAGINATE_NEXT'))
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_CODE)->getSelector(), $cust_dest2->code)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_NAME)->getSelector(), $cust_dest2->name)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_ADDR1)->getSelector(), $cust_dest2->address_1)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_ADDR2)->getSelector(), $cust_dest2->address_2)
                    // Click prev page
                    ->clickLink(Util::langtext('DATA_TABLE_PAGINATE_PREVIOUS'))
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_CODE)->getSelector(), $cust_dest->code)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_NAME)->getSelector(), $cust_dest->name)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_ADDR1)->getSelector(), $cust_dest->address_1)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_ADDR2)->getSelector(), $cust_dest->address_2);
        });
    }

    /**
     * test checkbox row per page
     * rows by 25, 50 and 100
     * @depends testCustomerDestinationInitialPage
     */
    public function testCustomerDestinationSelectRowsPerPage($customer)
    {
        $this->browse(function (Browser $browser) use ($customer)
        {
            $browser->visit("$this->test_url/$customer->id/destination");
                    
            $row_per_page = ['25', '50', '100'];
            
            for($i=0; $i < 3; $i++)
            {                    
                // check rows per page by 25, 50 and 100
                $browser->pause(1000)
                        ->assertVisible('#customer-destination-table_length > label > select')
                        ->value('#customer-destination-table_length > label > select', $row_per_page[$i])
                        ->pause(3000)
                        ->assertSelected('#customer-destination-table_length > label > select', $row_per_page[$i])
                        //check rows after rows per page changed
                        ->assertVisible($this->duskTable->table_row_data(
                            $this->table_name, 1, self::CUST_CHECKBOX)->getSelector())
                        ->check($this->duskTable->table_row_data(
                            $this->table_name, 1, self::CUST_CHECKBOX)->getSelector())
                        ->assertNotSelected($this->duskTable->table_row_data(
                            $this->table_name, 1, self::CUST_CHECKBOX)->getSelector(), NULL)
                        ->clickLink(Util::langtext('DATA_TABLE_PAGINATE_NEXT')) //page change
                        ->pause(1000)
                        ->assertVisible($this->duskTable->table_row_data(
                            $this->table_name, 5, self::CUST_CHECKBOX)->getSelector())
                        ->check($this->duskTable->table_row_data(
                            $this->table_name, 5, self::CUST_CHECKBOX)->getSelector())
                        ->assertNotSelected($this->duskTable->table_row_data(
                            $this->table_name, 1, self::CUST_CHECKBOX)->getSelector(), NULL)
                        ->clickLink(Util::langtext('DATA_TABLE_PAGINATE_NEXT')) //page change
                        ->pause(1000)
                        ->assertVisible($this->duskTable->table_row_data(
                            $this->table_name, 3, self::CUST_CHECKBOX)->getSelector())
                        ->check($this->duskTable->table_row_data(
                            $this->table_name, 3, self::CUST_CHECKBOX)->getSelector())
                        ->assertNotSelected($this->duskTable->table_row_data(
                            $this->table_name, 3, self::CUST_CHECKBOX)->getSelector(), NULL);
            }
                        
            // apply 25 again, as default and check table loaded
            $browser->pause(1000)
                    ->assertVisible('#customer-destination-table_length > label > select')
                    ->value('#customer-destination-table_length > label > select', $row_per_page[0])
                    ->pause(3000)
                    ->assertSelected('#customer-destination-table_length > label > select', $row_per_page[0])
                    ->assertVisible('#customer-destination-table > tbody');                       
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
    private function customerSearchReset($browser)
    {        
        return $browser->click('#customer-destination-detailed-search-reset')
                    ->click('#search-toggle-button')
                    ->pause(1000);
    }
    
}
