<?php

/**
 * sfMessageSource_XLIFF class with support to create large XML files
 * Better memory usage than original class.
 *
 * If you want to create large XML file set up this Message Source in your factories.yml
 *
 *  i18n:
 *    class: sfI18N
 *    param:
 *    source: XLIFF_Large
 *
 *
 * @author     Filip Chmarzynski <filip.chmarzynski[at]gmail[dot]com>
 * @version    $Id$
 * @package    symfony
 * @subpackage i18n
 */

class sfMessageSource_XLIFF_Large extends sfMessageSource_XLIFF
{

  /**
   * Saves the list of untranslated blocks to the translation source.
   * If the translation was not found, you should add those
   * strings to the translation source via the <b>append()</b> method.
   *
   * @param string $catalogue the catalogue to add to
   * @return boolean true if saved successfuly, false otherwise.
   */
  public function save($catalogue = 'messages')
  {
    $messages = $this->untranslated;
    if (count($messages) <= 0)
    {
      return false;
    }

    $variants = $this->getVariants($catalogue);
    if ($variants)
    {
      list($variant, $filename) = $variants;
    }
    else
    {
      list($variant, $filename) = $this->createMessageTemplate($catalogue);
    }

    if (is_writable($filename) == false)
    {
      throw new sfException(sprintf("Unable to save to file %s, file must be writable.", $filename));
    }

    $reader = new XMLReader();
    $reader->open( $filename );

    $identifiers = $transUnit =  array();

    while ( $reader->read() ) {
        if ( $reader->nodeType == XMLReader::ELEMENT && $reader->name == 'trans-unit' ) {
            $identifiers[] = $reader->getAttribute( "id" );
            $transUnit[]   = $reader->readOuterXml();
        }
    }
    $nextId = max($identifiers);

    foreach ( $messages as $message ) {

        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->setIndent( true );
        $xmlWriter->startElement( "trans-unit" );
        $xmlWriter->writeAttribute( "id", ++ $nextId );

        $xmlWriter->writeElement( "source", $message );
        $xmlWriter->writeElement( "target" );

        $xmlWriter->endElement();
        $transUnit[] = $xmlWriter->outputMemory( true );
    }

    $fileXmlWriter = new XMLWriter();
    $fileXmlWriter->openMemory();
    $fileXmlWriter->setIndent( true );
    $fileXmlWriter->startDocument( '1.0' );
    
    $fileXmlWriter->startElement( "xliff" );
    $fileXmlWriter->writeAttribute( "version", "1.0" );

    $fileXmlWriter->startElement( "file" );
    $fileXmlWriter->writeAttribute( "source-language", "EN" );
    $fileXmlWriter->writeAttribute( "target-language", $this->culture );
    $fileXmlWriter->writeAttribute( "datatype", "plaintext" );
    $fileXmlWriter->writeAttribute( "original", $catalogue );
    $fileXmlWriter->writeAttribute( "date", date( 'c' ) );
    $fileXmlWriter->writeAttribute( "product-name", $catalogue );

    $fileXmlWriter->writeElement( "header" );
    $fileXmlWriter->startElement( "body" );

    foreach ($transUnit as $unit) {
        $fileXmlWriter->writeRaw( $unit );
    }

    $fileXmlWriter->endElement();

    $fileXmlWriter->endElement();

    $fileXmlWriter->endElement();
    $fileXmlWriter->endDocument();

    $xml = $fileXmlWriter->outputMemory( true );
    file_put_contents( $filename, $xml );

    if ($this->cache)
    {
      $this->cache->remove($variant.':'.$this->culture);
    }

    return true;
  }

}
