<?php

namespace Tests\Browser;

use App\Models\User;
use App\Lib\Message;
use App\Lib\Util;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\Assert as PHPUnit;


class UserEditTest extends DuskTestCase
{
    use WithFaker;
    
    protected $name, $customer_id, $login_id, $users_row;
    protected $password, $confirm_password, $last_login_time;
    protected $systemAdminFlag, $status, $email, $role_id;
    protected $userIndexPath, $userPath, $userEditPath, $sessionLogIn, $dashboardIndex;
    
    /**
     * Constructor - initiate data
     */
    public function setUp(): void
    {
        parent::setUp();
        
        $this->users_row = 25; //default start - row per page        
        $this->userIndexPath = '/admin/user/index';
        $this->userPath = '/admin/user';
        $this->userEditPath = '/admin/user/edit';
        $this->sessionLogIn = '/admin/login';
        $this->dashboardIndex = '/admin/index';
        
        $this->name = strval($this->faker->firstName).' '.strval($this->faker->lastName);
        $this->email = $this->faker->email;
        $this->password = 'password1'; 
        $this->role_id = '1'; //default first avail item from drop down
        
        // new login id
        $loginName = $this->faker->userName;
        $this->login_id = (strlen($loginName) > 10) ? substr($loginName,0,9) : $loginName;
    }
    
    // set status as Admin user
    private function setAdminStatusFlag()
    {
        $this->status = '1'; //1:管理者ユーザー 
        $this->systemAdminFlag = '1'; // set  user status - 1:管理者ユーザー 
    }
    
    private function setAdminStatusOnly()
    {
        $this->status = '1'; //1:管理者ユーザー 
        $this->systemAdminFlag = '0'; //0: NOT 管理者ユーザー 
    }
    
    // set status as customer user only
    private function setCustomerStatusOnly()
    {
        $this->status = '2'; //2:得意先ユーザー'
        $this->systemAdminFlag = '0';//0: NOT 管理者ユーザー
        $this->customer_id = strval(rand(1,10)); // range based on customer seeder        
    }
    
    // return status for user
    private function getAdminStatus()
    {
        return $this->status;
    }
    
    // return Admin Flag for user
    private function getAdminFlag()
    {
        return $this->systemAdminFlag;
    }
    
    // return customer_id
    private function getCustomerID()
    {
        return $this->customer_id;
    }
    
    
    
    /**
     * Start session and go to `/user/` to access user edit
     * @return void
     */
    public function testStartUserEditSession()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->visit($this->sessionLogIn)
                    ->assertSee(Util::langtext('LOGIN_T_001'))
                    ->type('login_id', env('TEST_LOGIN_ID'))
                    ->type('password', env('TEST_PASSWORD'))
                    ->click('button[type="submit"]')
                    ->assertPathIs($this->dashboardIndex)
                    ->pause(2000) //give time to load elements
                    ->assertVisible('div.app-body > div.sidebar > nav.sidebar-nav > ul > li:nth-child(2) > a')
                    ->click('div.app-body > div.sidebar > nav.sidebar-nav > ul > li:nth-child(2) > a')
                    ->assertPathIs($this->userPath);
        });
    }
    
    /**
     * Direct to the path /user/edit with given user sample
     * @return void
     */
    public function testVerifyUserEditRedirect()
    {
        $this->browse(function (Browser $browser)
        {            
            $browser->assertPathIs($this->userPath)
                    ->assertSee(Util::langtext('SIDEBAR_LI_002'))
                    ->assertVisible('#user-table > tbody')
                    // use first row visible to redirect for user edit
                    ->assertVisible('#user-table > tbody > tr:nth-child(1) > td:nth-child(3) > a')                    
                    ->click('#user-table > tbody > tr:nth-child(1) > td:nth-child(3) > a');
                    
            PHPUnit::assertStringContainsString($this->userEditPath, $browser->driver->getCurrentURL());
        });
    }
    
    /**
     * verify elements of initial loaded and visible for screen /user/edit/[user_id]
     * will only start when testVerifyYserEditRedirect is successful
     * @depends testVerifyUserEditRedirect
     * @return void
     */
    public function testUserEditLoadedScreen()
    {        
        $this->browse(function (Browser $browser)
        {
            $browser->assertPathIsNot($this->userPath)
                    ->assertPathIsNot($this->userIndexPath)
                    ->pause(2000)
                    ->assertSee(Util::langtext('USER_T_002'))
                    //name
                    ->assertVisible('#name')
                    //password
                    ->assertVisible('#password')
                    //confirm password
                    ->assertVisible('#confirm-password')
                    //admin system flag
                    ->assertPresent('#system-admin-flag')
                    //status
                    ->assertVisible('#status')
                    // customer id
                    ->assertVisible('#customer-id')
                    //log in id
                    ->assertVisible('#login-id')
                    //email
                    ->assertVisible('#email')
                    //role_id - default value to 1 (Sample)
                    ->assertSelected('role_id',$this->role_id)
                    //last login time
                    ->assertVisible('#last-login-time');
        });
    }
    
    /*
     * assert that values are not NULL and empty input fields for /user/edit/[user_id]
     * @depends testUserEditLoadedScreen
     */
    public function testUserEditLoadedInputs()
    {
        $this->browse(function (Browser $browser)
        {
            $browser->assertPathIsNot($this->userPath)
                    ->assertPathIsNot($this->userIndexPath)
                    ->pause(2000)
                    ->assertSee(Util::langtext('USER_T_002'))
                    //name
                    ->assertInputValueIsNot('#name',NULL)
                    ->assertInputValueIsNot('#name',"")
                    //password
                    ->assertInputValue('#password',NULL)
                    //confirm password
                    ->assertInputValue('#confirm-password',NULL)
                    //admin system flag
                    ->assertInputValue('#system-admin-flag','1')
                    //status
                    ->assertInputValueIsNot('#status',NULL)
                    // customer id
                    ->assertNotSelected('#customer-id',NULL)
                    //log in id
                    ->assertInputValueIsNot('#login-id',NULL)
                    //email
                    ->assertInputValueIsNot('#email',NULL)
                    ->assertInputValueIsNot('#email',"")
                    //role_id - default value to 1 (Sample)
                    ->assertNotSelected('role_id',"")
                    ->assertSelected('role_id',$this->role_id)
                    //last login time
                    ->assertInputValueIsNot('#last-login-time','2020');
        });
    }
    
    /*
     * Look for user who is user - status 1 and update all fields
     * 
     * @return data
     */
    public function testAdminUserEditAllFillable()
    {
        $this->browse(function (Browser $browser)
        {            
            // go to /user
            $browser->visit($this->userPath)
                    ->assertSee(Util::langtext('SIDEBAR_LI_002'))
                    ->pause(3000);
                    
            // read a user with user - admin rights
            $rowRead = 1;
            for($i=1; $i <= $this->users_row; $i++)
            {
                $browser->assertVisible('#user-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(3) > a')
                        ->assertVisible('#user-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(7)')
                        ->assertVisible('#user-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(9)');
                        
                $RowStatus = $browser->text('#user-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(7)');
                $RowAdmnFlg = $browser->text('#user-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(9)');
                             
                if($RowStatus == '1' && $RowAdmnFlg == '1')
                {
                    $this->setAdminStatusFlag();
                    $rowRead = $i;
                    $i = $this->users_row + 1;
                }
            }
            
            $browser->click('#user-table > tbody > tr:nth-child('.strval($rowRead).') > td:nth-child(3) > a')
                    ->pause(1000)
                    ->assertPathIsNot($this->userPath)
                    ->assertPathIsNot($this->userIndexPath);
                    
            PHPUnit::assertStringContainsString($this->userEditPath, $browser->driver->getCurrentURL());                       
        });
        
        return $data = [$this->getAdminStatus(), $this->getAdminFlag()];
    }
    
    /**
     * test - Edit view fillables for a Admin User
     * @depends testAdminUserEditAllFillable
     */
    public function testAdminUserFullUpdateResponse($data)
    {
        $this->status = $data[0];
        $this->setAdminStatusFlag = $data[1];
        
        $this->browse(function (Browser $browser)
        {
            $browser->assertPathIsNot($this->userPath)
                    ->assertPathIsNot($this->userIndexPath)
                    ->pause(2000)
                    ->assertSee(Util::langtext('USER_T_002'));                    
                    
            if($this->status == '1')
            {
                //assert if its Admin User
                $browser->assertChecked('#system-admin-flag')           
                        ->assertSelected('#status',$this->status)
                        ->assertSelected('role_id',$this->role_id)
                        // assert input fields
                        ->value('#name', $this->name)
                        ->value('#password', $this->password)
                        ->value('#confirm-password', $this->password)
                        ->value('#login-id',$this->login_id)
                        ->value('#email',$this->email)
                        // submit button
                        ->click('button[type="submit"]')
                        ->pause(2000)
                        ->assertPathIs($this->userIndexPath);
            }
        });
    }
    
    /**
     * test - Edit view fillables for a Admin User - invalid larger length characters
     * Asserted status as AdminUser from dependent test function
     * @depends testAdminUserFullUpdateResponse
     */
    public function testAdminUserUpdateFailuresGreatLength()
    {
        $this->browse(function (Browser $browser)
        {              
            //make constructor data lengthty
            for($i=0; $i <= 10; $i++)
            {
                $this->name = $this->name.$this->name;
                $this->login_id = $this->login_id.$this->login_id;
            }
            
            $eml='';
            for($g=0; $g<15; $g++)
            {
                $eml = $eml.'asdfghjklqwertyuI';
                $this->password = $this->password.'asdfghjklqwertyuIPassword001';
            }
            $this->email = $eml;
            
            // read the table and select AdminUser
            // read a user with user - admin rights
            $browser->visit($this->userPath)
                    ->pause(3000);
            
            $rowRead = 1;
            for($i=1; $i <= $this->users_row; $i++)
            {
                $browser->assertVisible('#user-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(3) > a')
                        ->assertVisible('#user-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(7)')
                        ->assertVisible('#user-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(9)');
                        
                $RowStatus = $browser->text('#user-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(7)');
                $RowAdmnFlg = $browser->text('#user-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(9)');
                             
                if($RowStatus == '1' && $RowAdmnFlg == '1')
                {
                    $this->setAdminStatusFlag();
                    $rowRead = $i;
                    $i = $this->users_row + 1;
                }
            }
            
            $browser->click('#user-table > tbody > tr:nth-child('.strval($rowRead).') > td:nth-child(3) > a')
                    ->pause(1000); //give time to load
            
            PHPUnit::assertStringContainsString($this->userEditPath, $browser->driver->getCurrentURL());
                    
            // test data and produce error messages
            $browser->assertPathIsNot($this->userPath)
                    ->assertPathIsNot($this->userIndexPath)
                    ->assertSee(Util::langtext('USER_T_002'))                      
                    ->assertChecked('#system-admin-flag')           
                    ->assertSelected('#status',$this->getAdminStatus())
                    ->assertSelected('role_id',$this->role_id)
                    ->value('#name', $this->name)
                    ->value('#password', $this->password)
                    ->value('#confirm-password', $this->password)
                    ->value('#login-id',$this->login_id)
                    ->value('#email','Z'.$this->email)
                    // submit button
                    ->click('button[type="submit"]')
                    ->pause(2000)
                    ->assertSee(Message::getMessage(Message::ERROR_002, [Util::langtext('USER_L_002'),'50']))
                    ->assertSee(Message::getMessage(Message::ERROR_002, [Util::langtext('USER_L_004'),'10']))
                    ->assertSee(Message::getMessage(Message::ERROR_002, [Util::langtext('USER_L_021'),'255']))
                    ->assertSee(Message::getMessage(Message::ERROR_003, [Util::langtext('USER_L_005')]));
            
            //reload screen and verify changes placed
            $browser->script('location.reload()');            
            $browser->pause(2000)
                    ->assertSee(Util::langtext('USER_T_002'));
        });
    }
    
    /**
     * test - Edit view fillables for a Admin User - invalid shorter length characters
     * @depends testAdminUserUpdateFailuresGreatLength
     */    
    public function testAdminUserUpdateFailuresLessLength()
    {
        $this->browse(function (Browser $browser)
        {
            $this->setAdminStatusFlag();
            
            // test data and produce error messages
            $browser->assertPathIsNot($this->userPath)
                    ->assertPathIsNot($this->userIndexPath)
                    ->assertSee(Util::langtext('USER_T_002'))                      
                    ->assertChecked('#system-admin-flag')           
                    ->assertSelected('#status',$this->getAdminStatus())
                    ->assertSelected('role_id',$this->role_id)
                    ->value('#name', 'Sam')
                    ->value('#password', 'pwd1')
                    ->value('#confirm-password', 'pwd1')
                    ->value('#login-id','Sam')
                    ->value('#email','tyml')
                    // submit button
                    ->click('button[type="submit"]')
                    ->pause(2000)
                    ->assertSee(Message::getMessage(Message::ERROR_006, [Util::langtext('USER_L_002'),'4']))
                    ->assertSee(Message::getMessage(Message::ERROR_006, [Util::langtext('USER_L_004'),'4']))
                    ->assertSee(Message::getMessage(Message::ERROR_006, [Util::langtext('USER_L_021'),'8']))
                    ->assertSee(Message::getMessage(Message::ERROR_003, [Util::langtext('USER_L_005')]));
            
            //reload screen and verify changes placed
            $browser->script('location.reload()');            
            $browser->pause(2000)
                    ->assertSee(Util::langtext('USER_T_002'));
        });
    }
    
    /**
     * test - user edit view password mismatch
     */
    public function testEditUserPasswordMismatch()
    {
        $this->browse(function (Browser $browser)
        {            
            // mismatched password vs confirm password
            $browser->assertPathIsNot($this->userPath)
                    ->assertPathIsNot($this->userIndexPath)
                    ->assertSee(Util::langtext('USER_T_002'))                      
                    ->value('#name', $this->name)
                    ->value('#password', $this->password)
                    ->value('#confirm-password', $this->password.'t0')
                    // submit button
                    ->click('button[type="submit"]')
                    ->pause(1000)
                    ->assertSee(Message::getMessage(Message::ERROR_002, [Util::langtext('USER_L_021'),Util::langtext('USER_L_027')]));
            
            //reload screen and verify changes placed
            $browser->script('location.reload()');            
            $browser->pause(2000)
                    ->assertSee(Util::langtext('USER_T_002'));
        });
    }    
    
    /**
     * test cancel button from /user/edit/[user_id]
     */
    public function testEditUserCancelResponse()
    {
        $this->browse(function (Browser $browser)
        {            
            // mismatched password vs confirm password
            $browser->assertPathIsNot($this->userPath)
                    ->assertPathIsNot($this->userIndexPath)
                    ->assertSee(Util::langtext('USER_T_002'))                      
                    ->value('#name', $this->name)
                    ->value('#password', $this->password)
                    ->value('#confirm-password', $this->password)
                    // cancel button
                    ->clickLink(Util::langtext('CUSTOMER_B_008'))
                    ->pause(2000)
                    ->assertSee(Message::INFO_005);
        });
    }
    
    /*
     * Look for user who is user - status 2 and update all fields
     * 
     * @return data_customer
     */
    public function testCustomerUserEditAllFillable()
    {
        $this->browse(function (Browser $browser)
        { 
            // go to /user
            $browser->visit($this->userPath)
                    ->assertSee(Util::langtext('SIDEBAR_LI_002'))
                    ->pause(3000);
                    
            // read a user with user - admin rights
            $rowRead = 1;
            for($i=1; $i <= $this->users_row; $i++)
            {
                $browser->assertVisible('#user-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(3) > a')
                        ->assertVisible('#user-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(7)')
                        ->assertVisible('#user-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(8)')
                        ->assertVisible('#user-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(9)');
                        
                $RowStatus = $browser->text('#user-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(7)');
                $RowAdmnFlg = $browser->text('#user-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(9)');
                             
                if($RowStatus == '2' && $RowAdmnFlg == '0')
                {
                    $this->setCustomerStatusOnly();
                    $rowRead = $i;
                    $i = $this->users_row + 1;
                }
            }
            
            $browser->click('#user-table > tbody > tr:nth-child('.strval($rowRead).') > td:nth-child(3) > a')
                    ->pause(1000)
                    ->assertPathIsNot($this->userPath)
                    ->assertPathIsNot($this->userIndexPath);
                    
            PHPUnit::assertStringContainsString($this->userEditPath, $browser->driver->getCurrentURL());                       
        });
        
        return $data_customer = [$this->getAdminStatus(), $this->getAdminFlag(), $this->getCustomerID()];
    }
    
    /**
     * test - Edit view fillables for a Customer User
     * @depends testCustomerUserEditAllFillable
     */
    public function testCustomerUserFullUpdateResponse($data_customer)
    {
        $this->status = $data_customer[0];
        $this->setAdminStatusFlag = $data_customer[1];
        $this->customer_id = $data_customer[2];
        
        $this->browse(function (Browser $browser)
        {            
            $browser->assertPathIsNot($this->userPath)
                    ->assertPathIsNot($this->userIndexPath)
                    ->pause(2000)
                    ->assertSee(Util::langtext('USER_T_002'));                    
                    
            if($this->status == '2')
            {
                //assert if its Customer User
                $browser->assertNotChecked('#system-admin-flag')           
                        ->assertSelected('#status',$this->status)
                        ->assertSelected('role_id',$this->role_id)
                        ->assertInputValueIsNot('#login-id',NULL)
                        // assert input fields
                        ->value('#name', $this->name)
                        ->value('#password', $this->password)
                        ->value('#confirm-password', $this->password)
                        ->value('#login-id',$this->login_id)
                        ->value('#customer-id',$this->customer_id)
                        ->value('#email',$this->email)
                        // submit button
                        ->click('button[type="submit"]')
                        ->pause(2000)
                        ->assertPathIs($this->userIndexPath);
            }
        });
    }
    
    /**
     * test - Edit view fillables for a Customer User - invalid larger length characters
     * Asserted status as Customer User from dependent test function
     */
    public function testCustomerUserUpdateFailuresGreatLength()
    {
        $this->browse(function (Browser $browser)
        {              
            //make constructor data lengthty
            for($i=0; $i <= 10; $i++)
            {
                $this->name = $this->name.$this->name;
                $this->login_id = $this->login_id.$this->login_id;
            }
            
            $eml='';
            for($g=0; $g<15; $g++)
            {
                $eml = $eml.'asdfghjklqwertyuI';
                $this->password = $this->password.'asdfghjklqwertyuIpassword001';
            }
            $this->email = $eml;
            
            // read the table and select customer user
            // read a user with user - customer rights
            $browser->visit($this->userPath)
                    ->pause(3000);
            
            $rowRead = 1;
            for($i=1; $i <= $this->users_row; $i++)
            {
                $browser->assertVisible('#user-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(3) > a')
                        ->assertVisible('#user-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(7)')
                        ->assertVisible('#user-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(8)')                        
                        ->assertVisible('#user-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(9)');
                        
                $RowStatus = $browser->text('#user-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(7)');
                $RowAdmnFlg = $browser->text('#user-table > tbody > tr:nth-child('.strval($i).') > td:nth-child(9)');
                             
                if($RowStatus == '2' && $RowAdmnFlg == '0')
                {
                    $this->setCustomerStatusOnly();
                    $rowRead = $i;
                    $i = $this->users_row + 1;
                }
            }
            
            $browser->click('#user-table > tbody > tr:nth-child('.strval($rowRead).') > td:nth-child(3) > a')
                    ->pause(1000); //give time to load
                    
            PHPUnit::assertStringContainsString($this->userEditPath, $browser->driver->getCurrentURL());            
                    
            // test data and produce error messages
            $browser->assertPathIsNot($this->userPath)
                    ->assertPathIsNot($this->userIndexPath)
                    ->assertSee(Util::langtext('USER_T_002'))                      
                    ->assertNotChecked('#system-admin-flag')           
                    ->assertSelected('#status',$this->getAdminStatus())
                    ->assertSelected('role_id', $this->role_id)
                    ->assertNotSelected('#customer-id', NULL)
                    ->value('#name', $this->name)
                    ->value('#password', $this->password)
                    ->value('#confirm-password', $this->password)
                    ->value('#login-id',$this->login_id)
                    ->value('#customer-id', $this->getCustomerID())
                    ->value('#email','Z'.$this->email)
                    // submit button
                    ->click('button[type="submit"]')
                    ->pause(2000)
                    ->assertSee(Message::getMessage(Message::ERROR_002, [Util::langtext('USER_L_002'),'50']))
                    ->assertSee(Message::getMessage(Message::ERROR_002, [Util::langtext('USER_L_004'),'10']))
                    ->assertSee(Message::getMessage(Message::ERROR_002, [Util::langtext('USER_L_021'),'255']))
                    ->assertSee(Message::getMessage(Message::ERROR_003, [Util::langtext('USER_L_005')]));
            
            //reload screen and verify changes placed
            $browser->script('location.reload()');            
            $browser->pause(2000)
                    ->assertSee(Util::langtext('USER_T_002'));
        });
    }
    
    /**
     * test - Edit view fillables for a Customer User - invalid smaller length characters
     * @depends testCustomerUserUpdateFailuresGreatLength
     */    
    public function testCustomerUserUpdateFailuresLessLength()
    {
        $this->browse(function (Browser $browser)
        {
            $this->setCustomerStatusOnly();
            
            // test data and produce error messages
            $browser->assertPathIsNot($this->userPath)
                    ->assertPathIsNot($this->userIndexPath)
                    ->assertSee(Util::langtext('USER_T_002'))                      
                    ->assertNotChecked('#system-admin-flag')           
                    ->assertSelected('#status',$this->getAdminStatus())
                    ->assertSelected('role_id', $this->role_id)
                    ->assertNotSelected('#customer-id', NULL)
                    ->value('#name', 'Sam')
                    ->value('#password', 'pwd1')
                    ->value('#confirm-password', 'pwd1')
                    ->value('#customer-id','') //empty
                    ->value('#login-id','Sam')
                    ->value('#email','tyml')
                    // submit button
                    ->click('button[type="submit"]')
                    ->pause(2000)
                    ->assertSee(Message::getMessage(Message::ERROR_006, [Util::langtext('USER_L_002'),'4']))
                    ->assertSee(Message::getMessage(Message::ERROR_006, [Util::langtext('USER_L_004'),'4']))
                    ->assertSee(Message::getMessage(Message::ERROR_001, [Util::langtext('USER_L_008')]))					
                    ->assertSee(Message::getMessage(Message::ERROR_006, [Util::langtext('USER_L_021'),'8']))
                    ->assertSee(Message::getMessage(Message::ERROR_003, [Util::langtext('USER_L_005')]));
            
            //reload screen and verify changes placed
            $browser->script('location.reload()');            
            $browser->pause(2000)
                    ->assertSee(Util::langtext('USER_T_002'));
        });
    }  
}
