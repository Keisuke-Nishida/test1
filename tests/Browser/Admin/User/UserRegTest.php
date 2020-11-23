<?php

namespace Tests\Browser;

use PHPUnit\Framework\TestCase;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Lib\Message;
Use Faker\Factory as Faker;

class UserRegTest extends DuskTestCase
{
    private $login_url = "/admin/login";
    private $user_reg_url = "/admin/user/create";

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
                    ->assertPathIs('/admin/user');
        });
    }

    /*
     * Test case: Check response when all input text boxes are empty and button 保存 is pressed
     */

    public function testUserRegEmptyInputTextbox()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->user_reg_url)
                    ->type('name', '')
                    ->type('password', '')
                    ->type('password_confirmation', '')
                    ->type('login_id','')
                    ->type('email','')
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_001, [langtext('USER_L_002')]))
                    ->assertSee(Message::getMessage(Message::ERROR_001, [langtext('USER_L_021')]))
                    ->assertSee(Message::getMessage(Message::ERROR_001, [langtext('USER_L_021')]))
                    ->assertSee(Message::getMessage(Message::ERROR_001, [langtext('USER_L_004')]))
                    ->assertSee(Message::getMessage(Message::ERROR_001, [langtext('USER_L_005')]));
        });
    }

    /*
    * Test case: Check response when ユーザー名 is empty and button 保存 is pressed
    */
    public function testUserEmptyUserName()
    {
        $this->browse(function (Browser $browser)
        {
            $faker = Faker::create();
            $password = $faker->password;
            $browser->visit($this->user_reg_url)
                    ->type('name','')
                    ->type('password', $password)
                    ->type('password_confirmation', $password)
                    ->type('login_id', time())
                    ->type('email', $faker->email)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_001, [langtext('USER_L_002')]));
        });
    }

    /*
     * Test case: Check response when パスワード is empty and button 保存 is pressed
     */
     public function testUserEmptyPassword()
     {
         $this->browse(function (Browser $browser)
         {
            $faker = Faker::create();
            $password = '11111111';
            $browser->visit($this->user_reg_url)
                    ->assertPathIs($this->user_reg_url)
                    ->type('name', $faker->name)
                    ->type('password', '')
                    ->type('password_confirmation', $password)
                    ->type('login_id', time())
                    ->type('email', $faker->email)
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_001, [langtext('USER_L_021')]));
         });
     }

     /**
     * Test case: Check response when パスワード length is less than 8 characters and button 保存 is pressed
     */
     public function testUserInvalidPasswordLength(){
        $this->browse(function (Browser $browser)
        {
           $faker = Faker::create();
           $password = "4444";
           $browser->visit($this->user_reg_url)
                   ->type('name', $faker->name)
                   ->type('password', $password)
                   ->type('password_confirmation', $password)
                   ->type('login_id', time())
                   ->type('email', $faker->email)
                   ->click('button[type="submit"]')
                   ->pause(1000)
                   ->assertSee(Message::getMessage(Message::ERROR_006, [langtext('USER_L_021'), '8']));
        });
     }

     /**
      * Test case: Check response when パスワード（確認） is empty and button 保存 is pressed
      */
      public function testUserEmptyPasswordConfirmation(){
        $this->browse(function (Browser $browser)
        {
           $faker = Faker::create();
           $password = '11111111';
           $browser->visit($this->user_reg_url)
                   ->type('name', $faker->name)
                   ->type('password', $password)
                   ->type('password_confirmation', '')
                   ->type('login_id', time())
                   ->type('email', $faker->email)
                   ->click('button[type="submit"]')
                   ->pause(1000)
                   ->assertSee(Message::getMessage(Message::ERROR_001, [langtext('USER_L_021')]));
        });
     }

     /**
      * Test case: Check response when パスワード and パスワード（確認） doesn't matched
      */
      public function testUserPasswordMistmatched(){
        $this->browse(function (Browser $browser)
        {
           $faker = Faker::create();
           $browser->visit($this->user_reg_url)
                   ->type('name', $faker->name)
                   ->type('password', '11111111')
                   ->type('password_confirmation', '22222222')
                   ->type('login_id', time())
                   ->type('email', $faker->email)
                   ->click('button[type="submit"]')
                   ->pause(1000)
                   ->assertSee(Message::getMessage(Message::ERROR_002, [langtext('USER_L_021'), langtext(('USER_L_027'))]));
        });
     }

     /**
      * Test case: Check response when ログインID is empty and button 保存 is pressed
      */
      public function testUserEmptyLoginId(){
        $this->browse(function (Browser $browser)
        {
           $faker = Faker::create();
           $password = '11111111';
           $browser->visit($this->user_reg_url)
                   ->type('name', $faker->name)
                   ->type('password', $password)
                   ->type('password_confirmation', $password)
                   ->type('login_id', '')
                   ->type('email', $faker->email)
                   ->click('button[type="submit"]')
                   ->pause(1000)
                   ->assertSee(Message::getMessage(Message::ERROR_001, [langtext('USER_L_004')]));
        });
     }

     /**
      * Test case: Check response when ログインID is less than 4 characters
      * Test case: Check response when ログインID is more than 10 characters
      */
      public function testUserInvalidLoginIdLength(){
        $this->browse(function (Browser $browser)
        {
           $faker = Faker::create();
           $password = '11111111';
           $browser->visit($this->user_reg_url)
                   ->type('name', $faker->name)
                   ->type('password', $password)
                   ->type('password_confirmation', $password)
                   ->type('login_id', '444')
                   ->type('email', $faker->email)
                   ->click('button[type="submit"]')
                   ->pause(1000)
                   ->assertSee(Message::getMessage(Message::ERROR_006, [langtext('USER_L_004'), '4']))
                   ->type('name', $faker->name)
                   ->type('password', $password)
                   ->type('password_confirmation', $password)
                   ->type('login_id', '10101010101010')
                   ->type('email', $faker->email)
                   ->click('button[type="submit"]')
                   ->pause(1000)
                   ->assertSee(Message::getMessage(Message::ERROR_002, [langtext('USER_L_004'), '10']));
        });
     }

     /**
      * Test case: Check response when メールアドレス is empty and button 保存 is pressed
      */
      public function testUserEmptyEmailAddress(){
        $this->browse(function (Browser $browser)
        {
           $faker = Faker::create();
           $password = '11111111';
           $browser->visit($this->user_reg_url)
                   ->type('name', $faker->name)
                   ->type('password', $password)
                   ->type('password_confirmation', $password)
                   ->type('login_id', time())
                   ->type('email', '')
                   ->click('button[type="submit"]')
                   ->pause(1000)
                   ->assertSee(Message::getMessage(Message::ERROR_001, [langtext('USER_L_005')]));
        });
     }

     /**
      * Test case: Check response when entering invalid メールアドレス
      */
      public function testUserInvalidEmailAddress(){
        $this->browse(function (Browser $browser)
        {
           $faker = Faker::create();
           $password = '11111111';
           $browser->visit($this->user_reg_url)
                   ->type('name', $faker->name)
                   ->type('password', $password)
                   ->type('password_confirmation', $password)
                   ->type('login_id', time())
                   ->type('email', 'helloworld')
                   ->click('button[type="submit"]')
                   ->pause(1000)
                   ->assertSee(Message::getMessage(Message::ERROR_003, [langtext('USER_L_005')]));
        });
     }

     /**
      * Test case: Check if 得意先ID is disabled and システム管理者フラグ is enabled when the value of ユーザーステータス is 管理者ユーザー
      */
      public function testUserStatusAdmin(){
        $this->browse(function (Browser $browser)
        {
           $faker = Faker::create();
           $password = '11111111';
           $browser->visit($this->user_reg_url)
                   ->type('name', $faker->name)
                   ->type('password', $password)
                   ->type('password_confirmation', $password)
                   ->select('status', '1')
                   ->type('login_id', time())
                   ->type('email', $faker->email)
                   ->pause(1000)
                   ->assertDisabled('customer_id')
                   ->assertEnabled('system_admin_flag');
        });
     }

     /**
      * Test case: Check if 得意先ID enabled and システム管理者フラグ is disabled when the value of ユーザーステータス is 得意先ユーザー
      */
      public function testUserStatusCustomer(){
        $this->browse(function (Browser $browser)
        {
           $faker = Faker::create();
           $password = '11111111';
           $browser->visit($this->user_reg_url)
                   ->type('name', $faker->name)
                   ->type('password', $password)
                   ->type('password_confirmation', $password)
                   ->select('status', '2')
                   ->type('login_id', time())
                   ->type('email', $faker->email)
                   ->pause(1000)
                   ->assertEnabled('customer_id')
                   ->assertDisabled('system_admin_flag');
        });
     }

     /**
      * Test case: Check response when value of ユーザーステータス is 得意先ユーザー and no selected 得意先ID
      */
      public function testUserStatusCustomerAndNoSelectedCustomer(){
        $this->browse(function (Browser $browser)
        {
           $faker = Faker::create();
           $password = '11111111';
           $browser->visit($this->user_reg_url)
                   ->type('name', $faker->name)
                   ->type('password', $password)
                   ->type('password_confirmation', $password)
                   ->select('status', '2')
                   ->select('customer_id', '')
                   ->type('login_id', time())
                   ->type('email', $faker->email)
                   ->click('button[type="submit"]')
                   ->pause(1000)
                   ->assertSee(Message::getMessage(Message::ERROR_001, [langtext('USER_L_008')]));
        });
     }

     /**
      * Test case: Check saved user data in user list (Admin User)
      */
      public function testCreatedAdminUser(){
        $this->browse(function (Browser $browser)
        {
           $faker = Faker::create();
           $password = '11111111';
           $login_id = time();
           $browser->visit($this->user_reg_url)
                   ->type('name', $faker->name)
                   ->type('password', $password)
                   ->type('password_confirmation', $password)
                   ->pause(1000)
                   ->click('#system_admin_flag_checkbox')
                   ->select('status', '1')
                   ->type('login_id', $login_id)
                   ->type('email', $faker->email)
                   ->click('button[type="submit"]')
                   ->pause(1000)
                   ->assertPathIs('/admin/user/index')
                   ->click('#search-toggle-button')
                   ->pause(2000)
                   ->type('#search-login-id', $login_id)
                   ->press(langtext('USER_B_004'))
                   ->pause(2000)
                   ->assertSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(4)', $login_id)
                   ->click('#user-detailed-search-reset')
                   ->click('#search-toggle-button');
        });
     }

     /**
      * Test case: Check saved user data in user list (Customer User)
      */
      public function testCreatedCustomerUser(){
        $this->browse(function (Browser $browser)
        {
           $faker = Faker::create();
           $password = '11111111';
           $login_id = time();
           $browser->visit($this->user_reg_url)
                   ->type('name', $faker->name)
                   ->type('password', $password)
                   ->type('password_confirmation', $password)
                   ->select('status', '2')
                   ->select('customer_id','6')
                   ->type('login_id', $login_id)
                   ->type('email', $faker->email)
                   ->click('button[type="submit"]')
                   ->pause(1000)
                   ->assertPathIs('/admin/user/index')
                   ->click('#search-toggle-button')
                   ->pause(2000)
                   ->type('#search-login-id', $login_id)
                   ->press(langtext('USER_B_004'))
                   ->pause(2000)
                   ->assertSeeIn('#user-table > tbody > tr:nth-child(1) > td:nth-child(4)', $login_id)
                   ->click('#user-detailed-search-reset')
                   ->click('#search-toggle-button');
        });
     }

     /**
      * Test case: Check redirection URL when キャンセル button is pressed
      */
      public function testCanceldRegistration(){
        $this->browse(function (Browser $browser)
        {
           $browser->visit($this->user_reg_url)
                   ->click('#user-form-cancel')
                   ->pause(1000)
                   ->assertSee(Message::getMessage(Message::INFO_005))
                   ->click('#confirm_button');
            $this->assertStringContainsString('/admin/user/index', $browser->driver->getCurrentURL());
        });
     }

}

