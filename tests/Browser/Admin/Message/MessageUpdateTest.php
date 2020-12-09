<?php

namespace Tests\Browser;

use App\Lib\Message;
use App\Lib\Util;
use App\Models\Message as ModelMessage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
Use Faker\Factory as Faker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MessageUpdateTest extends DuskTestCase
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
        $table_data = $this->getMessageModel()->where('deleted_at', null)->first();
        $this->browse( function (Browser $browser) use ($table_data)
        {
            // check edit link
            $browser->visit($this->test_url)
                    ->pause(1000)
                    ->assertVisible("#edit-id-$table_data->id")
                    ->click("#edit-id-$table_data->id")
                    ->assertPathIs($this->test_edit_url.$table_data->id);
        });
        return $table_data;
    }

    /**
     * Test name update valid function
     * @depends testInitialPage
     */
    public function testEditValidName($table_data)
    {
        $this->browse(function (Browser $browser) use ($table_data)
        {
            $faker = Faker::create();
            $name = $faker->word;

            // edit
            $browser->visit($this->test_edit_url . $table_data->id)
                    ->pause(1000)
                    ->value($this->input_name_field, $name)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::COL_NAME)->getSelector(), $name)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_010')]));

            // rollback
            $browser->visit($this->test_edit_url . $table_data->id)
                    ->pause(1000)
                    ->value($this->input_name_field, $table_data->name)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::COL_NAME)->getSelector(), $table_data->name)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_010')]));
        });
    }

    /**
     * Test name update invalid function
     * @depends testInitialPage
     */
    public function testEditInvalidName($table_data)
    {
        $this->browse(function (Browser $browser) use ($table_data)
        {
            $faker = Faker::create();
            $name = $faker->text(100);
            $browser->visit($this->test_edit_url . $table_data->id)
                    ->pause(1000)
                    ->value($this->input_name_field, $name)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_002, [Util::langtext('MESSAGE_L_001'), '50']));
        });
    }

    /**
     * Test key update valid function
     * @depends testInitialPage
     */
    public function testEditValidKey($table_data)
    {
        $this->browse(function (Browser $browser) use ($table_data)
        {
            $faker = Faker::create();
            $key = $faker->text(10);

            // edit
            $browser->visit($this->test_edit_url . $table_data->id)
                    ->pause(1000)
                    ->value($this->input_key_field, $key)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::COL_KEY)->getSelector(), $key)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_010')]));

            // rollback
            $browser->visit($this->test_edit_url . $table_data->id)
                    ->pause(1000)
                    ->value($this->input_key_field, $table_data->key)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::COL_KEY)->getSelector(), $table_data->key)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_010')]));
        });
    }

    /**
     * Test key update invalid function
     * @depends testInitialPage
     */
    public function testEditInvalidKey($table_data)
    {
        $this->browse(function (Browser $browser) use ($table_data)
        {
            $faker = Faker::create();
            $key = $faker->text(100);
            $browser->visit($this->test_edit_url . $table_data->id)
                    ->pause(1000)
                    ->value($this->input_key_field, $key)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_002, [Util::langtext('MESSAGE_L_002'), '50']));
        });
    }

    /**
     * Test value update valid function
     * @depends testInitialPage
     */
    public function testEditValidValue($table_data)
    {
        $this->browse(function (Browser $browser) use ($table_data)
        {
            $faker = Faker::create();
            $value = $faker->sentence;

            // edit
            $browser->visit($this->test_edit_url . $table_data->id)
                    ->pause(1000)
                    ->value($this->input_value_field, $value)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::COL_VALUE)->getSelector(), $value)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_010')]));

            // rollback
            $browser->visit($this->test_edit_url . $table_data->id)
                    ->pause(1000)
                    ->value($this->input_value_field, $table_data->value)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::COL_VALUE)->getSelector(), $table_data->value)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_010')]));
        });
    }

    /**
     * Test value update invalid function
     * @depends testInitialPage
     */
    public function testEditInvalidValue($table_data)
    {
        $this->browse(function (Browser $browser) use ($table_data)
        {
            $faker = Faker::create();
            $value = $faker->text(200).$faker->text(200);
            $browser->visit($this->test_edit_url . $table_data->id)
                    ->pause(1000)
                    ->value($this->input_value_field, $value)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_002, [Util::langtext('MESSAGE_L_003'), '255']));
        });
    }

    /**
     * Return message model
     */
    private function getMessageModel()
    {
        return ModelMessage::where('deleted_at', null);
    }
}
