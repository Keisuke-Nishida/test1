<?php

namespace Tests\Browser;

use App\Lib\Message;
use App\Lib\Util;
use App\Models\Prefecture;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Arr;
Use Faker\Factory as Faker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CustomerRegisterTest extends DuskTestCase
{
    protected $test_url = "/admin/customer";
    protected $test_create_url = "/admin/customer/create";
    protected $login_field = "#login-id";
    protected $pass_field = "#password";
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
     * Create a new Customer Register Test instance
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

    /**
     * Test for initial page in customer register
     */
    public function testCustomerRegisterInitialPageLoadded()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->assertPathIs($this->test_url)
                    ->pause(1000)
                    ->clickLink(Util::langtext('CUSTOMER_B_004'))
                    ->assertPathIs($this->test_create_url);
        });
    }

    /**
     * test customer input required empty input
     */
    public function testCustomerRegisterRequired()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->test_create_url)
                    ->pause(1000)
                    ->value('#uriage-1',"1")
                    ->value('#uriage-2',"2")
                    ->value('#uriage-3',"3")
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_001, [Util::langtext('CUSTOMER_L_019')]))
                    ->assertSee(Message::getMessage(Message::ERROR_001, [Util::langtext('CUSTOMER_L_021')]));
        });
    }

    /**
     * test customer register valid
     */
    public function testCustomerRegisterValid()
    {
        $this->browse(function (Browser $browser)
        {
            $faker = Faker::create();
            $name = $faker->company . ' ' . $faker->companySuffix;
            $prefecture_id = $this->getRandPrefectureId();
            $code = $faker->unique()->randomNumber(7);
            $browser->visit($this->test_create_url)
                    ->pause(2000)
                    ->value('#code', $code)
                    ->value('#name', $name)
                    ->value('#name-kana', $name)
                    ->value('#prefecture-id', $prefecture_id)
                    ->value('#post-no', substr(str_replace(['-', ' '], '', $faker->postcode . $faker->postcode), 0, 7))
                    ->value('#address-1', $faker->streetName)
                    ->value('#address-2', $faker->streetAddress . ' ' . $faker->city)
                    ->value('#kiduke-kanji', substr($faker->catchPhrase, 0, 50))
                    ->value('#tel', substr(str_replace(['-', 'x', '(', ')', '+', '.', ' '], '', $faker->phoneNumber . $faker->phoneNumber), 0, 20))
                    ->value('#fax', substr(str_replace(['-', 'x', '(', ')', '+', '.', ' '], '', $faker->phoneNumber . $faker->phoneNumber), 0, 20))
                    ->value('#uriage-1',  $faker->numberBetween(1, 99))
                    ->value('#uriage-2',  $faker->numberBetween(1, 99))
                    ->value('#uriage-3',  $faker->numberBetween(1, 99))
                    ->value('#uriage-4',  $faker->numberBetween(1, 99))
                    ->value('#uriage-5',  $faker->numberBetween(1, 99))
                    ->value('#uriage-6',  $faker->numberBetween(1, 99))
                    ->value('#uriage-7',  $faker->numberBetween(1, 99))
                    ->value('#uriage-8',  $faker->numberBetween(1, 99))
                    ->value('#core-system-flag', $faker->numberBetween(0, 1))
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::INFO_001, [Util::langtext('SIDEBAR_LI_003')]))
                    ->click('#search-toggle-button')
                    ->pause(1000)
                    ->type('#search-customer-code', $code)
                    ->press(Util::langtext('CUSTOMER_B_002'))
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::CUST_CODE)->getSelector(), $code);
        });
    }

    /**
     * Test customer register input and click cancel
     */
    public function testCustomerRegisterInputButCancel()
    {
        $this->browse(function (Browser $browser)
        {
            $faker = Faker::create();
            $code = $faker->unique()->randomNumber(7);
            $name = $faker->company . ' ' . $faker->companySuffix;
            $prefecture_id = $this->getRandPrefectureId();
            $browser->visit($this->test_create_url)
                    ->pause(2000)
                    // check search fields as empty
                    ->value('#code', $code)
                    ->value('#name', $name)
                    ->value('#name-kana', $name)
                    ->value('#prefecture-id', $prefecture_id)
                    ->value('#post-no', substr(str_replace(['-', ' '], '', $faker->postcode . $faker->postcode), 0, 7))
                    ->value('#address-1', $faker->streetName)
                    ->value('#address-2', $faker->streetAddress . ' ' . $faker->city)
                    ->value('#kiduke-kanji', substr($faker->catchPhrase, 0, 50))
                    ->value('#tel', substr(str_replace(['-', 'x', '(', ')', '+', '.', ' '], '', $faker->phoneNumber . $faker->phoneNumber), 0, 20))
                    ->value('#fax', substr(str_replace(['-', 'x', '(', ')', '+', '.', ' '], '', $faker->phoneNumber . $faker->phoneNumber), 0, 20))
                    ->value('#uriage-1',  $faker->numberBetween(1, 99))
                    ->value('#uriage-2',  $faker->numberBetween(1, 99))
                    ->value('#uriage-3',  $faker->numberBetween(1, 99))
                    ->value('#uriage-4',  $faker->numberBetween(1, 99))
                    ->value('#uriage-5',  $faker->numberBetween(1, 99))
                    ->value('#uriage-6',  $faker->numberBetween(1, 99))
                    ->value('#uriage-7',  $faker->numberBetween(1, 99))
                    ->value('#uriage-8',  $faker->numberBetween(1, 99))
                    ->value('#core-system-flag', $faker->numberBetween(0, 1))
                    ->clickLink(Util::langtext('CUSTOMER_B_008'))
                    ->pause(1000)
                    ->press('OK')
                    ->pause(1000)
                    ->assertPathIs($this->test_url."/index")
                    ->click('#search-toggle-button')
                    ->pause(2000)
                    ->type('#search-customer-code', $code)
                    ->press(Util::langtext('CUSTOMER_B_002'))
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::EMPTY_DATA)
                        ->getSelector(), Util::langtext('DATA_TABLE_EMPTY_TEXT'));
        });
    }

    /**
     * Get random prefecture id
     */
    private function getRandPrefectureId()
    {
        $prefecture_ids = Prefecture::select('id')->whereNull('deleted_at')->get();
        $prefecture_ids = $prefecture_ids->toArray();
        $prefecture_id = Arr::random($prefecture_ids);
        $prefecture_id = $prefecture_id['id'];
        return $prefecture_id;
    }
}
