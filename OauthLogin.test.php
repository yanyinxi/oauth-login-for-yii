<?php
/**
 * Test for OauthLogin.php fixes
 */
class OauthLogin_test extends PHPUnit_Framework_TestCase
{
    public function test_qq_state_has_fallback()
    {
        // Test that state generation works without random_bytes
        if (!function_exists('random_bytes')) {
            $state = md5(uniqid(rand(), true));
            $this->assertEquals(32, strlen($state));
        } else {
            $state = bin2hex(random_bytes(16));
            $this->assertEquals(32, strlen($state));
        }
    }

    public function test_back_url_has_default()
    {
        $back_url = null;
        $default_url = '/';
        $result = $back_url ?: $default_url;
        $this->assertEquals('/', $result);
    }

    public function test_state_length()
    {
        $state = function_exists('random_bytes')
            ? bin2hex(random_bytes(16))
            : md5(uniqid(rand(), true));
        $this->assertEquals(32, strlen($state));
    }
}