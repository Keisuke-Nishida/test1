<?php

namespace Tests\Browser;

use App\Lib\Message;
use App\Lib\Util;
use App\Models\Customer;
use App\Models\Prefecture;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
Use Faker\Factory as Faker;
use Illuminate\Support\Arr;
use Tests\DuskTestCase;

class CustomerUpdateTest extends DuskTestCase
{
    protected $test_url = "/admin/customer";
    protected $test_edit_url = "/admin/customer/edit";
    protected $login_field = "#login-id";
    protected $pass_field = "#password";
    protected $duskTable;
    protected $table_name = "customer-table";

    protected $edit_code_field = '#code';
    protected $edit_name_field = '#name';
    protected $edit_post_no_field = '#post-no';
    protected $edit_tel_field = '#tel';
    protected $edit_fax_field = '#fax';
    protected $edit_uriage_field = '#uriage-';

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
     * Test for initial page in customer update
     */
    public function testCustomerUpdateInitialPageLoadded()
    {
        $customer = Customer::first();
        $this->browse(function (Browser $browser) use ($customer)
        {
            $browser->visit($this->test_url)
                    ->pause(1000)
                    ->click('#edit-id-'.$customer->id)
                    ->pause(1000)
                    ->assertPathIs($this->test_edit_url . '/'. $customer->id);
        });
        return $customer;
    }

    /**
     * Test customer search update valid function
     * @depends testCustomerUpdateInitialPageLoadded
     */
    public function testCustomerEditValidCode($customer)
    {
        $this->browse(function (Browser $browser) use ($customer)
        {
            $faker = Faker::create();
            $code = $faker->unique()->randomNumber(7);
            // edit
            $browser->visit($this->test_edit_url . '/'. $customer->id)
                    ->pause(1000)
                    ->value($this->edit_code_field, $code)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::CUST_CODE)->getSelector(), $code)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_003')]));
            // rollback
            $browser->visit($this->test_edit_url . '/'. $customer->id)
                    ->pause(1000)
                    ->value($this->edit_code_field, $customer->code)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::CUST_CODE)->getSelector(), $customer->code)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_003')]));
        });
    }

    /**
     * Test customer update code invalid function
     * @depends testCustomerUpdateInitialPageLoadded
     */
    public function testCustomerEditInvalidCode($customer)
    {
        $this->browse(function (Browser $browser) use ($customer)
        {
            $browser->visit($this->test_edit_url . '/'. $customer->id)
                    ->pause(1000)
                    ->value($this->edit_code_field, 'as@dasd')
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_005, [Util::langtext('CUSTOMER_L_019')]));
        });
    }

    /**
     * Test customer update code update valid function
     * @depends testCustomerUpdateInitialPageLoadded
     */
    public function testCustomerEditValidPostNo($customer)
    {
        $this->browse(function (Browser $browser) use ($customer)
        {
            $faker = Faker::create();
            $post_no = substr(str_replace(['-', ' '], '', $faker->postcode . $faker->postcode), 0, 7);
            // edit
            $browser->visit($this->test_edit_url . '/'. $customer->id)
                    ->pause(1000)
                    ->value($this->edit_post_no_field, $post_no)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_003')]));
            // rollback
            $browser->visit($this->test_edit_url . '/'. $customer->id)
                    ->pause(1000)
                    ->value($this->edit_post_no_field, $customer->code)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_003')]));
        });
    }

    /**
     * Test customer update code invalid function
     * @depends testCustomerUpdateInitialPageLoadded
     */
    public function testCustomerEditInvalidPostNo($customer)
    {
        $this->browse(function (Browser $browser) use ($customer)
        {
            // Numeric
            $browser->visit($this->test_edit_url . '/'. $customer->id)
                    ->pause(1000)
                    ->value($this->edit_post_no_field, 'as@dasd')
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_005, [Util::langtext('CUSTOMER_L_022')]));
            // length
            $browser->visit($this->test_edit_url . '/'. $customer->id)
                    ->pause(1000)
                    ->value($this->edit_post_no_field, '12345678')
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_008, [Util::langtext('CUSTOMER_L_022'), '7']));
        });
    }

    /**
     * Test customer update tel valid function
     * @depends testCustomerUpdateInitialPageLoadded
     */
    public function testCustomerEditValidTel($customer)
    {
        $this->browse(function (Browser $browser) use ($customer)
        {
            $faker = Faker::create();
            $tel = substr(str_replace(['-', 'x', '(', ')', '+', '.', ' '], '', $faker->phoneNumber . $faker->phoneNumber), 0, 20);
            // edit
            $browser->visit($this->test_edit_url . '/'. $customer->id)
                    ->pause(1000)
                    ->value($this->edit_tel_field, $tel)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::CUST_PHONE)->getSelector(), $tel)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_003')]));
            // rollback
            $browser->visit($this->test_edit_url . '/'. $customer->id)
                    ->pause(1000)
                    ->value($this->edit_tel_field, $customer->tel)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::CUST_PHONE)->getSelector(), $customer->tel)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_003')]));
        });
    }

    /**
     * Test customer update tel invalid function
     * @depends testCustomerUpdateInitialPageLoadded
     */
    public function testCustomerEditInvalidTel($customer)
    {
        $tel = 123456789123456789123;
        $this->browse(function (Browser $browser) use ($customer, $tel)
        {
            $browser->visit($this->test_edit_url . '/'. $customer->id)
                    ->pause(1000)
                    ->value($this->edit_tel_field, $tel)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_027'), '4', '20']));
        });
    }

    /**
     * Test customer update fax valid function
     * @depends testCustomerUpdateInitialPageLoadded
     */
    public function testCustomerEditValidFax($customer)
    {
        $this->browse(function (Browser $browser) use ($customer)
        {
            $faker = Faker::create();
            $fax = substr(str_replace(['-', 'x', '(', ')', '+', '.', ' '], '', $faker->phoneNumber . $faker->phoneNumber), 0, 20);
            // edit
            $browser->visit($this->test_edit_url . '/'. $customer->id)
                    ->pause(1000)
                    ->value($this->edit_fax_field, $fax)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_003')]));
            // rollback
            $browser->visit($this->test_edit_url . '/'. $customer->id)
                    ->pause(1000)
                    ->value($this->edit_fax_field, $customer->fax)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_003')]));
        });
    }

    /**
     * Test customer update fax invalid function
     * @depends testCustomerUpdateInitialPageLoadded
     */
    public function testCustomerEditInvalidFax($customer)
    {
        $fax = 123456789123456789123;
        $this->browse(function (Browser $browser) use ($customer, $fax)
        {
            $browser->visit($this->test_edit_url . '/'. $customer->id)
                    ->pause(1000)
                    ->value($this->edit_fax_field, $fax)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_028'), '4', '20']));
        });
    }

    /**
     * Test customer update fax valid function
     * @depends testCustomerUpdateInitialPageLoadded
     */
    public function testCustomerEditValidUriage($customer)
    {
        $this->browse(function (Browser $browser) use ($customer)
        {
            $faker = Faker::create();
            $uriage = $faker->numberBetween(1, 99);
            $uriage_field = $faker->numberBetween(1, 8);
            $uriage_db = 'uriage_'. $uriage_field;
            // edit
            $browser->visit($this->test_edit_url . '/'. $customer->id)
                    ->pause(1000)
                    ->value($this->edit_uriage_field.$uriage_field, $uriage)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_003')]));
            // rollback
            $browser->visit($this->test_edit_url . '/'. $customer->id)
                    ->pause(1000)
                    ->value($this->edit_uriage_field.$uriage_field, $customer[$uriage_db])
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_003')]));
        });
    }

    /**
     * Test customer update uriage invalid function
     * @depends testCustomerUpdateInitialPageLoadded
     */
    public function testCustomerEditInvalidUriage($customer)
    {
        $this->browse(function (Browser $browser) use ($customer)
        {
            $faker = Faker::create();
            $uriage = $faker->numberBetween(100,999);
            $uriage_field = $faker->numberBetween(1, 8);
            $error_c = 28 + $uriage_field;
            $error_int_m = Message::getMessage(Message::ERROR_005, [Util::langtext('CUSTOMER_L_0'.$error_c)]);
            $error_digit_m = Message::getMessage(Message::ERROR_009, [Util::langtext('CUSTOMER_L_0'.$error_c), '1', '2']);
            // lengths
            $browser->visit($this->test_edit_url . '/'. $customer->id)
                    ->pause(1000)
                    ->value($this->edit_uriage_field.$uriage_field, $uriage)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee($error_digit_m);
            // integer
            $browser->visit($this->test_edit_url . '/'. $customer->id)
                    ->pause(1000)
                    ->value($this->edit_uriage_field.$uriage_field, "asd")
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee($error_int_m);
        });
    }

    /**
     * Test customer edit valid
     * @depends testCustomerUpdateInitialPageLoadded
     */
    public function testCustomerEditValid($customer)
    {
        $this->browse(function (Browser $browser) use ($customer)
        {   
            $faker = Faker::create();
            $code = $faker->unique()->randomNumber(7);
            $name = $faker->company . ' ' . $faker->companySuffix;
            $prefecture_id = $this->getRandPrefectureId();
            $browser->visit($this->test_edit_url . '/'. $customer->id)
                    ->pause(1000)
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
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_003')]));
            // Rollback
            $browser->visit($this->test_edit_url . '/'. $customer->id)
                    ->pause(1000)
                    ->value('#code', $customer->code)
                    ->value('#name', $customer->name)
                    ->value('#name-kana', $customer->name_kana)
                    ->value('#prefecture-id', $customer->prefecture_id)
                    ->value('#post-no', $customer->post_no)
                    ->value('#address-1', $customer->address_1)
                    ->value('#address-2', $customer->address_2)
                    ->value('#kiduke-kanji', $customer->kiduke_kanji)
                    ->value('#tel', $customer->tel)
                    ->value('#fax', $customer->fax)
                    ->value('#uriage-1',  $customer->uriage_1)
                    ->value('#uriage-2',  $customer->uriage_2)
                    ->value('#uriage-3',  $customer->uriage_3)
                    ->value('#uriage-4',  $customer->uriage_4)
                    ->value('#uriage-5',  $customer->uriage_5)
                    ->value('#uriage-6',  $customer->uriage_6)
                    ->value('#uriage-7',  $customer->uriage_7)
                    ->value('#uriage-8',  $customer->uriage_8)
                    ->value('#core-system-flag', $customer->core_system_flag)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_003')]));
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
