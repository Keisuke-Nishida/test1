<?php

namespace Tests\Browser;

use App\Lib\Message;
use App\Lib\Util;
use App\Models\BulletinBoardData;
use Illuminate\Foundation\Testing\Assert as PHPUnit;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;


class BulletinBoardListTest extends DuskTestCase
{
    protected $sessionLogIn, $dashboardIndex, $bulletinBoardPath, $bulletinBoardIndexPath;
    
    // constructor and initialize data
    public function setUp(): void
    {
        parent::setUp();
        
        $this->sessionLogIn = '/admin/login';
        $this->dashboardIndex = '/admin/index';
        $this->bulletinBoardPath = '/admin/bulletin_board';
        $this->bulletinBoardIndexPath = '/admin/bulletin_board/index';
    }
    
    
    /**
     * start session for Bulletin Board test functions
     * @return void
     */
    public function testStartBulletinListSession()
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
     * Test read intial loaded data from visiting /admin/bulletin_board
     * check table loaded and search bar empty
     * @return void
     */
    public function testBulletinBoardInitLoadPage()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->assertPathIs($this->bulletinBoardPath)
                    // check user table visible with a loaded data row
                    ->assertSeeIn('main.main > ol > li',Util::langtext('BULLETIN_BOARD_L_005'))
                    ->click('#search-toggle-button')
                    // check search fields as empty
                    ->assertValue('#search-name','')
                    ->assertValue('#search-title','')
                    ->assertValue('#search-body','')
                    // check user table visible with a loaded data row
                    ->assertVisible('#bulletin_board-table > tbody')
                    ->assertVisible('#bulletin_board-table > tbody > tr:nth-child(1) > td:nth-child(2)')
                    ->assertVisible('#bulletin_board-table > tbody > tr:nth-child(1) > td:nth-child(4)')
                    ->assertVisible('#bulletin_board-table > tbody > tr:nth-child(1) > td:nth-child(5)');
        }); 
    }  
    
    /**
     * Test no selected row bulk delete and popup dialog
     * @return void
     */
    public function testNoSelectedBulkDeleteDialogBulletin()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->bulletinBoardPath)
                    ->pause(1000)
                    ->assertSee(Util::langtext('BULLETIN_BOARD_L_005'))
                    ->click('#bulletin_board-multiple-delete-button')
                    ->assertDialogOpened('No data selected')
                    ->acceptDialog();
        });
    }
    
    /**
     * Test multiple selected row and bulk delete dialog response
     * @return void
     */
    public function testSelectedBulkDeletedDialogBulletin()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->bulletinBoardPath)
                    ->pause(1000)
                    ->assertVisible('#bulletin_board-table > tbody');
                    
            // Check
            for ($i = 1; $i <= 5 ; $i++)
            {
                $browser->assertVisible('#bulletin_board-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(1)')
                        ->click('#bulletin_board-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(1)')
                        ->assertNotSelected('#bulletin_board-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(1)', NULL);
            }
            
            $browser->click('#bulletin_board-multiple-delete-button')
                    ->pause(1000)
                    ->assertVisible('#message_body', Message::getMessage(Message::INFO_004, [Util::langtext('SIDEBAR_LI_006')]))
                    ->press('Close')
                    ->pause(1000);
                    
            // uncheck
            for ($i = 1; $i <= 5 ; $i++)
            {
                $browser->assertVisible('#bulletin_board-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(1)')
                        ->click('#bulletin_board-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(1)')
                        ->assertNotChecked('#bulletin_board-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(1)');
            }
        });
    }
    
    /**
     * test pagination for Bulletin Board List by page selection and rows by 25, 50 and 100
     * @return void
     */
    public function testBulletinBoardPaginationRowsPerPage()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->bulletinBoardPath)
                    ->assertPathIs($this->bulletinBoardPath);

            $row_per_page = ['25', '50', '100'];

            for($i=0; $i < 3; $i++)
            {
                // check rows per page by 25, 50 and 100
                $browser->pause(1000)
                        ->assertVisible('#bulletin_board-table_length > label > select')
                        ->value('#bulletin_board-table_length > label > select', $row_per_page[$i])
                        ->assertSelected('#bulletin_board-table_length > label > select', $row_per_page[$i])
                        //check rows after rows per page changed
                        ->assertVisible('#bulletin_board-table > tbody > tr:nth-child(1) > td:nth-child(1)')
                        ->check('#bulletin_board-table > tbody > tr:nth-child(1) > td:nth-child(1)')
                        ->assertNotSelected('#bulletin_board-table > tbody > tr:nth-child(1) > td:nth-child(1)', NULL)
                        ->clickLink(Util::langtext('DATA_TABLE_PAGINATE_NEXT')) //page change
                        ->pause(1000)
                        ->assertVisible('#bulletin_board-table > tbody > tr:nth-child(1) > td:nth-child(1)')
                        ->check('#bulletin_board-table > tbody > tr:nth-child(1) > td:nth-child(1)')
                        ->assertNotSelected('#bulletin_board-table > tbody > tr:nth-child(1) > td:nth-child(1)', NULL)
                        ->clickLink(Util::langtext('DATA_TABLE_PAGINATE_NEXT')) //page change
                        ->pause(1000)
                        ->assertVisible('#bulletin_board-table > tbody > tr:nth-child(1) > td:nth-child(1)')
                        ->check('#bulletin_board-table > tbody > tr:nth-child(1) > td:nth-child(1)')
                        ->assertNotSelected('#bulletin_board-table > tbody > tr:nth-child(1) > td:nth-child(1)', NULL);
            }

            // apply 25 again, as default and check table loaded
            $browser->pause(1000)
                    ->assertVisible('#bulletin_board-table_length > label > select')
                    ->value('#bulletin_board-table_length > label > select', $row_per_page[0])
                    ->pause(1000)
                    ->assertSelected('#bulletin_board-table_length > label > select', $row_per_page[0])
                    ->assertVisible('#bulletin_board-table > tbody');
        });
    }
}
