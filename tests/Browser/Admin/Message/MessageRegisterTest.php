<?php

namespace Tests\Browser;

use App\Lib\Message;
use App\Lib\Util;
use App\Models\Message as ModelMessage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
Use Faker\Factory as Faker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MessageRegisterTest extends DuskTestCase
{
    protected $test_url = "/admin/message";
    protected $test_create_url = "/admin/message/create";
    protected $test_edit_url = "/admin/message/edit/";
    protected $login_field = "#login-id";
    protected $pass_field = "#password";

    protected $search_name_field = '#search-name';
    protected $search_key_field = '#search-key';
    protected $search_value_field = '#search-value';

    protected $input_name_field = '#name';
    protected $input_key_field = '#key';
    protected $input_value_field = '#value';

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
     * Test for initial page
     */
    public function testInitialPage()
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
     * test input required empty input
     */
    public function testRegisterRequired()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->test_create_url)
                    ->pause(1000)
                    ->value($this->input_name_field,'')
                    ->value($this->input_key_field,'')
                    ->value($this->input_value_field,'')
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_001, [Util::langtext('MESSAGE_L_001')]))
                    ->assertSee(Message::getMessage(Message::ERROR_001, [Util::langtext('MESSAGE_L_002')]))
                    ->assertSee(Message::getMessage(Message::ERROR_001, [Util::langtext('MESSAGE_L_003')]));
        });
    }

    /**
     * Test name register invalid function
     */
    public function testRegisterInvalidName()
    {
        $this->browse(function (Browser $browser)
        {
            $faker = Faker::create();
            $name = $faker->text(100).$faker->text(100);
            $browser->visit($this->test_create_url)
                    ->pause(1000)
                    ->value($this->input_name_field, $name)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_002, [Util::langtext('MESSAGE_L_001'), '50']));
        });
    }

    /**
     * Test key reg invalid function
     */
    public function testRegisterInvalidKey()
    {
        $this->browse(function (Browser $browser)
        {
            $faker = Faker::create();
            $key = $faker->text(100);
            $browser->visit($this->test_create_url)
                    ->pause(1000)
                    ->value($this->input_key_field, $key)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_002, [Util::langtext('MESSAGE_L_002'), '50']));
        });
    }

    /**
     * Test value register invalid function
     */
    public function testRegisterInvalidValue()
    {
        $this->browse(function (Browser $browser)
        {
            $faker = Faker::create();
            $value = $faker->text(200).$faker->text(200).$faker->text(200);
            $browser->visit($this->test_create_url)
                    ->pause(1000)
                    ->value($this->input_value_field, $value)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_002, [Util::langtext('MESSAGE_L_003'), '255']));
        });
    }

    /**
     * test register valid
     */
    public function testRegisterValid()
    {
        $this->browse(function (Browser $browser)
        {
            $faker = Faker::create();
            $browser->visit($this->test_create_url)
                    ->pause(1000)
                    ->value($this->input_name_field, $faker->word)
                    ->value($this->input_key_field, $faker->text(10))
                    ->value($this->input_value_field, $faker->sentence)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::INFO_001, [Util::langtext('SIDEBAR_LI_010')]));

        });
    }

    /**
     * test register input and click cancel
     */
    public function testRegisterInputButCancel()
    {
        $this->browse(function (Browser $browser)
        {
            $faker = Faker::create();
            $name = $faker->word;
            $key = $faker->text(10);
            $browser->visit($this->test_create_url)
                    ->pause(1000)
                    ->value($this->input_name_field, $name)
                    ->value($this->input_key_field, $key)
                    ->value($this->input_value_field, $faker->sentence)
                    ->clickLink(Util::langtext('MESSAGE_B_008'))
                    ->pause(1000)
                    ->press('OK')
                    ->assertPathIs($this->test_url."/index")
                    ->click('#search-toggle-button')
                    ->pause(2000)
                    ->type($this->search_name_field, $name)
                    ->type($this->search_key_field, $key)
                    ->click($this->detail_search_submit)
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::EMPTY_DATA)->getSelector(),
                        Util::langtext('DATA_TABLE_EMPTY_TEXT'));

        });
    }
}
