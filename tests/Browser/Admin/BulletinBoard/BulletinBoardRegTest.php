<?php

namespace Tests\Browser;

use App\Lib\Message;
use App\Lib\Util;
use App\Models\BulletinBoardData;
use Illuminate\Foundation\Testing\Assert as PHPUnit;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;


class BulletinBoardRegTest extends DuskTestCase
{
    use WithFaker;
    
    protected $sessionLogIn, $dashboardIndex, $bulletinBoardPath;
    protected $bulletinBoardCreate, $bulletinBoardIndex;
    protected $name, $body, $title;
    protected $date_now, $date_next, $hrs, $mins;
    
    // Constructor and initiate data
    public function setUp(): void
    {
        parent::setUp();
        
        $this->sessionLogIn = '/admin/login';
        $this->dashboardIndex = '/admin/index';
        $this->bulletinBoardPath = '/admin/bulletin_board';
        $this->bulletinBoardCreate = '/admin/bulletin_board/create';
        $this->bulletinBoardIndex = '/admin/bulletin_board/index';
        
        $this->name = $this->faker->name;
        $this->title = $this->faker->title;
        $this->body = $this->faker->text;
        
        $this->date_now = strval(Date("Y/m/d", time()));
        $this->date_next = strval(Date("Y/m/d", strtotime('+5 days')));
        $this->hrs = strval(rand(10,23));
        $this->mins = strval(rand(11,59));
    }
    
    /**
     * start session for Bulletin Board test functions
     * @return void
     */
    public function testStartBulletinRegSession()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->sessionLogIn)
                    ->assertSee(Util::langtext('LOGIN_T_001'))
                    ->type('login_id', env('TEST_LOGIN_ID'))
                    ->type('password', env('TEST_PASSWORD'))
                    ->click('button[type="submit"]')
                    ->assertPathIs($this->dashboardIndex) //check if logged in and see dashboard index
                    ->pause(2000)
                    ->assertVisible('div.app-body > div.sidebar > nav.sidebar-nav > ul > li:nth-child(6) > a')
                    ->click('div.app-body > div.sidebar > nav.sidebar-nav > ul > li:nth-child(6) > a')
                    ->assertPathIs($this->bulletinBoardPath); 
        });
    }
    
    /*
     * Test initial load of table and create button redirection for Bullettin Board
     * @return void
     */
    public function testBulletinRegRedirect()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->assertPathIs($this->bulletinBoardPath)
                    ->assertSee(Util::langtext('BULLETIN_BOARD_L_005'))
                    // check user table visible with a loaded data row
                    ->assertVisible('#bulletin_board-table > tbody')
                    ->assertVisible('#bulletin_board-table > tbody > tr:nth-child(1) > td:nth-child(2)')
                    ->assertVisible('#bulletin_board-table > tbody > tr:nth-child(1) > td:nth-child(4)')
                    ->assertVisible('#bulletin_board-table > tbody > tr:nth-child(1) > td:nth-child(5)')
                    // check create button
                    ->assertVisible('div.card-body > div > div.row > div:nth-child(2) > a')
                    ->click('div.card-body > div > div.row > div:nth-child(2) > a')
                    ->pause(1000)
                    ->assertPathIs($this->bulletinBoardCreate);
            
            PHPUnit::assertStringContainsString($this->bulletinBoardCreate, $browser->driver->getCurrentURL());
        }); 
    }
    
    /*
     * Check for Bulletin Board create and initial input fields
     * @depends testBulletinRegRedirect 
     */
    public function testBulletinRegPageLoad()
    {
       $this->browse(function (Browser $browser)
        {
            $browser->assertPathIs($this->bulletinBoardCreate)
                    ->pause(1000)
                    ->assertSee(Util::langtext('BULLETIN_BOARD_T_003').Util::langtext('BULLETIN_BOARD_T_001'))
                    ->assertSee(Util::langtext('BULLETIN_BOARD_T_001'))
                    // check required fields if loaded empty
                    ->assertVisible('#name')
                    ->assertVisible('#title')
                    ->assertVisible('#body')
                    ->assertVisible('#bulletin_board_start_date')
                    ->assertVisible('#bulletin_board_start_time')
                    ->assertVisible('#bulletin_board_start_minute')
                    ->assertVisible('#bulletin_board_end_date')
                    ->assertVisible('#bulletin_board_end_time')
                    ->assertVisible('#bulletin_board_end_minute')
                    ->assertPresent('#bulletin_board_file')
                    ->assertPresent('#bulletin_board_file_label')
                    ->assertVisible('#bulletin_board_delete_file')
                    ->assertInputValue('#name','')
                    ->assertInputValue('#body','')
                    ->assertInputValue('#title','')
                    ->assertNotSelected('#bulletin_board_start_date','')
                    ->assertNotSelected('#bulletin_board_start_time','')
                    ->assertNotSelected('#bulletin_board_start_minute','')
                    ->assertNotSelected('#bulletin_board_end_date','')
                    ->assertNotSelected('#bulletin_board_end_time','')
                    ->assertNotSelected('#bulletin_board_end_minute','')
                    ->assertInputValueIsNot('#bulletin_board_file_label','');
                    
            // reload create page
            $browser->script('location.reload()');            
            $browser->pause(1000)
                    ->assertSee(Util::langtext('BULLETIN_BOARD_T_003').Util::langtext('BULLETIN_BOARD_T_001'));
        }); 
    }
    
    /**
     * Check for required field response in Bulletin Board create - name, title and body - empty
     * @return void
     */
    public function testBulletinRegReqInputResponse()
    {
       $this->browse(function (Browser $browser)
        {
            $browser->visit($this->bulletinBoardCreate)
                    ->assertPathIs($this->bulletinBoardCreate)
                    ->pause(1000)
                    ->assertSee(Util::langtext('BULLETIN_BOARD_T_003').Util::langtext('BULLETIN_BOARD_T_001'))
                    ->assertSee(Util::langtext('BULLETIN_BOARD_T_001'))
                    ->assertVisible('#name')
                    ->assertVisible('#title')
                    ->assertVisible('#body')
                    ->value('#name','')
                    ->value('#body','')
                    ->value('#title','')
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_001, [Util::langtext('BULLETIN_BOARD_L_001')]))
                    ->assertSee(Message::getMessage(Message::ERROR_001, [Util::langtext('BULLETIN_BOARD_L_002')]))
                    ->assertSee(Message::getMessage(Message::ERROR_001, [Util::langtext('BULLETIN_BOARD_L_003')]));
        });
    }
    
    /**
     * Check for input field response in Bulletin Board create - name, title and body - out of bounds length
     * @return void
     */
    public function testBulletinRegOutboundResponse()
    {
       $this->browse(function (Browser $browser)
        {
            $inval ='';
            for($g=0; $g<10; $g++)
            {
                $inval = $inval.'asdfghjklqwertyuIPassNotword001';
            }
            
            $browser->visit($this->bulletinBoardCreate)
                    ->assertPathIs($this->bulletinBoardCreate)
                    ->pause(1000)
                    ->assertSee(Util::langtext('BULLETIN_BOARD_T_003').Util::langtext('BULLETIN_BOARD_T_001'))
                    ->assertSee(Util::langtext('BULLETIN_BOARD_T_001'))
                    ->assertVisible('#name')
                    ->assertVisible('#title')
                    ->assertVisible('#body')
                    ->value('#name',$inval)
                    ->value('#body',$inval)
                    ->value('#title',$inval)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_002, [Util::langtext('BULLETIN_BOARD_L_001'),'50']))
                    ->assertSee(Message::getMessage(Message::ERROR_002, [Util::langtext('BULLETIN_BOARD_L_002'),'50']))
                    ->assertSee(Message::getMessage(Message::ERROR_002, [Util::langtext('BULLETIN_BOARD_L_003'),'255']));
        });
    }
    
    /**
     * Create Bulletin Board Entry with Valid data type - no upload
     * @return void
     */
    public function testBulletinRegValidFieldsResponse()
    {
       $this->browse(function (Browser $browser)
        {
            $browser->visit($this->bulletinBoardCreate)
                    ->assertPathIs($this->bulletinBoardCreate)
                    ->pause(1000)
                    ->assertSee(Util::langtext('BULLETIN_BOARD_T_003').Util::langtext('BULLETIN_BOARD_T_001'))
                    ->assertSee(Util::langtext('BULLETIN_BOARD_T_001'))
                    ->assertVisible('#name')
                    ->assertVisible('#title')
                    ->assertVisible('#body')
                    ->assertVisible('#bulletin_board_start_date')
                    ->assertVisible('#bulletin_board_start_time')
                    ->assertVisible('#bulletin_board_start_minute')
                    ->assertVisible('#bulletin_board_end_date')
                    ->assertVisible('#bulletin_board_end_time')
                    ->assertVisible('#bulletin_board_end_minute')
                    ->value('#name', $this->name)
                    ->value('#title', $this->title)
                    ->value('#body', $this->body)
                    ->value('#bulletin_board_start_date',$this->date_now)
                    ->value('#bulletin_board_start_time',$this->hrs)
                    ->value('#bulletin_board_start_minute',$this->mins)
                    ->value('#bulletin_board_end_date',$this->date_next)
                    ->value('#bulletin_board_end_time',$this->hrs)
                    ->value('#bulletin_board_end_minute',$this->mins)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertPathIs($this->bulletinBoardIndex)
                    ->assertSee(Message::getMessage(Message::INFO_001, [Util::langtext('SIDEBAR_LI_006')]));
                    
            PHPUnit::assertStringContainsString($this->bulletinBoardIndex, $browser->driver->getCurrentURL());
        });
    }
    
}
