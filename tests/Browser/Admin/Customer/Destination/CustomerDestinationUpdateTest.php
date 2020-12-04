<?php

namespace Tests\Browser;

use App\Lib\Message;
use App\Lib\Util;
use App\Models\Customer;
use App\Models\CustomerDestination;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\Assert as PHPUnit;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;


class CustomerDestinationUpdateTest extends DuskTestCase
{
    use WithFaker;    

    protected $sessionLogIn, $dashboardIndex, $customerPath, $customerDestEditRes;
    protected $customerDestinationEditPath, $entryList, $customerDestinationCode;
    
    // constructor and initialize data
    public function setUp(): void
    {
        parent::setUp();

        $this->sessionLogIn = '/admin/login';
        $this->dashboardIndex = '/admin/index';
        $this->customerPath = '/admin/customer';
        
        $this->customerDestinationEditPath = ['/destination/','/edit'];
        $this->customerDestEditRes = '/destination?customer_destination_id=';

        $randName = $this->faker->name;
        $fax = '+1 '.strval(rand(100,999)).' '.strval(rand(100,999)).' '.strval(rand(1000,9999)); //jp fax num format
        $landline = '03-'.strval(rand(1000,9999)).'-'.strval(rand(1000,9999)); //jp landline num format
        $postalCode = strval(rand(10,99)).strval(rand(10,99)).strval(rand(10,99)).'1';//jp postal code

        // entry by: name-kana, post-code, address-2, fax, name, prefecture-id, address-1, tel, kiduke-kanji
        $this->entryList = [strtoupper(trim($randName)), $postalCode, $this->faker->address,
                            $fax, $randName, strval(rand(1,5)), $this->faker->address, $landline, 'Kiduke Notice Mark'];
    }    
    
    // set code from Customer Destination
    private function setCustDestCode()
    {
        $count = 1;
        while($count == 1)
        {
            $this->customerDestinationCode = strval(rand(1,9999999));                        
            $res = CustomerDestination::where('code','=',$this->customerDestinationCode)->count();

            if($res == 0){ $count = 0;}
        }
    }

    // get code
    private function getCustDestCode()
    {
        return $this->customerDestinationCode;
    }
    
    
    /*
     * start session - go to customer destination
     * @return void
     */
    public function testStartCustomerDestEditSession()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->sessionLogIn)
                    ->assertSee(Util::langtext('LOGIN_T_001'))
                    ->type('login_id', env('TEST_LOGIN_ID'))
                    ->type('password', env('TEST_PASSWORD'))
                    ->click('button[type="submit"]')
                    ->assertPathIs($this->dashboardIndex)
                    ->pause(1000) //give time to load elements
                    ->assertVisible('div.app-body > div.sidebar > nav.sidebar-nav > ul > li:nth-child(3) > a')
                    ->click('div.app-body > div.sidebar > nav.sidebar-nav > ul > li:nth-child(3) > a')
                    ->pause(1000)
                    ->assertSee(Util::langtext('SIDEBAR_LI_003'))
                    ->assertPathIs($this->customerPath);
        });
    }
    
    /*
     * Read customer destination page initial load and read table
     * @return void
     */
    public function testCustomerDestLink()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->customerPath)
                    ->pause(2000)
                    ->assertSee(Util::langtext('SIDEBAR_LI_003'))
                    ->assertVisible('#customer-table > tbody')
                    ->assertVisible('#customer-table > tbody > tr:nth-child(1) > td:nth-child(12) > a')
                    ->click('#customer-table > tbody > tr:nth-child(1) > td:nth-child(12) > a')
                    ->pause(2000)
                    ->assertPathIsNot($this->customerPath)
                    ->assertSee(Util::langtext('SIDEBAR_SUB_LI_001'))
                    ->assertVisible('#customer-destination-table > tbody');
        });
    }
    
    /**
     * Read Customer Destination - from 1 row - read edit button
     * @depends testCustomerDestLink
     */
    public function testCustomerDestEditButton()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->assertPathIsNot($this->customerPath)
                    ->assertSee(Util::langtext('SIDEBAR_SUB_LI_001'))
                    ->assertVisible('#customer-destination-table > tbody')
                    ->assertVisible('#customer-destination-table > tbody > tr:nth-child(1) > td:nth-child(4) > a')
                    ->click('#customer-destination-table > tbody > tr:nth-child(1) > td:nth-child(4) > a')
                    ->pause(2000)
                    ->assertSee(Util::langtext('SIDEBAR_SUB_LI_001'))
                    ->assertSee(Util::langtext('CUSTOMER_T_003'));

            PHPUnit::assertStringContainsString($this->customerDestinationEditPath[0], $browser->driver->getCurrentURL());
            PHPUnit::assertStringContainsString($this->customerDestinationEditPath[1], $browser->driver->getCurrentURL());            
        });
    }
    
    /*
     * Read Customer Destination Edit fields initial load state
     * @return void
     */
    public function testCustomerDestEditFields()
    {
        $this->browse(function (Browser $browser)
        {
            PHPUnit::assertStringContainsString($this->customerDestinationEditPath[0], $browser->driver->getCurrentURL());
            PHPUnit::assertStringContainsString($this->customerDestinationEditPath[1], $browser->driver->getCurrentURL());
            
            // dummy value length check for postal code, fax and tel that in invalid length test
            $editInitLoad = [strval(rand(11110,99999)),
                                '+'.strval(rand(1110,9999)),
                                '03-'.strval(rand(1111,9999)).'-'.strval(rand(1111,9999)).'-'.strval(rand(1111,9999)).'-'.strval(rand(1111,9999))];

            $browser->assertSee(Util::langtext('SIDEBAR_SUB_LI_001'))
                    ->assertSee(Util::langtext('CUSTOMER_T_003'))
                    // mandatory fields
                    ->assertInputValueIsNot('#customer-id','')
                    ->assertInputValueIsNot('#code','')
                    ->assertInputValueIsNot('#customer-name','')
                    ->assertInputValueIsNot('#name','')
                    // none mandatory fields
                    ->assertInputValueIsNot('#post-no', $editInitLoad[0])
                    ->assertInputValueIsNot('#fax', $editInitLoad[1])
                    ->assertInputValueIsNot('#tel', $editInitLoad[2]);

            $browser->script('location.reload()'); //refresh page
        });
    }
    
    /**
     * Check Customer Destination Edit for require field response
     * @return void
     */
    public function testCustomerDestEditReqFieldResponse()
    {
        $this->browse(function (Browser $browser)
        {
            PHPUnit::assertStringContainsString($this->customerDestinationEditPath[0], $browser->driver->getCurrentURL());
            PHPUnit::assertStringContainsString($this->customerDestinationEditPath[1], $browser->driver->getCurrentURL());

            $browser->assertSee(Util::langtext('SIDEBAR_SUB_LI_001'))
                    ->assertSee(Util::langtext('CUSTOMER_T_003'))
                    ->value('#code','')
                    ->value('#name','')
                    ->assertInputValue('#code','')
                    ->assertInputValue('#name','')
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_001, [Util::langtext('CUST_DEST_L_002')]))
                    ->assertSee(Message::getMessage(Message::ERROR_001, [Util::langtext('CUST_DEST_L_003')]));

            $browser->script('location.reload()'); //refresh page
        });
    }
    
    /**
     * Customer Destination Edit existing entry 
     * @return customerDestCode
     */
    public function testCustomerDestEditEntry()
    {
        $this->setCustDestCode();

        $this->browse(function (Browser $browser)
        {
            PHPUnit::assertStringContainsString($this->customerDestinationEditPath[0], $browser->driver->getCurrentURL());
            PHPUnit::assertStringContainsString($this->customerDestinationEditPath[1], $browser->driver->getCurrentURL());

            $browser->assertSee(Util::langtext('SIDEBAR_SUB_LI_001'))
                    ->assertSee(Util::langtext('CUSTOMER_T_003'))
                    ->assertInputValueIsNot('#customer-id','')
                    ->value('#code', $this->getCustDestCode())
                    ->value('#name-kana', $this->entryList[0])
                    ->value('#post-no', $this->entryList[1])
                    ->value('#address-2', $this->entryList[2])
                    ->value('#fax', $this->entryList[3])
                    ->assertInputValueIsNot('#customer-name','')
                    ->value('#name', $this->entryList[4])
                    ->value('#prefecture-id', $this->entryList[5])
                    ->value('#address-1', $this->entryList[6])
                    ->value('#tel', $this->entryList[7])
                    ->value('#kiduke-kanji', $this->entryList[8])
                    ->click('button[type="submit"]')
                    ->pause(2000)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('CUST_DEST_T_001')]));

            PHPUnit::assertStringContainsString($this->customerDestEditRes, $browser->driver->getCurrentURL());
        });

        return $customerDestCode = $this->getCustDestCode();
    }
    
    /**
     * Customer Destination - Edit - edit a different row with already existing Customer Destination Code
     * @depends testCustomerDestEditEntry
     * @return void
     */
    public function testCustomerDestEditDuplicateCustomerCode($customerDestCode)
    {
        $this->customerDestinationCode = $customerDestCode;

        $this->browse(function (Browser $browser)
        {
            PHPUnit::assertStringContainsString($this->customerDestEditRes, $browser->driver->getCurrentURL());
            
            $browser->assertPathIsNot($this->customerPath)
                    ->assertSee(Util::langtext('SIDEBAR_SUB_LI_001'))
                    ->assertVisible('#customer-destination-table > tbody > tr:nth-child(2) > td:nth-child(4) > a')
                    ->click('#customer-destination-table > tbody > tr:nth-child(2) > td:nth-child(4) > a') // edit a diff row
                    ->pause(2000)
                    //at customer destination edit screen 
                    ->assertSee(Util::langtext('SIDEBAR_SUB_LI_001'))
                    ->assertSee(Util::langtext('CUSTOMER_T_003'))
                    ->assertInputValueIsNot('#customer-id','')
                    ->assertInputValueIsNot('#customer-name','')
                    ->value('#code',$this->customerDestinationCode)
                    ->value('#name', $this->entryList[4])
                    ->click('button[type="submit"]')
                    // respond with error message
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_010, [Util::langtext('CUST_DEST_L_002')]));

            $browser->script('location.reload()'); //refresh page
        });
    }
    
    /**
     * Customer Destination - Edit a entry with invalid Customer Destination - Code
     * @depends testCustomerDestEditDuplicateCustomerCode
     * @return void
     */
    public function testCustomerDestEditInvalidCustomerCode()
    {        
        $this->browse(function (Browser $browser)
        {
            PHPUnit::assertStringContainsString($this->customerDestinationEditPath[0], $browser->driver->getCurrentURL());
            PHPUnit::assertStringContainsString($this->customerDestinationEditPath[1], $browser->driver->getCurrentURL());
            
            $browser->assertPathIsNot($this->customerPath)
                    ->assertSee(Util::langtext('SIDEBAR_SUB_LI_001'))
                    ->assertSee(Util::langtext('CUSTOMER_T_003'))
                    ->assertInputValueIsNot('#customer-id','')
                    ->assertInputValueIsNot('#customer-name','')
                    ->value('#code','Invld12')
                    ->value('#name', $this->entryList[4])
                    ->click('button[type="submit"]')
                    // respond with error message
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_005, [Util::langtext('CUST_DEST_L_002')]));

            $browser->script('location.reload()'); //refresh page
        });
    }
    
    /**
     * Customer Destination - Edit - edit current entry with exceeding allowable inputs
     * @return void
     */
    public function testCustomerDestEditExceedInputs()
    {
        $this->browse(function (Browser $browser)
        {
            PHPUnit::assertStringContainsString($this->customerDestinationEditPath[0], $browser->driver->getCurrentURL());
            PHPUnit::assertStringContainsString($this->customerDestinationEditPath[1], $browser->driver->getCurrentURL());

            //handle invalid exeeding Inputs
            // for code, postal code, fax and tel
            $invalElem = [strval(rand(11111110,9999999999)), strval(rand(1111111100,9999999999)), '+5555111118888888877777','03-5555-7777-10101-1555'];            

            // read browser
            $browser->assertSee(Util::langtext('SIDEBAR_SUB_LI_001'))
                    ->assertSee(Util::langtext('CUSTOMER_T_003'))
                    ->assertInputValueIsNot('#customer-id','')
                    ->assertInputValueIsNot('#customer-name','')
                    ->value('#code', $invalElem[0])
                    ->value('#name', $this->entryList[4])
                    ->value('#post-no', $invalElem[1])
                    ->value('#fax', $invalElem[2])
                    ->value('#tel', $invalElem[3])
                    ->click('button[type="submit"]')
                    // respond with error message
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_009, [Util::langtext('CUST_DEST_L_002'), '1','7']))
                    ->assertSee(Message::getMessage(Message::ERROR_008, [Util::langtext('CUST_DEST_L_022'), '7']))
                    ->assertSee(Message::getMessage(Message::ERROR_009, [Util::langtext('CUST_DEST_L_025'),'4','20']))
                    ->assertSee(Message::getMessage(Message::ERROR_009, [Util::langtext('CUST_DEST_L_026'),'4','20']));

            $browser->script('location.reload()'); //refresh page
        });
    }
}
