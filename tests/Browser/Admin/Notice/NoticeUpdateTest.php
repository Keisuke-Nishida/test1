<?php

namespace Tests\Browser;

use App\Lib\Message;
use App\Lib\Util;
use App\Models\NoticeData;
use DateTime;
use Illuminate\Foundation\Testing\Assert as PHPUnit;
use Illuminate\Foundation\Testing\DatabaseMigrations;
Use Faker\Factory as Faker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class NoticeUpdateTest extends DuskTestCase
{
    protected $test_url = "/admin/notice_data";
    protected $test_edit_url = "/admin/notice_data/edit";
    protected $login_field = "#login-id";
    protected $pass_field = "#password";

    protected $search_name_field = '#search-name';
    protected $search_title_field = '#search-title';
    protected $search_body_field = '#search-body';
    protected $detail_search_submit = '#notice_data-detailed-search-submit';
    protected $checkbox_select_all = '#notice_data-select-all';

    protected $name_field = '#name';
    protected $title_field = '#title';
    protected $body_field = '#body';
    protected $start_date_field = '#notice_data_start_date';
    protected $start_time_field = '#notice_data_start_time';
    protected $start_minute_field = '#notice_data_start_minute';
    protected $end_date_field = '#notice_data_end_date';
    protected $end_time_field = '#notice_data_end_time';
    protected $end_minute_field = '#notice_data_end_minute';

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
     * Test for initial page
     */
    public function testNoticeInitialPageLoadded()
    {
        $notice_data = NoticeData::where('deleted_at', null)->first();
        $this->browse( function (Browser $browser) use ($notice_data)
        {
            // check edit link
            $browser->visit($this->test_url)
                    ->pause(1000)
                    ->assertVisible("#edit-id-$notice_data->id")
                    ->click("#edit-id-$notice_data->id")
                    ->assertPathIs($this->test_edit_url.'/'.$notice_data->id);
        });
        return $notice_data;
    }

    /**
     * Test name update valid function
     * @depends testNoticeInitialPageLoadded
     */
    public function testNoticeEditValidName($notice_data)
    {
        $this->browse(function (Browser $browser) use ($notice_data)
        {
            $faker = Faker::create();
            $name = $faker->company . ' ' . $faker->companySuffix;
            // edit
            $browser->visit($this->test_edit_url . '/'. $notice_data->id)
                    ->pause(1000)
                    ->value($this->name_field, $name)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::NOTICE_NAME)->getSelector(), $name)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_005')]));
            // rollback
            $browser->visit($this->test_edit_url . '/'. $notice_data->id)
                    ->pause(1000)
                    ->value($this->name_field, $notice_data->name)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::NOTICE_NAME)->getSelector(), $notice_data->name)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_005')]));
        });
    }

    /**
     * Test name update invalid function
     * @depends testNoticeInitialPageLoadded
     */
    public function testNoticeEditInvalidName($notice_data)
    {
        $this->browse(function (Browser $browser) use ($notice_data)
        {
            $browser->visit($this->test_edit_url . '/'. $notice_data->id)
                    ->pause(1000)
                    ->value($this->name_field, '')
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_001, [Util::langtext('NOTICE_L_001')]));
        });
    }

    /**
     * Test title update valid function
     * @depends testNoticeInitialPageLoadded
     */
    public function testNoticeEditValidTitle($notice_data)
    {
        $this->browse(function (Browser $browser) use ($notice_data)
        {
            $faker = Faker::create();
            $title = $faker->text(10);

            // edit
            $browser->visit($this->test_edit_url . '/'. $notice_data->id)
                    ->pause(1000)
                    ->value($this->title_field, $title)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::NOTICE_TITLE)->getSelector(), $title)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_005')]));

            // rollback
            $browser->visit($this->test_edit_url . '/'. $notice_data->id)
                    ->pause(1000)
                    ->value($this->title_field, $notice_data->title)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::NOTICE_TITLE)->getSelector(), $notice_data->title)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_005')]));
        });
    }

    /**
     * Test title update invalid function
     * @depends testNoticeInitialPageLoadded
     */
    public function testNoticeEditInvalidTitle($notice_data)
    {
        $this->browse(function (Browser $browser) use ($notice_data)
        {
            $browser->visit($this->test_edit_url . '/'. $notice_data->id)
                    ->pause(1000)
                    ->value($this->title_field, '')
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_001, [Util::langtext('NOTICE_L_002')]));
        });
    }

    /**
     * Test body update valid function
     * @depends testNoticeInitialPageLoadded
     */
    public function testNoticeEditValidBody($notice_data)
    {
        $this->browse(function (Browser $browser) use ($notice_data)
        {
            $faker = Faker::create();
            $body = $faker->text(120);
            
            // edit
            $browser->visit($this->test_edit_url . '/'. $notice_data->id)
                    ->pause(1000)
                    ->value($this->body_field, $body)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::NOTICE_BODY)->getSelector(), $body)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_005')]));

            // rollback
            $browser->visit($this->test_edit_url . '/'. $notice_data->id)
                    ->pause(1000)
                    ->value($this->body_field, $notice_data->body)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::NOTICE_BODY)->getSelector(), $notice_data->body)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_005')]));
        });
    }

    /**
     * Test update body invalid function
     * @depends testNoticeInitialPageLoadded
     */
    public function testNoticeEditInvalidBody($notice_data)
    {
        $this->browse(function (Browser $browser) use ($notice_data)
        {
            $browser->visit($this->test_edit_url . '/'. $notice_data->id)
                    ->pause(1000)
                    ->value($this->body_field, '')
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_001, [Util::langtext('NOTICE_L_003')]));
        });
    }

    /**
     * Test start and edit update function
     * @depends testNoticeInitialPageLoadded
     */
    public function testNoticeEditStartEnd($notice_data)
    {
        $this->browse(function (Browser $browser) use ($notice_data)
        {
            $faker = Faker::create();
            $d_format = 'Y/m/d';
            $start_date = $this->dateRandDate(date($d_format), $faker->numberBetween(0,10));
            $start_time = $this->addZeroBelow10String($faker->numberBetween(0,23));
            $start_min = $this->addZeroBelow10String($faker->numberBetween(0,59));
            $end_date = $this->dateRandDate($start_date, $faker->numberBetween(0,10));
            $end_time = $this->addZeroBelow10String($faker->numberBetween(0,23));
            $end_min = $this->addZeroBelow10String($faker->numberBetween(0,59));
            
            $start_datetime_split = $this->dateTimeArray($notice_data->start_time);
            $end_datetime_split = $this->dateTimeArray($notice_data->end_time);
            $start_format = new DateTime($start_date);
            $end_format = new DateTime($end_date);

            // edit
            $browser->visit($this->test_edit_url . '/'. $notice_data->id)
                    ->pause(1000)
                    ->value($this->start_date_field, $start_date)
                    ->select($this->start_time_field, $start_time)
                    ->select($this->start_minute_field, $start_min)
                    ->value($this->end_date_field, $end_date)
                    ->select($this->end_time_field, $end_time)
                    ->select($this->end_minute_field, $end_min)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::NOTICE_DELIVERY_START)->getSelector(),
                        $start_format->format('Y-m-d'). " $start_time:$start_min:00")
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::NOTICE_DELIVERY_END)->getSelector(),
                        $end_format->format('Y-m-d'). " $end_time:$end_min:00")
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_005')]));

            // rollback
            $browser->visit($this->test_edit_url . '/'. $notice_data->id)
                    ->pause(1000)
                    ->value($this->start_date_field, $start_datetime_split[0])
                    ->select($this->start_time_field, $start_datetime_split[1])
                    ->select($this->start_minute_field, $start_datetime_split[2])
                    ->value($this->end_date_field, $end_datetime_split[0])
                    ->select($this->end_time_field, $end_datetime_split[1])
                    ->select($this->end_minute_field, $end_datetime_split[2])
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::NOTICE_DELIVERY_START)->getSelector(),
                        $notice_data->start_time)
                    ->assertSeeIn($this->duskTable->table_row_data(
                        $this->table_name,1,self::NOTICE_DELIVERY_END)->getSelector(),
                        $notice_data->end_time)
                    ->assertSee(Message::getMessage(Message::INFO_002, [Util::langtext('SIDEBAR_LI_005')]));
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

    private function dateTimeArray($dateTime)
    {
        $dt = new DateTime($dateTime);
        return array($dt->format('Y/m/d'), $dt->format('H'), $dt->format('i'));
    }
}
