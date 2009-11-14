<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) Jonathan H. Wage <jonwage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once(dirname(__FILE__).'/sfDoctrineBaseTask.class.php');

/**
 * Creates database for current model.
 *
 * @package    symfony
 * @subpackage doctrine
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id$
 */
class sfDoctrineDqlTask extends sfDoctrineBaseTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('dql_query', sfCommandArgument::REQUIRED, 'The DQL query to execute', null),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', true),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('show-sql', null, sfCommandOption::PARAMETER_NONE, 'Show the sql that would be executed'),
      new sfCommandOption('table', null, sfCommandOption::PARAMETER_NONE, 'Return results in table format'),
    ));

    $this->namespace = 'doctrine';
    $this->name = 'dql';
    $this->briefDescription = 'Execute a DQL query and view the results';

    $this->detailedDescription = <<<EOF
The [doctrine:data-dql|INFO] task executes a DQL query and display the formatted results:

  [./symfony doctrine:dql "FROM User u"|INFO]

You can show the SQL that would be executed by using the [--dir|COMMENT] option:

  [./symfony doctrine:dql --show-sql "FROM User u"|INFO]
EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);

    $dql = $arguments['dql_query'];

    $q = Doctrine_Query::create()
      ->parseDqlQuery($dql);

    $this->logSection('doctrine', 'executing dql query');

    echo sprintf('DQL: %s', $dql) . "\n";

    if ($options['show-sql'])
    {
      echo sprintf('SQL: %s', $q->getSqlQuery()) . "\n";
    }

    $count = $q->count();

    if ($count)
    {
      if (!$options['table'])
      {
        $results = $q->fetchArray();

        echo sprintf('found %s results', $count) . "\n";
        $yaml = sfYaml::dump($results, 4);
        $lines = explode("\n", $yaml);
        foreach ($lines as $line)
        {
          echo $line."\n";
        }
      }
      else
      {
        $results = $q->execute(array(), Doctrine_Core::HYDRATE_SCALAR);

        $headers  = array();
        // calculate lengths
        foreach($results as $result)
        {
          foreach( $result as $field => $value )
          {
            if (!isset($headers[$field]))
            {
              $headers[$field] = 0;
            }
            $headers[$field] = max($headers[$field], strlen($value));
          }
        }

        // print headers
        $hdr  = "|";
        $div  = "+";
        foreach($headers as $field => &$length)
        {
          if ($length < strlen($field))
          {
            $length = strlen($field);
          }
          $hdr .= " ".str_pad($field, $length)." |";
          $div .= str_pad("", $length + 2, "-")."+";
        }
        echo $div."\n";
        echo $hdr."\n";
        echo $div."\n";

        // print results
        foreach($results as $result)
        {
          echo '|';
          foreach( $result as $field => $value )
          {
            echo ' '.str_pad($value,$headers[$field]).' |';
          }
          echo "\n";
        }
        echo $div . "\n";
        echo sprintf('(%s results)', $count) . "\n";
        echo "\n";
      }
    }
    else
    {
      $this->logSection('doctrine', 'no results found');
    }
  }
}
