<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Lib\Message;

class LoginTest extends DuskTestCase
{

    private $login_url = "/admin/login";

    /*
        Test case 1: Check results on entering invalid login_id / invalid password
     */
    public function testInvalidLoginIdAndPassoword()
    {
        $this->browse(function (Browser $browser){
            $browser->visit($this->login_url)
                    ->assertSee(langtext('LOGIN_T_001'))
                    ->type('login_id', 'invalidusername')
                    ->type('password', 'invalidpassword')
                    ->click('button[type="submit"]')
                    ->assertSee(Message::ERROR_007);
        });
    }

    /*
        Test case 2: Check response when a login id is empty and submit button is pressed
     */
    public function testEmptyLoginId()
    {
        $this->browse(function (Browser $browser){
            $browser->visit($this->login_url)
                    ->assertSee(langtext('LOGIN_T_001'))
                    ->type('login_id', '')
                    ->type('password', env('TEST_PASSWORD'))
                    ->click('button[type="submit"]')
                    ->assertSee(Message::getMessage(Message::ERROR_001, [langtext('LOGIN_P_001')]));
        });
    }

    /*
        Test case 3: Check response when a password is empty and submit button is pressed
     */
    public function testEmptyPassword()
    {
        $this->browse(function (Browser $browser){
            $browser->visit($this->login_url)
                    ->assertSee(langtext('LOGIN_T_001'))
                    ->type('login_id', env('TEST_LOGIN_ID'))
                    ->type('password', '')
                    ->click('button[type="submit"]')
                    ->assertSee(Message::getMessage(Message::ERROR_001, [langtext('LOGIN_P_002')]));
        });
    }

    /*
        Test case 4: Check results on entering invalid Login Id
     */
    public function testInvalidLoginIdAndValidPassword()
    {
        $this->browse(function (Browser $browser){
            $browser->visit($this->login_url)
                    ->assertSee(langtext('LOGIN_T_001'))
                    ->type('login_id', 'invalidlogin')
                    ->type('password', env('TEST_PASSWORD'))
                    ->click('button[type="submit"]')
                    ->assertSee(Message::ERROR_007);
        });
    }

    /*
        Test case 5: Check results on entering invalid password
     */
    public function testInvalidPasswordAndValidLoginId()
    {
        $this->browse(function (Browser $browser){
            $browser->visit($this->login_url)
                    ->assertSee(langtext('LOGIN_T_001'))
                    ->type('login_id', env('TEST_LOGIN_ID'))
                    ->type('password', 'invalidpassword')
                    ->click('button[type="submit"]')
                    ->assertSee(Message::ERROR_007);
        });
    }

    /**
     *  Test case 6: Check Landing page on entering Valid Login Id and Password
     */
    public function testValidLoginIdAndPassword()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->login_url)
                    ->assertSee(langtext('LOGIN_T_001'))
                    ->type('login_id', env('TEST_LOGIN_ID'))
                    ->type('password', env('TEST_PASSWORD'))
                    ->click('button[type="submit"]')
                    ->assertPathIs('/admin/index');
        });
    }
}
