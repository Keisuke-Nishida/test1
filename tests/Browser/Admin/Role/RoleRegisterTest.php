<?php

namespace Tests\Browser;

use App\Lib\Message;
use App\Lib\Util;
use App\Models\Menu;
use Illuminate\Foundation\Testing\Assert as PHPUnit;
use Illuminate\Foundation\Testing\DatabaseMigrations;
Use Faker\Factory as Faker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RoleRegisterTest extends DuskTestCase
{
    protected $test_url = "/admin/role_menu";
    protected $test_create_url = "/admin/role_menu/create/";
    protected $test_edit_url = "/admin/role_menu/edit/";
    protected $login_field = "#login-id";
    protected $pass_field = "#password";

    protected $reg_name_field = '#name';
    protected $reg_type_field = '#type';
    protected $reg_menu_field = '#available-menus';
    protected $search_name_field = '#search-name';
    protected $search_type_field = '#search-type';
    protected $search_menu_field = '#search-menu';
    protected $detail_search_submit = '#role-menu-detailed-search-submit';
    protected $checkbox_select_all = '#role-menu-select-all';
    protected $multiple_delete_btn = '#role-menu-multiple-delete-button';
    protected $move_left_btn = '#menu-move-left';
    protected $move_right_btn = '#menu-move-right';

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
     * Test for initial page
     */
    public function testInitialPage()
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
     * test input required empty input
     */
    public function testRegisterRequired()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->test_create_url)
                    ->pause(1000)
                    ->value($this->reg_name_field,'')
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_001, [Util::langtext('ROLE_MENU_L_011')]));
        });
    }

    /**
     * Test name reg invalid function
     */
    public function testRegisterInvalidName()
    {
        $this->browse(function (Browser $browser)
        {
            $faker = Faker::create();
            $name = $faker->text(100);
            $browser->visit($this->test_create_url)
                    ->pause(1000)
                    ->value($this->reg_name_field, $name)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_006, [Util::langtext('ROLE_MENU_L_011'), '50']));
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
            $name = $faker->company . ' ' . $faker->companySuffix;
            $menu_data = Menu::where('deleted_at', null)->where('type', 1)->inRandomOrder()->first();
            $browser->visit($this->test_create_url)
                    ->pause(1000)
                    ->value($this->reg_name_field, $name)
                    ->select($this->reg_menu_field, $menu_data->id)
                    ->click($this->move_right_btn)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::INFO_001, [Util::langtext('SIDEBAR_LI_004')]));
            $browser = $this->searchAssertTable($browser,
            $this->search_name_field, $name, self::COL_NAME);
            $browser = $this->searchReset($browser);
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
            $name = $faker->company . ' ' . $faker->companySuffix;
            $menu_data = Menu::where('deleted_at', null)->where('type', 1)->inRandomOrder()->first();
            $browser->visit($this->test_create_url)
                    ->pause(1000)
                    ->value($this->reg_name_field, $name)
                    ->select($this->reg_menu_field, $menu_data->id)
                    ->click($this->move_right_btn)
                    ->clickLink(Util::langtext('ROLE_MENU_B_006'))
                    ->pause(1000)
                    ->press('OK')
                    ->assertPathIs($this->test_url."/index")
                    ->click('#search-toggle-button')
                    ->pause(2000)
                    ->type($this->search_name_field, $name)
                    ->click($this->detail_search_submit)
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::EMPTY_DATA)->getSelector(),
                        Util::langtext('DATA_TABLE_EMPTY_TEXT'));
            
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
