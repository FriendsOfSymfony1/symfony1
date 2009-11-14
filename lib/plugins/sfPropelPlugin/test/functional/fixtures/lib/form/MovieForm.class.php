<?php

/**
 * Movie form.
 *
 * @package    form
 * @subpackage movie
 * @version    SVN: $Id$
 */
class MovieForm extends BaseMovieForm
{
  public function configure()
  {
    $this->embedI18n(array('en', 'fr'));
  }
}
