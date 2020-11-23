<?php

namespace Tests\Browser;

use PHPUnit\Framework\TestCase;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Lib\Message;

class UserListTest extends DuskTestCase
{
    private $login_url = "/admin/login";
    private $user_url = "/admin/user";
    private $logout_url = "/logout";
    /**
     * Verify start session for /user
     * @return void
     */
    public function testUserScreenSession()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->login_url)
                    ->assertSee(langtext('LOGIN_T_001'))
                    ->type('login_id', env('TEST_LOGIN_ID'))
                    ->type('password', env('TEST_PASSWORD'))
                    ->click('button[type="submit"]')
                    ->assertPathIs('/admin/index') //check if logged in and see dashboard index
                    //move to /user
                    ->pause(3000)
                    ->assertVisible('div.app-body > div.sidebar > nav.sidebar-nav > ul > li:nth-child(2) > a')
                    ->click('div.app-body > div.sidebar > nav.sidebar-nav > ul > li:nth-child(2) > a')
                    ->assertPathIs($this->user_url);
        });
    }

    /*
     * Test case 1: check the initial user data
     */
    public function testInitialUserListData()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->assertPathIs($this->user_url)
                    ->pause(6000) //give time to load elements first
                    ->assertSeeIn('main.main > ol > li',langtext('SIDEBAR_LI_002'))
                    // check search fields as empty
                    ->value('#search-name',"")
                    ->value('#search-login-id',"")
                    ->value('#search-status',"")
                    ->value('#search-email', "")
                    // check user table visible with a loaded data row
                    ->assertVisible('#user-table > tbody > tr:nth-child(1) > td:nth-child(3) > a')
                    ->assertVisible('#user-table > tbody > tr:nth-child(1) > td:nth-child(10) > a')
                    ->assertVisible('#user-table > tbody > tr:nth-child(1) > td:nth-child(2)')
                    ->assertVisible('#user-table > tbody > tr:nth-child(1) > td:nth-child(4)')
                    ->assertDontSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(5)', NULL);
        });
    }

    /**
     *  NOTE: not implemented
     * Check pop up message when bulk delete is pressed and no selected data to be deleted
     * note: not implemented
     */
    // public function testPopUpMessageWhenBulkDeleteButtonPressedAndNoDataSelected()
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->visit($this->user_url)
    //                 ->pause(1000)
    //                 ->assertSee(langtext('SIDEBAR_LI_002'))
    //                 ->click('#user-multiple-delete-button')
    //                 ->assertDialogOpened(langtext('USER_M_001'))
    //                 ->acceptDialog();
    //     });
    // }

    /**
     * Test case : Check pop up message upon deleting user
     */
    public function testRowDeleteConfirmDialog()
    {
        $this->browse(function (Browser $browser) {
            $user = User::first();
            $browser->visit($this->user_url)
                    ->assertSee(langtext('SIDEBAR_LI_002'))
                    ->pause(1000)
                    ->click('a[data-id="'.$user->id.'"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::INFO_004, [langtext('SIDEBAR_LI_002')]))
                    ->press('Close')
                    ->pause(1000);
                    // ->click('#confirm_button') // this will delete user from database
                    // ->pause(1000)
                    // ->assertSee(Message::getMessage(Message::INFO_003, [langtext('SIDEBAR_LI_002')]))
                    // ->pause(1000);  //pause closing the dialog
        });
    }

    /*
     * Check user's edit page
     * @return void
     */
    public function testUserEditPage()
    {
        $this->browse( function (Browser $browser)
        {
            // check edit link
            $browser->assertPathIs($this->user_url)
                    ->pause(3000)
                    ->assertVisible('#user-table > tbody > tr:nth-child(1) > td:nth-child(3) > a')
                    ->click('#user-table > tbody > tr:nth-child(1) > td:nth-child(3) > a');
           // verify returned id + redirection URL
            $this->assertStringContainsString($this->user_url . '/edit', $browser->driver->getCurrentURL());
        });
    }

    /*
     * Click registration button and go to registration page
     * @return void
     */
    public function testUserRegistrationPage()
    {
        $this->browse( function(Browser $browser)
        {
            $browser->visit($this->user_url)
                    ->assertPathIs($this->user_url)
                    ->pause(3000)
                    ->assertVisible('div.data-table-wrapper > div:nth-child(2) > div:nth-child(2) > a')
                    ->click('div.data-table-wrapper > div:nth-child(2) > div:nth-child(2) > a')
                    ->assertPathIs($this->user_url . '/create');
        });
    }

    /*
     * check and uncheck the first 5 rows and then click check all
     */
    public function testCheckboxOfFirst5Rows()
    {
        $this->browse( function (Browser $browser)
        {
            $browser->visit($this->user_url)
                    ->pause(3000) //wait for elements to load
                    ->assertVisible('#user-table > tbody');

            //check / uncheck small sample data of 5 rows loaded
            for($i=1; $i <= 5; $i++)
            {
                $browser->assertVisible('#user-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(1)')
                        ->click('#user-table > tbody > tr:nth-child('.strval($i).') > td.select-checkbox') //checked
                        ->pause(1000)
                        ->assertNotSelected('#user-table > tbody > tr:nth-child('.strval($i).') > td.select-checkbox', NULL)
                        ->click('#user-table > tbody > tr:nth-child('.strval($i).') > td.select-checkbox') //unchecked
                        ->pause(1000)
                        ->assertNotChecked('#user-table > tbody > tr:nth-child('.strval($i).') > td.select-checkbox');
            }
        });
    }

    public function testCheckboxForSelectAll()
    {
        $this->browse( function (Browser $browser)
        {
            $browser->visit($this->user_url)
                    ->pause(3000) //wait for elements to load
                    ->assertVisible('#user-table > tbody');
            // check select-all-check box checked
            $browser->check('#user-select-all')
                    ->pause(1000)
                    ->assertChecked('#user-select-all')
                    // verify all unchecked
                    ->uncheck('#user-select-all')
                    // ->pause(1000)
                    ->assertNotChecked('#user-select-all');
        });
    }

    /**
     * Test case: Check search results when searching valid name
     */
    public function testUserSearchValidName()
    {
        $user = User::take(3)->whereNull('deleted_at')->get();
        $first_user = $user[0];
        $this->browse(function (Browser $browser) use ($first_user)
        {
            $browser->visit($this->user_url)
                    ->assertSee(langtext('SIDEBAR_LI_002'))
                    ->click('#search-toggle-button')
                    ->pause(2000)
                    ->type('#search-name', $first_user->name)
                    ->press(langtext('USER_B_004'))
                    ->pause(1000)
                    ->assertSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(2)', $first_user->name)
                    ->click('#user-detailed-search-reset')
                    ->click('#search-toggle-button');
        });
        return $user;
    }

    /**
     * Test case: Check results when searching valid email address
     * @depends testUserSearchValidName
     */
    public function testUserSearchValidEmail($user)
    {
        $second_user = $user[1];
        $this->browse(function (Browser $browser) use ($second_user)
        {
            $browser->visit($this->user_url)
                    ->assertSee(langtext('SIDEBAR_LI_002'))
                    ->click('#search-toggle-button')
                    ->pause(2000)
                    ->type('#search-email', $second_user->email)
                    ->press(langtext('USER_B_004'))
                    ->pause(1000)
                    ->assertSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(5)', $second_user->email)
                    ->click('#user-detailed-search-reset')
                    ->click('#search-toggle-button');
        });
    }

    /**
     * Test case: Check the results when searching valid login id
     * @depends testUserSearchValidName
     */
    public function testUserSearchLoginId($user)
    {
        $third_user = $user[2];
        $this->browse(function (Browser $browser) use ($third_user)
        {
            $browser->visit($this->user_url)
                    ->assertSee(langtext('SIDEBAR_LI_002'))
                    ->click('#search-toggle-button')
                    ->pause(2000)
                    ->type('#search-login-id', $third_user->login_id)
                    ->press(langtext('USER_B_004'))
                    ->pause(2000)
                    ->assertSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(4)', $third_user->login_id)
                    ->click('#user-detailed-search-reset')
                    ->click('#search-toggle-button');
        });
    }

    /**
     * Test case: check results when searching user status
     */
    public function testUserSearchStatus()
    {
        $user = User::where('status', 1)->whereNull('deleted_at')->first();
        $this->browse(function (Browser $browser) use ($user)
        {
            $browser->visit($this->user_url)
                    ->assertSee(langtext('SIDEBAR_LI_002'))
                    ->click('#search-toggle-button')
                    ->pause(2000)
                    ->select('#search-status', 1)
                    ->press(langtext('USER_B_004'))
                    ->pause(1000)
                    ->assertSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(2)', $user->name)
                    ->assertSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(4)', $user->login_id)
                    ->assertSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(5)', $user->email)
                    ->click('#user-detailed-search-reset')
                    ->click('#search-toggle-button');
        });
    }

    /**
     * Test case: check results when searching invalid name
     */
    public function testUserSearchInvalidName()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->user_url)
                    ->assertSee(langtext('SIDEBAR_LI_002'))
                    ->click('#search-toggle-button')
                    ->pause(2000)
                    ->type('#search-name', '@!qwerty:{">')
                    ->press(langtext('USER_B_004'))
                    ->pause(1000)
                    ->assertSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(1)', langtext('DATA_TABLE_EMPTY_TEXT'))
                    ->click('#user-detailed-search-reset')
                    ->click('#search-toggle-button');
        });
    }

    /**
     * Test caes: check results when searching invalid email
     */
    public function testUserSearchInvalidEmail()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->user_url)
                    ->assertSee(langtext('SIDEBAR_LI_002'))
                    ->click('#search-toggle-button')
                    ->pause(2000)
                    ->type('#search-email', 'asdasd')
                    ->press(langtext('USER_B_004'))
                    ->pause(1000)
                    ->assertSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(1)', langtext('DATA_TABLE_EMPTY_TEXT'))
                    ->click('#user-detailed-search-reset')
                    ->click('#search-toggle-button');
        });
    }

    /**
     * Test case: check results when searching invalid user login id
     */
    public function testUserSearchInvalidLoginId()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->user_url)
                    ->assertSee(langtext('SIDEBAR_LI_002'))
                    ->click('#search-toggle-button')
                    ->pause(2000)
                    ->type('#search-login-id', 'qwewads@@xac!~!')
                    ->press(langtext('USER_B_004'))
                    ->pause(1000)
                    ->assertSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(1)', langtext('DATA_TABLE_EMPTY_TEXT'))
                    ->click('#user-detailed-search-reset')
                    ->click('#search-toggle-button');
        });
    }

    // /**
    // * *************************************************************************************************************************
    // * *************************************************************************************************************************
    //  * Test case: check results when searching invalid user search status function
    //  */
    // public function testInvalidSearchStatus()
    // {
    //     $user = User::where('status', 1)->whereNull('deleted_at')->first();
    //     $this->browse(function (Browser $browser) use ($user)
    //     {
    //         $browser->visit($this->user_url)
    //                 ->assertSee(langtext('SIDEBAR_LI_002'))
    //                 ->click('#search-toggle-button')
    //                 ->pause(2000)
    //                 ->select('#search-status', 2)
    //                 ->type('#search-email', $user->email)
    //                 ->press(langtext('USER_B_004'))
    //                 ->pause(1000)
    //                 ->assertSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(1)', langtext('DATA_TABLE_EMPTY_TEXT'))
    //                 ->click('#user-detailed-search-reset')
    //                 ->click('#search-toggle-button');
    //     });
    // }

    /**
     * Test changing page
     */
    public function testPageChange()
    {
        $this->browse(function (Browser $browser)
        {
            $user = User::skip(25)->whereNull('deleted_at')->first();
            $user2 = User::skip(50)->whereNull('deleted_at')->first();
            $browser->visit($this->user_url)
                    ->pause(1000)
                    ->assertVisible('div.dataTables_paginate > ul.pagination > li:nth-child(2) > a')
                    // Click page 2
                    ->click('div.dataTables_paginate > ul.pagination > li:nth-child(3) > a')
                    ->assertSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(2)', $user->name)
                    ->assertSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(4)', $user->login_id)
                    ->assertSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(5)', $user->email)
                    // Click next page
                    ->clickLink(langtext('DATA_TABLE_PAGINATE_NEXT'))
                    ->assertSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(2)', $user2->name)
                    ->assertSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(4)', $user2->login_id)
                    ->assertSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(5)', $user2->email)
                    // Click prev page
                    ->clickLink(langtext('DATA_TABLE_PAGINATE_PREVIOUS'))
                    ->assertSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(2)', $user->name)
                    ->assertSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(4)', $user->login_id)
                    ->assertSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(5)', $user->email);
        });
    }

    /**
     * Test case: test pagination and click checkbox
     * rows by 25, 50 and 100
     * @return void
     */
    public function testUserPaginationAndSelectRowsPerPage()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->user_url)
                    ->assertPathIs($this->user_url);

            $row_per_page = ['25', '50', '100'];

            for($i=0; $i < 3; $i++)
            {
                // check rows per page by 25, 50 and 100
                $browser->pause(1000)
                        ->assertVisible('#user-table_length > label > select')
                        ->value('#user-table_length > label > select', $row_per_page[$i])
                        ->pause(3000)
                        ->assertSelected('#user-table_length > label > select', $row_per_page[$i])
                        //check rows after rows per page changed
                        ->assertVisible('#user-table > tbody > tr:nth-child(1) > td:nth-child(1)')
                        ->check('#user-table > tbody > tr:nth-child(1) > td:nth-child(1)')
                        ->assertNotSelected('#user-table > tbody > tr:nth-child(1) > td:nth-child(1)', NULL)
                        ->clickLink(langtext('DATA_TABLE_PAGINATE_NEXT')) //page change
                        ->pause(1000)
                        ->assertVisible('#user-table > tbody > tr:nth-child(1) > td:nth-child(1)')
                        ->check('#user-table > tbody > tr:nth-child(1) > td:nth-child(1)')
                        ->assertNotSelected('#user-table > tbody > tr:nth-child(1) > td:nth-child(1)', NULL)
                        ->clickLink(langtext('DATA_TABLE_PAGINATE_NEXT')) //page change
                        ->pause(1000)
                        ->assertVisible('#user-table > tbody > tr:nth-child(1) > td:nth-child(1)')
                        ->check('#user-table > tbody > tr:nth-child(1) > td:nth-child(1)')
                        ->assertNotSelected('#user-table > tbody > tr:nth-child(1) > td:nth-child(1)', NULL);
            }

            // apply 25 again, as default and check table loaded
            $browser->pause(1000)
                    ->assertVisible('#user-table_length > label > select')
                    ->value('#user-table_length > label > select', $row_per_page[0])
                    ->pause(3000)
                    ->assertSelected('#user-table_length > label > select', $row_per_page[0])
                    ->assertVisible('#user-table > tbody');
        });
    }
}
