<?php

namespace Phing\Drush\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Class DrushTest.
 *
 * @package Phing\Drush\Tests
 */
class DrushTest extends TestCase {

  /**
   * Drush test.
   *
   * @dataProvider commandsProvider
   */
  public function testSmoke($command, $result) {
    \Phing::setOutputStream(new \OutputStream(fopen('php://output', 'w')));
    \Phing::setErrorStream(new \OutputStream(fopen('php://output', 'w')));

    $file = __DIR__ . '/xml/test.xml';
    $template = __DIR__ . '/xml/template.xml';
    // Read the entire string.
    $xml = file_get_contents($template);
    // Replace something in the file string - this is a VERY simple example.
    $str = str_replace('<drush/>', $command, $xml);
    $str = str_replace('<drush', '<drush pretend="yes"', $str);
    // Write the entire string.
    file_put_contents($file, $str);

    \Phing::startup();
    $m = new \Phing();
    $args = array('-f', realpath($file));
    ob_start();
    $m->execute($args);
    $m->runBuild();
    $content = ob_get_contents();
    ob_end_clean();
    \Phing::shutdown();

    $this->assertContains("Executing command: " . $result . " 2>&1\n", $content);
    unlink($file);
  }

  /**
   * Data provider.
   *
   * @return array
   *    Test arguments.
   */
  public function commandsProvider() {
    return array(
      array(
        'command' => '<drush/>',
        'result' => 'drush',
      ),
      array(
        'command' => '<drush druplicon="yes"/>',
        'result' => 'drush --druplicon',
      ),
      array(
        'command' => '<drush command="status" simulate="yes" assume="yes"/>',
        'result' => 'drush --simulate --yes status',
      ),
      array(
        'command' => '<drush assume="yes" command="make"><option name="simulate"/></drush>',
        'result' => 'drush --simulate --yes make',
      ),
      array(
        'command' => '<drush bin="/whos/your/brogy/drush" command="status" assume="yes"/>',
        'result' => '/whos/your/brogy/drush --yes status',
      ),
      array(
        'command' => '<drush command="make" assume="yes"
               verbose="no"
               color="no"
               simulate="yes"
               root="/somewhere/over/the/rainbow">
            <param>/way/up/high.make.yml</param>
            <param>/And/the/dreams/that/you/dreamed/of</param>
        </drush>',
        'result' => 'drush --nocolor --root="/somewhere/over/the/rainbow" --simulate --yes make /way/up/high.make.yml /And/the/dreams/that/you/dreamed/of',
      ),
      array(
        'command' => '<drush command="status" assume="yes"
               simulate="yes"
               root="/somewhere/over/the/rainbow">
            <param quote="yes">/way/up/high.make.yml</param>
            <param escape="yes">/And/the/dreams/that/you/dreamed/of;I\'m Broggy and I know it.</param>
        </drush>',
        'result' => 'drush --root="/somewhere/over/the/rainbow" --simulate --yes status "/way/up/high.make.yml" /And/the/dreams/that/you/dreamed/of\;I\\\'m Broggy and I know it.',
      ),
      array(
        'command' => '<property name="drush.bin" value="/everyday/im/shuffling/drush"/><drush command="status" assume="yes"
               simulate="yes"
               root="/somewhere/over/the/rainbow">
        </drush>',
        'result' => '/everyday/im/shuffling/drush --root="/somewhere/over/the/rainbow" --simulate --yes status',
      ),
    );
  }

}
