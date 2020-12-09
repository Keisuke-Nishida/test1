<?php

namespace Tests\Browser;

use App\Lib\Message;
use App\Lib\Util;
use App\Models\Menu;
use App\Models\Role;
use App\Models\RoleMenu;
use Illuminate\Foundation\Testing\Assert as PHPUnit;
use Illuminate\Foundation\Testing\DatabaseMigrations;
Use Faker\Factory as Faker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RoleUpdateTest extends DuskTestCase
{
    protected $test_url = "/admin/role_menu";
    protected $test_create_url = "/admin/role_menu/create/";
    protected $test_edit_url = "/admin/role_menu/edit/";
    protected $login_field = "#login-id";
    protected $pass_field = "#password";

    protected $input_name_field = '#name';
    protected $input_type_field = '#type';
    protected $input_menu_field = '#available-menus';
    protected $input_sel_menu_field = '#selected-menus';
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
     * Create a new List Test instance
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
        $table_data = Role::where('deleted_at', null)
                    ->where('id', env('DUSK_TEST_ROLE_ID'))
                    ->first();
        $this->browse( function (Browser $browser) use ($table_data)
        {
            // check edit link
            $browser->visit($this->test_url)
                    ->pause(1000)
                    ->assertVisible("#edit-id-$table_data->id")
                    ->click("#edit-id-$table_data->id")
                    ->assertPathIs($this->test_edit_url.$table_data->id)
                    ->pause(1000);
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
            $name = $faker->company . ' ' . $faker->companySuffix;
            // edit
            $browser->visit($this->test_edit_url . $table_data->id)
                    ->pause(1000)
                    ->value($this->input_name_field, $name)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_004')]));
            $browser = $this->searchAssertTable($browser, $this->search_name_field,
                        $name, self::COL_NAME);

            // rollback
            $browser->visit($this->test_edit_url . $table_data->id)
                    ->pause(1000)
                    ->value($this->input_name_field, $table_data->name)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_004')]));
            $browser = $this->searchAssertTable($browser, $this->search_name_field,
                        $table_data->name, self::COL_NAME);
        });
    }

    /**
     * Test name update valid function
     * @depends testInitialPage
     */
    public function testEditInValidName($table_data)
    {
        $this->browse(function (Browser $browser) use ($table_data)
        {
            $browser->visit($this->test_edit_url . $table_data->id)
                    ->pause(1000)
                    ->value($this->input_name_field, '')
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_001, [Util::langtext('ROLE_MENU_L_011')]));
        });
    }

    /**
     * Test type update valid function
     * @depends testInitialPage
     */
    public function testEditType($table_data)
    {
        $this->browse(function (Browser $browser) use ($table_data)
        {
            $role_menu = RoleMenu::select('menu_id')
                                ->where('role_id', $table_data->id)
                                ->where('deleted_at', null)
                                ->get();
            $type = ($table_data->type == 2) ? 1 : 2;
            $menu = Menu::where('deleted_at', null)->where('type', $type)->first();
            // edit
            $browser->visit($this->test_edit_url . $table_data->id)
                    ->pause(1000)
                    ->select($this->input_type_field, $type)
                    ->select($this->input_menu_field, $menu->id)
                    ->click($this->move_right_btn)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_004')]))
                    ->click('#search-toggle-button')
                    ->pause(1000)
                    ->value($this->search_name_field, $table_data->name)
                    ->click($this->detail_search_submit)
                    ->pause(2000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1, self::COL_AUTH_TYPE)->getSelector(), $type)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1, self::COL_AVAIL_MENU)->getSelector(), $menu->name);

            // rollback
            $browser->visit($this->test_edit_url . $table_data->id)
                    ->pause(1000)
                    ->select($this->input_type_field, $table_data->type);
            foreach($role_menu as $item)
            {                
                $browser->select($this->input_menu_field, $item->menu_id)
                        ->click($this->move_right_btn);
            }
            $browser = $browser->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_004')]))
                    ->click('#search-toggle-button')
                    ->pause(1000)
                    ->value($this->search_name_field, $table_data->name)
                    ->click($this->detail_search_submit)
                    ->pause(2000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1, self::COL_AUTH_TYPE)->getSelector(), $table_data->type)
                    ->assertDontSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1, self::COL_AVAIL_MENU)->getSelector(), $menu->name);
        });
    }

    /**
     * Test menu update valid function
     * @depends testInitialPage
     */
    public function testEditMenu($table_data)
    {
        $this->browse(function (Browser $browser) use ($table_data)
        {
            $role_menu = RoleMenu::select('menu_id')
                                ->where('deleted_at', null)
                                ->where('role_id', $table_data->id)
                                ->get();
            $menus_sel = Menu::where('deleted_at', null)
                            ->whereIn('id', $role_menu)
                            ->get();
            $menu = Menu::whereNotIn('id', $role_menu)
                    ->where('deleted_at', null)
                    ->first();
            // edit
            $browser->visit($this->test_edit_url . $table_data->id)
                    ->pause(1000)
                    ->select($this->input_menu_field, $menu->id)
                    ->click($this->move_right_btn)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_004')]))
                    ->click('#search-toggle-button')
                    ->pause(1000)
                    ->value($this->search_name_field, $table_data->name)
                    ->click($this->detail_search_submit)
                    ->pause(2000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1, self::COL_AVAIL_MENU)->getSelector(), $menu->name);

            // rollback
            $browser->visit($this->test_edit_url . $table_data->id);
            foreach ($menus_sel as $value) {
                $browser = $browser->select($this->input_sel_menu_field, $value->id);
            }
            $browser->pause(1000)
                    ->click($this->move_left_btn)
                    ->pause(1000)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_004')]))
                    ->click('#search-toggle-button')
                    ->pause(1000)
                    ->value($this->search_name_field, $table_data->name)
                    ->click($this->detail_search_submit)
                    ->pause(2000)
                    ->assertDontSeeIn($this->duskTable->table_row_data(
                        $this->table_name, 1, self::COL_AVAIL_MENU)->getSelector(), $menu->name);
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
}
