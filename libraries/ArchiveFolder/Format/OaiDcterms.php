<?php
/**
 * Metadata format map for the oai_dcterms Dublin Core Terms format.
 *
 * @package ArchiveFolder
 */
class ArchiveFolder_Format_OaiDcterms extends ArchiveFolder_Format_Abstract
{
    const METADATA_PREFIX = 'oai_dcterms';
    const METADATA_SCHEMA = 'http://www.openarchives.org/OAI/2.0/oai_dcterms.xsd';
    const METADATA_NAMESPACE = 'http://www.openarchives.org/OAI/2.0/oai_dcterms/';

    // Namespaces for simple and qualified Dublin Core.
    const DUBLIN_CORE_NAMESPACE = 'http://purl.org/dc/elements/1.1/';
    const DCTERMS_NAMESPACE = 'http://purl.org/dc/terms/';

    protected $_metadataPrefix = self::METADATA_PREFIX;
    protected $_metadataSchema = self::METADATA_SCHEMA;
    protected $_metadataNamespace = self::METADATA_NAMESPACE;

    protected $_parametersFormat = array(
        'use_dcterms' => true,
        'link_to_files' => true,
        'support_separated_files' => true,
        'compare_directly' => true,
    );

    // List of Qualified Dublin Core terms. This list is built automatically.
    protected $_dcmiTerms = array();

    public function __construct($parameters, $writer)
    {
        parent::__construct($parameters, $writer);

        $this->_loadDcmiElements();
    }

    protected function _fillMetadata($record = null)
    {
        $writer = $this->_writer;

        if (is_null($record)) {
            $record = $this->_document;
        }

        // Prepare the oai record.
        $writer->startElement('oai_dcterms:dcterms');
        $writer->writeAttribute('xmlns:oai_dcterms', self::METADATA_NAMESPACE);
        $writer->writeAttribute('xmlns:dc', self::DUBLIN_CORE_NAMESPACE);
        $writer->writeAttribute('xmlns:dcterms', self::DCTERMS_NAMESPACE);
        $writer->writeAttribute('xmlns:' . self::XSI_PREFIX, self::XSI_NAMESPACE);
        $writer->writeAttribute('xsi:schemaLocation', self::METADATA_NAMESPACE . ' ' . self::METADATA_SCHEMA);

        $this->_fillDublinCore($record['metadata']);

        $writer->endElement();
    }
}