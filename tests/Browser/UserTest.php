<?php

namespace Tests\Browser;

use Illuminate\Testing\Assert as PHPUnit;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserTest extends DuskTestCase
{
    private $login_url = "/login";
    private $user_url = "/user";
    private $logout_url = "/logout";
    /**
     * Verify start session for /user
     * @return void
     */
    public function testStartUserSession()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->login_url)
                    ->assertSee(langtext('LOGIN_T_001'))
                    ->value('#login-id', env('TEST_LOGIN_ID'))
                    ->value('#password', env('TEST_PASSWORD'))
                    ->click('button[type="submit"]')
                    ->assertPathIs('/dashboard') //check if logged in and see dashboard index
                    //move to /user
                    ->pause(3000)
                    ->assertVisible('div.app-body > div.sidebar > nav.sidebar-nav > ul > li:nth-child(2) > a')
                    ->click('div.app-body > div.sidebar > nav.sidebar-nav > ul > li:nth-child(2) > a')
                    ->assertPathIs('/user');
        });
    }

    /*
     * Test read intial loaded data from visiting /user page
     * check table loaded and search bar empty
     * @return void
     */
    public function testUserInitialPageLoadded()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->assertPathIs('/user')
                    ->pause(3000) //give time to load elements first
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
     * Test no selected row bulk delete
     * popup dialog
     */
    public function testNoSelectedBulkDeleteDialog()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->user_url)
                    ->pause(1000)
                    ->assertSee(langtext('SIDEBAR_LI_002'))
                    ->click('#user-multiple-delete-button')
                    ->assertDialogOpened(langtext('USER_M_001'))
                    ->acceptDialog();
        });
    }

    /**
     * Test delete row confirm pop up dialog
     */
    public function testRowDeleteConfirmDialog()
    {
        $this->browse(function (Browser $browser) {
            $user = User::first();
            $browser->visit($this->user_url)
                    ->assertSee(langtext('SIDEBAR_LI_002'))
                    ->pause(1000)
                    ->click("#delete-id-$user->id")
                    ->pause(1000)
                    ->assertSee(str_replace('[user_id]', $user->id, langtext('USER_DLG_001')))
                    ->click('#delete-no')
                    ->pause(1000);  //pause closing the dialog
        });
    }

    /*
     * validate edit redirection for selected user and proceed to /user/edit
     * @return void
     */
    public function testRedirectSelectUserEdit()
    {
        $this->browse( function (Browser $browser)
        {
            // check edit link
            $browser->assertPathIs('/user')
                    ->pause(3000)
                    ->assertVisible('#user-table > tbody > tr:nth-child(1) > td:nth-child(3) > a')
                    ->click('#user-table > tbody > tr:nth-child(1) > td:nth-child(3) > a');

           // verify returned id + redirection URL
            PHPUnit::assertStringContainsString('/user/edit', $browser->driver->getCurrentURL());
        });
    }

    /*
     * validate user create redirection and go to /user/create
     * @return void
     */
    public function testRedirectSelectUserCreate()
    {
        $this->browse( function(Browser $browser)
        {
            $browser->visit($this->user_url)
                    ->assertPathIs('/user')
                    ->pause(3000)
                    ->assertVisible('div.data-table-wrapper > div:nth-child(2) > div:nth-child(2) > a')
                    ->click('div.data-table-wrapper > div:nth-child(2) > div:nth-child(2) > a')
                    ->assertPathIs('/user/create');
        });
    }

    /*
     * Validate user table checkbox for select all / unselect all
     * and validate check/ uncheck box for individual item
     * @return void
     */
    public function testBatchRowsChecked()
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
                        ->pause(3000)
                        ->assertNotSelected('#user-table > tbody > tr:nth-child('.strval($i).') > td.select-checkbox', NULL)
                        ->click('#user-table > tbody > tr:nth-child('.strval($i).') > td.select-checkbox') //unchecked
                        ->pause(3000)
                        ->assertNotChecked('#user-table > tbody > tr:nth-child('.strval($i).') > td.select-checkbox');
            }

            // check select-all-check box checked
            $browser->check('#user-select-all')
                    ->pause(5000)
                    ->assertChecked('#user-select-all')
                    // verify all unchecked
                    ->uncheck('#user-select-all')
                    ->pause(5000)
                    ->assertNotChecked('#user-select-all');
        });
    }

    /**
     * Test user search name function
     */
    public function testUserSearchName()
    {
        $user = User::take(3)->get();
        $first_user = $user[0];
        $this->browse(function (Browser $browser) use ($first_user)
        {
            $browser->visit($this->user_url)
                    ->assertSee(langtext('SIDEBAR_LI_002'))
                    ->click('#search-toggle-button')
                    ->pause(2000)
                    ->type('#search-name', $first_user->name)
                    ->press(langtext('USER_B_004'))
                    ->assertSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(2)', $first_user->name)
                    ->click('#user-detailed-search-reset')
                    ->click('#search-toggle-button');
        });
        return $user;
    }

    /**
     * Test user search email function
     * @depends testUserSearchName
     */
    public function testUserSearchEmail($user)
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
     * Test user search login id function
     * @depends testUserSearchName
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
                    ->pause(1000)
                    ->assertSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(4)', $third_user->login_id)
                    ->click('#user-detailed-search-reset')
                    ->click('#search-toggle-button');

        });
    }

    /**
     * Test user search status function
     * @depends testUserSearchName
     */
    public function testUserSearchStatus($user)
    {
        $user = User::where('status', 1)->first();
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
     * Test invalid search name
     */
    public function testInvalidSearchName()
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
     * Test invalid search email
     */
    public function testInvalidSearchEmail()
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
     * Test invalid user search login id function
     */
    public function testInvalidSearchLoginId()
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

    /**
     * Test invalid user search status function
     * @depends testUserSearchName
     */
    public function testInvalidSearchStatus($user)
    {
        $user = User::where('status', 1)->first();
        $this->browse(function (Browser $browser) use ($user)
        {
            $browser->visit($this->user_url)
                    ->assertSee(langtext('SIDEBAR_LI_002'))
                    ->click('#search-toggle-button')
                    ->pause(2000)
                    ->select('#search-status', 2)
                    ->type('#search-email', $user->email)
                    ->press(langtext('USER_B_004'))
                    ->pause(1000)
                    ->assertSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(1)', langtext('DATA_TABLE_EMPTY_TEXT'))
                    ->click('#user-detailed-search-reset')
                    ->click('#search-toggle-button');

        });
    }

    /**
     * Test changing page
     */
    public function testPageChange()
    {
        $this->browse(function (Browser $browser)
        {
            $user = User::skip(25)->first();
            $user2 = User::skip(50)->first();
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
     * test checkbox row per page
     * rows by 25, 50 and 100
     * @return void
     */
    public function testSelectRowsPerPage()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->user_url)
                    ->assertPathIs('/user');

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
                        ->assertVisible('#user-table > tbody > tr:nth-child(5) > td:nth-child(1)')
                        ->check('#user-table > tbody > tr:nth-child(5) > td:nth-child(1)')
                        ->assertNotSelected('#user-table > tbody > tr:nth-child(1) > td:nth-child(1)', NULL)
                        ->clickLink(langtext('DATA_TABLE_PAGINATE_NEXT')) //page change
                        ->pause(1000)
                        ->assertVisible('#user-table > tbody > tr:nth-child(3) > td:nth-child(1)')
                        ->check('#user-table > tbody > tr:nth-child(3) > td:nth-child(1)')
                        ->assertNotSelected('#user-table > tbody > tr:nth-child(3) > td:nth-child(1)', NULL);
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

    /*
     * End session for /user and logout
     * @return void
     */
    public function testEndUserSession()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->assertPathIs('/user') //verify leaving from /user
                    ->assertTitle(env('APP_NAME').' - '.langtext('SIDEBAR_LI_002'))
                    ->visit($this->logout_url)
                    ->assertPathIs('/login') //verify logged out
                    ->assertTitle(env('APP_NAME').' - '.langtext('LOGIN_T'))
                    ->assertSee(langtext('LOGIN_T_001'));
        });
    }
}
