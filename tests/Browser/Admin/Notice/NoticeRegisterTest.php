<?php

namespace Tests\Browser;

use App\Lib\Message;
use App\Lib\Util;
use App\Models\NoticeData;
use Illuminate\Foundation\Testing\Assert as PHPUnit;
use Illuminate\Foundation\Testing\DatabaseMigrations;
Use Faker\Factory as Faker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class NoticeRegisterTest extends DuskTestCase
{
    protected $test_url = "/admin/notice_data";
    protected $test_create_url = "/admin/notice_data/create";
    protected $login_field = "#login-id";
    protected $pass_field = "#password";

    protected $search_name_field = '#search-name';
    protected $search_title_field = '#search-title';
    protected $search_body_field = '#search-body';
    protected $detail_search_submit = '#notice_data-detailed-search-submit';
    protected $checkbox_select_all = '#notice_data-select-all';

    protected $reg_name_field = '#name';
    protected $reg_title_field = '#title';
    protected $reg_body_field = '#body';
    protected $reg_start_date_field = '#notice_data_start_date';
    protected $reg_start_time_field = '#notice_data_start_time';
    protected $reg_start_minute_field = '#notice_data_start_minute';
    protected $reg_end_date_field = '#notice_data_end_date';
    protected $reg_end_time_field = '#notice_data_end_time';
    protected $reg_end_minute_field = '#notice_data_end_minute';

    protected $duskTable;
    protected $table_name = 'notice_data-table';

    protected $table_length = '#notice_data-table_length';

    const EMPTY_DATA = 1;
    const NOTICE_CHECKBOX = 1;
    const NOTICE_NAME = 2;
    const NOTICE_EDIT = 3;
    const NOTICE_TITLE = 4;
    const NOTICE_BODY = 5;
    const NOTICE_DELIVERY_START = 6;
    const NOTICE_DELIVERY_END = 7;
    const NOTICE_DELETE = 8;
    
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
                    ->assertVisible('div.app-body > div.sidebar > nav.sidebar-nav > ul > li:nth-child(5) > a')
                    ->click('div.app-body > div.sidebar > nav.sidebar-nav > ul > li:nth-child(5) > a')
                    ->assertPathIs($this->test_url); 
        });
    }

    /**
     * Test for initial page in customer register
     */
    public function testNoticeRegisterInitialPageLoadded()
    {
        $this->browse( function(Browser $browser)
        {
            $browser->visit($this->test_url)
                    ->assertPathIs($this->test_url)
                    ->pause(1000)                    
                    ->clickLink(Util::langtext('NOTICE_B_002'))
                    ->assertPathIs($this->test_create_url);
        });
    }

    /**
     * test input required empty input
     */
    public function testNoticeRegisterRequired()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->test_create_url)
                    ->pause(1000)
                    ->value($this->reg_name_field,'')
                    ->value($this->reg_title_field,'')
                    ->value($this->reg_body_field,'')
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_001, [Util::langtext('NOTICE_L_001')])) //Notice name
                    ->assertSee(Message::getMessage(Message::ERROR_001, [Util::langtext('NOTICE_L_002')])) //Notice title
                    ->assertSee(Message::getMessage(Message::ERROR_001, [Util::langtext('NOTICE_L_003')])); //Notice body/text
        });
    }

    /**
     * test register valid
     */
    public function testNoticeRegisterValid()
    {
        $this->browse(function (Browser $browser)
        {
            $faker = Faker::create();
            $d_format = 'Y/m/d';
            $name = $faker->company . ' ' . $faker->companySuffix;
            $body = $faker->text(120);
            $title = $faker->text(10);
            $start_date = $this->dateRandDate(date($d_format), $faker->numberBetween(0,10));
            $start_time = $this->addZeroBelow10String($faker->numberBetween(0,23));
            $start_min = $this->addZeroBelow10String($faker->numberBetween(0,59));
            $end_date = $this->dateRandDate($start_date, $faker->numberBetween(0,10));
            $end_time = $this->addZeroBelow10String($faker->numberBetween(0,23));
            $end_min = $this->addZeroBelow10String($faker->numberBetween(0,59));

            $browser->visit($this->test_create_url)
                    ->pause(1000)
                    ->value($this->reg_name_field, $name)
                    ->value($this->reg_body_field, $body)
                    ->value($this->reg_title_field, $title)
                    ->value($this->reg_start_date_field, $start_date)
                    ->select($this->reg_start_time_field, $start_time)
                    ->select($this->reg_start_minute_field, $start_min)
                    ->value($this->reg_end_date_field, $end_date)
                    ->select($this->reg_end_time_field, $end_time)
                    ->select($this->reg_end_minute_field, $end_min)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::INFO_001, [Util::langtext('SIDEBAR_LI_005')]));

        });
    }

    /**
     * test register input and click cancel
     */
    public function testNoticeRegisterInputButCancel()
    {
        $this->browse(function (Browser $browser)
        {
            $faker = Faker::create();
            $d_format = 'Y/m/d';
            $name = $faker->company . ' ' . $faker->companySuffix;
            $body = $faker->text(120);
            $title = $faker->text(10);
            $start_date = $this->dateRandDate(date($d_format), $faker->numberBetween(0,10));
            $start_time = $this->addZeroBelow10String($faker->numberBetween(0,23));
            $start_min = $this->addZeroBelow10String($faker->numberBetween(0,59));
            $end_date = $this->dateRandDate($start_date, $faker->numberBetween(0,10));
            $end_time = $this->addZeroBelow10String($faker->numberBetween(0,23));
            $end_min = $this->addZeroBelow10String($faker->numberBetween(0,59));

            $browser->visit($this->test_create_url)
                    ->pause(1000)
                    ->value($this->reg_name_field, $name)
                    ->value($this->reg_body_field, $body)
                    ->value($this->reg_title_field, $title)
                    ->value($this->reg_start_date_field, $start_date)
                    ->select($this->reg_start_time_field, $start_time)
                    ->select($this->reg_start_minute_field, $start_min)
                    ->value($this->reg_end_date_field, $end_date)
                    ->select($this->reg_end_time_field, $end_time)
                    ->select($this->reg_end_minute_field, $end_min)
                    ->clickLink(Util::langtext('NOTICE_B_008'))
                    ->pause(1000)
                    ->press('OK')
                    ->assertPathIs($this->test_url."/index")
                    ->click('#search-toggle-button')
                    ->pause(2000)
                    ->type($this->search_title_field, $title)
                    ->press(Util::langtext('NOTICE_B_004'))
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::EMPTY_DATA)->getSelector(),
                        Util::langtext('DATA_TABLE_EMPTY_TEXT'));
        });
    }

    private function addZeroBelow10String($num)
    {
        return ($num > 9) ? (string) $num : '0'.$num;
    }

    private function dateRandDate($date, $addDate)
    {
        return date( 'Y/m/d', strtotime("$date +$addDate day") );
    }
}
