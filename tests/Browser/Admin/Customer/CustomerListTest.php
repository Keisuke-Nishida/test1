<?php

namespace Tests\Browser;

use App\Lib\Message;
use App\Lib\Util;
use App\Models\Customer;
use Illuminate\Foundation\Testing\Assert as PHPUnit;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CustomerListTest extends DuskTestCase
{
    protected $test_url = "/admin/customer";
    protected $login_field = "#login-id";
    protected $pass_field = "#password";
    protected $search_customer_code_field = '#search-customer-code';
    protected $search_customer_name_field = '#search-customer-name';
    protected $search_customer_address_field = '#search-customer-address';
    protected $search_customer_tel_field = '#search-customer-tel';
    protected $search_prefecture_field = '#search-prefecture';

    protected $duskTable;
    protected $table_name = "customer-table";

    const EMPTY_DATA = 1;
    const CUST_CHECKBOX = 1;
    const CUST_CODE = 2;
    const CUST_NAME = 3;
    const CUST_EDIT = 4;
    const CUST_PREF = 5;
    const CUST_ADDR1 = 6;
    const CUST_ADDR2 = 7;
    const CUST_PHONE = 8;
    const CUST_URIAGE1 = 9;
    const CUST_URIAGE2 = 10;
    const CUST_URIAGE3 = 11;
    const CUST_DEST_EDIT = 12;
    const CUST_DELETE = 13;
    
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
    public function testCustomerInitialPage()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->assertPathIs($this->test_url)
                    ->pause(3000) //give time to load elements first
                    ->assertSeeIn('main.main > ol > li',Util::langtext('SIDEBAR_LI_003'))
                    // check search fields as empty
                    ->value($this->search_customer_code_field,"")
                    ->value($this->search_customer_name_field,"")
                    ->value($this->search_customer_address_field,"")
                    ->value($this->search_customer_tel_field, "")                    
                    ->value($this->search_prefecture_field, "")
                    // check user table visible with a loaded data row
                    ->assertVisible($this->duskTable->table_row_data(
                        $this->table_name,1,self::CUST_CODE)
                        ->getSelector())
                    ->assertVisible($this->duskTable->table_row_data(
                        $this->table_name,1,self::CUST_EDIT)
                        ->href()->getSelector());
        });       
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
                    ->assertSee(Util::langtext('SIDEBAR_LI_003'))
                    ->click('#customer-multiple-delete-button')
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
                $browser->check($this->duskTable->table_row_data($this->table_name, $i, self::CUST_CHECKBOX)->getSelector())
                        ->assertVisible($this->duskTable->table_row_selected($this->table_name, $i, self::CUST_CHECKBOX)->getSelector());
            }
            $browser->click('#customer-multiple-delete-button')
                    ->pause(1000)
                    ->assertVisible('#message_body', Message::INFO_004)
                    ->press('Close')
                    ->pause(1000);
            // uncheck
            for ($i = 1; $i <= 5 ; $i++)
            {
                $browser->check($this->duskTable->table_row_data($this->table_name, $i, self::CUST_CHECKBOX)->getSelector())
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
            $customer = Customer::first();
            $browser->visit($this->test_url)
                    ->assertSee(Util::langtext('SIDEBAR_LI_003'))
                    ->pause(1000)
                    ->assertVisible("#remove-id-$customer->id")
                    ->click("#remove-id-$customer->id")
                    ->pause(1000)
                    ->assertSee(str_replace('%s', Util::langtext('SIDEBAR_LI_003'), Message::INFO_004))
                    ->press('Close')
                     //pause closing the dialog
                    ->pause(1000);
        });
    }

    /*
     * validate create redirection and go to /create url
     * @return void
     */
    public function testRedirectSelectCustomerCreate()
    {
        $this->browse( function(Browser $browser)
        {
            $browser->visit($this->test_url)
                    ->assertPathIs($this->test_url)
                    ->pause(1000)                    
                    ->clickLink(Util::langtext('CUSTOMER_B_004'))
                    ->assertPathIs($this->test_url.'/create');
        });
    }
    
    /*
     * validate edit redirection for selected row and proceed to edit url
     * @return void
     */
    public function testRedirectSelectCustomerEdit()
    {
        $this->browse( function (Browser $browser)
        {
            // check edit link
            $browser->visit($this->test_url)
                    ->pause(1000)
                    ->assertVisible($this->duskTable->table_row_data(
                        $this->table_name,1,self::CUST_EDIT)->href()->getSelector())
                    ->click($this->duskTable->table_row_data(
                        $this->table_name,1,self::CUST_EDIT)->href()->getSelector());

           // verify returned id + redirection URL
            PHPUnit::assertStringContainsString($this->test_url.'/edit', $browser->driver->getCurrentURL());
        });
    }

    /**
     * validate edit customer destination redirection 
     * for selected row and proceed to destination url
     * note: Not Implemented
     */
    public function testRedirectSelectCustomerDestination()
    {
        $this->browse( function (Browser $browser){
            $customer = Customer::first();
            $browser->visit($this->test_url)
                    ->pause(1000)
                    ->click($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_DEST_EDIT)->href()->getSelector());
            // verify returned id + redirection URL
            PHPUnit::assertStringContainsString($this->test_url.'/'.$customer->id.'/destination', $browser->driver->getCurrentURL());
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
                    ->assertVisible('#customer-table > tbody');

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
            $browser->check('#customer-select-all')
                    ->pause(5000)
                    ->assertChecked('#customer-select-all')
                    // verify all unchecked
                    ->uncheck('#customer-select-all')
                    ->pause(5000)
                    ->assertNotChecked('#customer-select-all');
        });
    }

    /**
     * Test customer search code valid function
     */
    public function testCustomerSearchValidCode()
    {
        $customers = Customer::take(4)->get();
        $customer = $customers[0];

        $this->browse(function (Browser $browser) use ($customer)
        {
            $browser->visit($this->test_url)
                    ->click('#search-toggle-button')
                    ->pause(2000)
                    ->value($this->search_customer_code_field, $customer->code)
                    ->press(Util::langtext('CUSTOMER_B_002'))
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::CUST_CODE)
                        ->getSelector(), $customer->code);
            $browser = $this->customerSearchReset($browser);
        });
        return $customers;
    }

    /**
     * Test customer search code invalid function
     */
    public function testCustomerSearchInvalidCode()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->test_url)
                    ->click('#search-toggle-button')
                    ->pause(1000)
                    ->value($this->search_customer_code_field, '!!#wer@axzc@zxc')
                    ->press(Util::langtext('CUSTOMER_B_002'))
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::EMPTY_DATA)
                        ->getSelector(), Util::langtext('DATA_TABLE_EMPTY_TEXT'));
            $browser = $this->customerSearchReset($browser);
        });
    }

    /**
     * Test customer search name valid function
     * @depends testCustomerSearchValidCode
     */
    public function testCustomerSearchValidName($customers)
    {
        $customer = $customers[1];
        $this->browse(function (Browser $browser) use ($customer)
        {
            $browser->visit($this->test_url)
                    ->assertSee(Util::langtext('SIDEBAR_LI_002'))
                    ->click('#search-toggle-button')
                    ->pause(2000)
                    ->value($this->search_customer_name_field, $customer->name)
                    ->press(Util::langtext('CUSTOMER_B_002'))
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::CUST_NAME)
                        ->getSelector(), $customer->name);
            $browser = $this->customerSearchReset($browser);
        });
    }
    /**
     * Test customer search name invalid function
     */
    public function testCustomerSearchNameInvalid()
    {
        $this->browse(function (Browser $browser)
        {            
            $browser->visit($this->test_url)
                    ->assertSee(Util::langtext('SIDEBAR_LI_002'))
                    ->click('#search-toggle-button')
                    ->pause(1000)
                    ->value($this->search_customer_name_field, '!!qwzxc@zxc')
                    ->press(Util::langtext('CUSTOMER_B_002'))
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::EMPTY_DATA)
                        ->getSelector(), Util::langtext('DATA_TABLE_EMPTY_TEXT'));
            $browser = $this->customerSearchReset($browser);
        });

    }

    /**
     * Test customer search prefecture valid function
     */
    public function testCustomerSearchValidPrefecture()
    {
        $customer = Customer::where('prefecture_id', 1)->first();
        $this->browse(function (Browser $browser) use ($customer)
        {
            $browser->visit($this->test_url)
                    ->assertSee(Util::langtext('SIDEBAR_LI_002'))
                    ->click('#search-toggle-button')
                    ->pause(1000)
                    ->select($this->search_prefecture_field, 1)
                    ->press(Util::langtext('CUSTOMER_B_002'))
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_CODE)
                        ->getSelector(), $customer->code)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_NAME)
                        ->getSelector(), $customer->name);
            $browser = $this->customerSearchReset($browser);
        });
    }

    /**
     * Test customer search prefacture invalid function
     */
    public function testCustomerSearchInvalidPrefacture()
    {
        $this->browse(function (Browser $browser) 
        {
            $browser->visit($this->test_url)
                    ->assertSee(Util::langtext('SIDEBAR_LI_002'))
                    ->click('#search-toggle-button')
                    ->pause(1000)
                    ->value($this->search_customer_address_field, '123122@@zasd')
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
     * Test customer search address function
     * @depends testCustomerSearchValidCode
     */
    public function testCustomerSearchValidAddress($customers)
    {
        $customer = $customers[2];
        $this->browse(function (Browser $browser) use ($customer)
        {
            $browser->visit($this->test_url)
                    ->assertSee(Util::langtext('SIDEBAR_LI_002'))
                    ->click('#search-toggle-button')
                    ->pause(2000)
                    ->value($this->search_customer_address_field, $customer->address_1)
                    ->press(Util::langtext('CUSTOMER_B_002'))
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_ADDR1)
                        ->getSelector(), $customer->address_1)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_ADDR2)
                        ->getSelector(), $customer->address_2);
            $browser = $this->customerSearchReset($browser);   
        });
    }
    
    /**
     * Test customer search address invalid function
     */
    public function testCustomerSearchInvalidAddress()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->test_url)
                    ->assertSee(Util::langtext('SIDEBAR_LI_002'))
                    ->click('#search-toggle-button')
                    ->pause(1000)
                    ->value($this->search_customer_address_field, '123122@@zasd')
                    ->press(Util::langtext('CUSTOMER_B_002'))
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::EMPTY_DATA)
                        ->getSelector(), Util::langtext('DATA_TABLE_EMPTY_TEXT'));
            $browser = $this->customerSearchReset($browser);
        });
    }

    /**
     * Test customer search phone function
     * @depends testCustomerSearchValidCode
     */
    public function testCustomerSearchValidPhone($customers)
    {
        $customer = $customers[3];
        $this->browse(function (Browser $browser) use ($customer)
        {
            // Valid
            $browser->visit($this->test_url)
                    ->assertSee(Util::langtext('SIDEBAR_LI_002'))
                    ->click('#search-toggle-button')
                    ->pause(2000)
                    ->value($this->search_customer_tel_field, $customer->tel)
                    ->press(Util::langtext('CUSTOMER_B_002'))
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_PHONE)
                        ->getSelector(), $customer->tel);
            $browser = $this->customerSearchReset($browser);        
        });
    }

    /**
     * Test customer search phone invalid function
     */
    public function testCustomerSearchInvalidPhone()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->test_url)
                    ->assertSee(Util::langtext('SIDEBAR_LI_002'))
                    ->click('#search-toggle-button')
                    ->pause(1000)
                    ->value($this->search_customer_tel_field, '@!qwerty:{">')
                    ->press(Util::langtext('CUSTOMER_B_002'))
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::EMPTY_DATA)
                        ->getSelector(), Util::langtext('DATA_TABLE_EMPTY_TEXT'));
            $browser = $this->customerSearchReset($browser);
        });
    }
    
    /**
     * Test changing page
     */
    public function testPageChange()
    {
        $this->browse(function (Browser $browser)
        {
            $customer = Customer::skip(25)->first();
            $customer2 = Customer::skip(50)->first();
            $browser->visit($this->test_url)
                    ->pause(1000)
                    ->assertVisible('div.dataTables_paginate > ul.pagination > li:nth-child(2) > a')
                    // Click page 2
                    ->click('div.dataTables_paginate > ul.pagination > li:nth-child(3) > a')
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_CODE)->getSelector(), $customer->code)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_NAME)->getSelector(), $customer->name)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_ADDR1)->getSelector(), $customer->address_1)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_ADDR2)->getSelector(), $customer->address_2)
                    
                    // Click next page
                    ->clickLink(Util::langtext('DATA_TABLE_PAGINATE_NEXT'))
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_CODE)->getSelector(), $customer2->code)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_NAME)->getSelector(), $customer2->name)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_ADDR1)->getSelector(), $customer2->address_1)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_ADDR2)->getSelector(), $customer2->address_2)
                    // Click prev page
                    ->clickLink(Util::langtext('DATA_TABLE_PAGINATE_PREVIOUS'))
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_CODE)->getSelector(), $customer->code)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_NAME)->getSelector(), $customer->name)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_ADDR1)->getSelector(), $customer->address_1)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_ADDR2)->getSelector(), $customer->address_2);
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
            $browser->visit($this->test_url)
                    ->assertPathIs($this->test_url);
                    
            $row_per_page = ['25', '50', '100'];
            
            for($i=0; $i < 3; $i++)
            {                    
                // check rows per page by 25, 50 and 100
                $browser->pause(1000)
                        ->assertVisible('#customer-table_length > label > select')
                        ->value('#customer-table_length > label > select', $row_per_page[$i])
                        ->pause(3000)
                        ->assertSelected('#customer-table_length > label > select', $row_per_page[$i])
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
                    ->assertVisible('#customer-table_length > label > select')
                    ->value('#customer-table_length > label > select', $row_per_page[0])
                    ->pause(3000)
                    ->assertSelected('#customer-table_length > label > select', $row_per_page[0])
                    ->assertVisible('#customer-table > tbody');                       
        });
    }
    
    /*
     * End session for /customer and logout
     * @return void
     */
    public function testEndUserSession()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->assertPathIs($this->test_url) //verify logging out
                    ->assertTitle(Util::langtext('SIDEBAR_LI_003').' | '.env('APP_NAME'))
                    ->visit('/admin/logout')
                    ->assertPathIs('/admin/login') //verify logged out
                    ->assertTitle('ログイン | '.env('APP_NAME'))
                    ->assertSee(Util::langtext('LOGIN_T_001'));
        });
    }

    /**
     * Search Reset
     */
    private function customerSearchReset($browser)
    {        
        return $browser->click('#customer-detailed-search-reset')
        ->click('#search-toggle-button');
    }
}
