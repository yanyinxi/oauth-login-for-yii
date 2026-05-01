<?php
/**
 * Test for medium_login.php XSS fix
 */
class medium_login_test extends PHPUnit_Framework_TestCase
{
    public function test_href_is_htmlspecialchars_encoded()
    {
        $url = 'https://graph.qq.com/oauth2.0/authorize?code=x&"><img src=x onerror=alert(1)';
        $encoded = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
        $this->assertNotContains('<img', $encoded);
        $this->assertContains('&lt;img', $encoded);
    }
}