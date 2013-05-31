<?php
/**
 * Simple MediaWiki extension that converts from Ensembl Gene ID to 
 * desired target gene ID type, by using the CrossRef endpoint of the
 * new Ensembl REST API.
 *
 * Author:  Samuel Lampa - samuel.lampa@gmail.com
 * Date:    2013-05-31
 *
 * More info on the REST API Here:
 *
 *      http://beta.rest.ensembl.org
 *
 * Installation:
 *
 * 1. Place this file into a folder extensions/GeneIdConvert in your
 *    wiki folder.
 * 2. Add the following line to your LocalSettings.php file:
 * 
 *    require_once("$IP/extensions/GeneIdConvert/GeneIdConvert.php"); 
 *
 * Example usage of the extension within a MediaWiki article:
 *
 *      <geneidconv to="EntrezGene">ENSG00000157764</gene>
 *
 * A few of the avaible options for the "to" parameter, is (as of 
 * writing this):
 *
 *      OTTG
 *      ArrayExpress
 *      EntrezGene
 *      HGNC
 *      MIM_GENE
 *      UniGene
 *      Uniprot_genename
 *      WikiGene
 *
 */

$wgHooks['ParserFirstCallInit'][] = 'wfGeneIdConvertParserInit';
 
// Hook our callback function into the parser
function wfGeneIdConvertParserInit( Parser $parser ) {
    // When the parser sees the <geneidconv> tag, it executes 
    $parser->setHook( 'geneidconv', 'wfGeneIdConvRender' );
    // Always return true from this function. The return value does not denote
    // success or otherwise have meaning - it just must always be true.
    return true;
}
 
// Execute 
function wfGeneIdConvRender( $srcGeneId, array $args, Parser $parser, PPFrame $frame ) {
    $ensemblRestBaseUrl = "http://beta.rest.ensembl.org/xrefs/id"; // Without trailing slash
    $targetId = "";
    $toFormat = $args['to'];

    // Construct the Query URL
    $queryUrl = $ensemblRestBaseUrl . '/' . $srcGeneId . '?content-type=application/json';
    // Do the actualy querying of the REST API
    $resultJson = file_get_contents($queryUrl);
    // Convert from JSON to associative array structure 
    $result = json_decode($resultJson, true);
    
    // Get the Gene Id that corresponds to our desired target format
    foreach ($result as $mapping) {
        if ( $mapping['dbname'] == $toFormat ) {
            $targetId = $mapping['display_id'];
        }
    }
    return $targetId;
}
