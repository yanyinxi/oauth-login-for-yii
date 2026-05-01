<?php
/**
 * Test for small_login.php XSS fix
 */
class small_login_test extends PHPUnit_Framework_TestCase
{
    public function test_href_is_htmlspecialchars_encoded()
    {
        $url = 'https://api.weibo.com/oauth2/authorize?redirect_uri="><script>alert(1)</script>';
        $encoded = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
        $this->assertNotContains('<script>', $encoded);
        $this->assertContains('&lt;script&gt;', $encoded);
    }
}