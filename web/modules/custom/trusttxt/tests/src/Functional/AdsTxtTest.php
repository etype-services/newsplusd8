<?php

namespace Drupal\Tests\trusttxt\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests functionality of configured trust.txt files.
 *
 * @group trust.txt
 */
class trustTxtTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected $profile = 'standard';

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['trusttxt', 'node'];

  /**
   * Checks that an administrator can view the configuration page.
   */
  public function testtrustTxtAdminAccess() {
    // Create user.
    $this->admin_user = $this->drupalCreateUser(['administer trust.txt']);
    $this->drupalLogin($this->admin_user);
    $this->drupalGet('admin/config/system/trusttxt');

    $this->assertFieldById('edit-trusttxt-content', NULL, 'The textarea for configuring trust.txt is shown.');
  }

  /**
   * Checks that a non-administrative user cannot use the configuration page.
   */
  public function testtrustTxtUserNoAccess() {
    // Create user.
    $this->normal_user = $this->drupalCreateUser(['access content']);
    $this->drupalLogin($this->normal_user);
    $this->drupalGet('admin/config/system/trusttxt');

    $this->assertResponse(403);
    $this->assertNoFieldById('edit-trusttxt-content', NULL, 'The textarea for configuring trust.txt is not shown for users without appropriate permissions.');
  }

  /**
   * Test that the trust.txt path delivers content with an appropriate header.
   */
  public function testtrustTxtPath() {
    $this->drupalGet('trust.txt');
    $this->assertResponse(200, 'No local trust.txt file was detected, and an anonymous user is delivered content at the /trust.txt path.');
    $this->assertText('greenadexchange.com, 12345, DIRECT, AEC242');
    $this->assertText('blueadexchange.com, 4536, DIRECT');
    $this->assertText('silverssp.com, 9675, RESELLER');
    $this->assertHeader('Content-Type', 'text/plain; charset=UTF-8', 'The trust.txt file was served with header Content-Type: "text/plain; charset=UTF-8"');
  }

  /**
   * Test that the trust.txt path delivers content with an appropriate header.
   */
  public function testApptrustTxtPath() {
    $this->drupalGet('app-trust.txt');
    $this->assertResponse(200, 'No local trust.txt file was detected, and an anonymous user is delivered content at the /trust.txt path.');
    $this->assertText('onetwothree.com, 12345, DIRECT, AEC242');
    $this->assertText('fourfivesix.com, 4536, DIRECT');
    $this->assertText('97whatever.com, 9675, RESELLER');
    $this->assertHeader('Content-Type', 'text/plain; charset=UTF-8', 'The trust.txt file was served with header Content-Type: "text/plain; charset=UTF-8"');
  }

  /**
   * Checks that a configured trust.txt file is delivered as configured.
   */
  public function testtrustTxtConfiguretrustTxt() {
    // Create an admin user, log in and access settings form.
    $this->admin_user = $this->drupalCreateUser(['administer trust.txt']);
    $this->drupalLogin($this->admin_user);
    $this->drupalGet('admin/config/system/trusttxt');

    $test_string = "# SimpleTest {$this->randomMachineName()}";
    $this->drupalPostForm(NULL, ['trusttxt_content' => $test_string], t('Save configuration'));

    $this->drupalLogout();
    $this->drupalGet('trust.txt');
    $this->assertResponse(200, 'No local trust.txt file was detected, and an anonymous user is delivered content at the /trust.txt path.');
    $this->assertHeader('Content-Type', 'text/plain; charset=UTF-8', 'The trust.txt file was served with header Content-Type: "text/plain; charset=UTF-8"');
    $content = $this->getRawContent();
    $this->assertTrue($content == $test_string, sprintf('Test string [%s] is displayed in the configured trust.txt file [%s].', $test_string, $content));
  }

}
