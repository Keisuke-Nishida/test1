<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    /**
     * Test user max 10 character
     */
    public function testMaxUserLengthInputError()
    {
        $this->browse(function (Browser $browser){
            $browser->visit('/login')
                    ->assertSee('Web情報閲覧サービス管理')
                    ->value('#login-id', '12345678910')
                    ->value('#password', env('TEST_PASSWORD'))
                    ->click('button[type="submit"]')
                    ->assertSee(langtext('LOGIN_E_001'));
        });
    }

    /**
     * Test password max 15 character
     */
    public function testMaxPasswordLengthInputError()
    {
        $this->browse(function (Browser $browser){
            $browser->visit('/login')
                    ->assertSee('Web情報閲覧サービス管理')
                    ->value('#login-id', env('TEST_LOGIN_ID'))
                    ->value('#password', '123456789123456789')
                    ->click('button[type="submit"]')
                    ->assertSee(langtext('LOGIN_E_002'));
        });
    }

    /**
     * Test invalid user and valid password
     */
    public function testInvalidUserAndValidPassword()
    {
        
        $this->browse(function (Browser $browser){
            $browser->visit('/login')
                    ->assertSee('Web情報閲覧サービス管理')
                    ->value('#login-id', '123456789')
                    ->value('#password', env('TEST_PASSWORD'))
                    ->click('button[type="submit"]')
                    ->assertSee(langtext('LOGIN_E_003'));
        });
    }

    /**
     * Test valid user and invalid password
     */
    public function testValidUserAndInvalidPassword()
    {
        $this->browse(function (Browser $browser){
            $browser->visit('/login')
                    ->assertSee('Web情報閲覧サービス管理')
                    ->value('#login-id', env('TEST_LOGIN_ID'))
                    ->value('#password', '12321321')
                    ->click('button[type="submit"]')
                    ->assertSee(langtext('LOGIN_E_003'));
        });
    }

    /**
     * Test invalid user and password
     */
    public function testInvalidUserAndPassword()
    {
        $this->browse(function (Browser $browser){
            $browser->visit('/login')
                    ->assertSee('Web情報閲覧サービス管理')
                    ->value('#login-id', '123456789')
                    ->value('#password', '12321321')
                    ->click('button[type="submit"]')
                    ->assertSee(langtext('LOGIN_E_003'));
        });
    }

    /**
     * Test user and password login no value error
     */
    public function testLoginUserAndPasswordNoInputError()
    {
        $this->browse(function (Browser $browser){
            $browser->visit('/login')
                    ->assertSee('Web情報閲覧サービス管理')
                    ->value('#login-id', '')
                    ->value('#password', '')
                    ->click('button[type="submit"]')
                    ->assertSee(langtext('LOGIN_E_001'))
                    ->assertSee(langtext('LOGIN_E_002'));
        });
    }

    /**
     * User Login Test
     *
     * @return void
     */
    public function testValidUserAndValidPassword()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->assertSee('Web情報閲覧サービス管理')
                    ->value('#login-id', env('TEST_LOGIN_ID'))
                    ->value('#password', env('TEST_PASSWORD'))
                    ->click('button[type="submit"]')
                    ->assertPathIs('/dashboard'); //check if logged in and see dashboard index
        });
    }

    /**
     * Check if the login user go to /login url 
     * it will be redirect to dashboard
     * @return void
     */
    public function testLoginRedirectDashboard()
    {
        $this->browse(function (Browser $browser){
            $browser->visit('/login')
                    ->assertPathIs('/dashboard');
        });
    }

}
