class ProjectServiceContainer extends sfServiceContainer
{
  protected $shared = array();

  public function __construct()
  {
    parent::__construct($this->getDefaultParameters());
  }

  protected function getDefaultParameters()
  {
    return array(
      'foo' => 'bar',
      'bar' => 'foo is %foo bar',
      'values' => array(
        0 => true,
        1 => false,
        2 => NULL,
        3 => 0,
        4 => 1000.3,
        5 => 'true',
        6 => 'false',
        7 => 'null',
      ),
    );
  }
}
