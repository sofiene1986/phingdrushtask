<?php

namespace Phing\Drush\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Class TaskTest.
 *
 * @package Phing\Drush\Tests
 */
class TaskTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    \Phing::setOutputStream(new \OutputStream(fopen('php://output', 'w')));
    \Phing::setErrorStream(new \OutputStream(fopen('php://output', 'w')));

    $dir = __DIR__ . "/xml/*";
    foreach(glob($dir) as $buildfile) {
      \Phing::startup();
      $m = new \Phing();
      $args = array('-f', realpath($buildfile));
      ob_start();
      $m->execute($args);
      $m->runBuild();
      $content = ob_get_contents();
      ob_end_clean();
      \Phing::shutdown();

      $this->assertContains('Executing command: ', $content);
    }
  }

}
