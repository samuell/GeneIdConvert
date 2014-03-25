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
 *      {{ #geneidconv: ENSG00000157764 | EntrezGene }}
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

// Take credit for your work.
$wgExtensionCredits['parserhook'][] = array(
 
   // The full path and filename of the file. This allows MediaWiki
   // to display the Subversion revision number on Special:Version.
   'path' => __FILE__,
 
   // The name of the extension, which will appear on Special:Version.
   'name' => 'Gene ID Convert',
 
   // A description of the extension, which will appear on Special:Version.
   'description' => 'Simple MediaWiki extension that converts from Ensembl Gene ID to desired target gene ID type',
 
   // Alternatively, you can specify a message key for the description.
   // 'descriptionmsg' => 'exampleextension-desc',
 
   // The version of the extension, which will appear on Special:Version.
   // This can be a number or a string.
   'version' => 1, 
 
   // Your name, which will appear on Special:Version.
   'author' => 'Samuel Lampa',
 
   // The URL to a wiki page/web page with information about the extension,
   // which will appear on Special:Version.
   'url' => 'https://www.mediawiki.org/wiki/Manual:Parser_functions',
 
);

// Specify the function that will initialize the parser function.
$wgHooks['ParserFirstCallInit'][] = 'GeneIdConvertSetupParserFunction';

// Allow translation of the parser function name
$wgExtensionMessagesFiles['GeneIdConvert'] = __DIR__ . '/GeneIdConvert.i18n.php';

// Hook our callback function into the parser
function GeneIdConvertSetupParserFunction( &$parser ) {
    // When the parser sees the <geneidconv> tag, it executes 
    $parser->setFunctionHook( 'geneidconv', 'GeneIdConvertRenderParserFunction' );
    // Always return true from this function. The return value does not denote
    // success or otherwise have meaning - it just must always be true.
    return true;
}
 
// function geneIDConvert( $srcGeneId, array $args, Parser $parser, PPFrame $frame ) {
function GeneIdConvertRenderParserFunction( $parser, $srcGeneId = '', $toFormat = '' ) {

    if ( $srcGeneId === '' ) {
        return 'ERROR:Field_1_gene_id_missing';
    }
    if ( $toFormat === '' ) {
        return 'ERROR:Field_2_target_format_missing';
    }
    // Disable cache, at least for debugging purposes
    $parser->disableCache();

    // Set some variables
    $ensemblRestBaseUrl = "http://beta.rest.ensembl.org/xrefs/id"; // Without trailing slash
    $targetId = '';

    // Construct the Query URL
    $queryUrl = $ensemblRestBaseUrl . '/' . $srcGeneId . '?content-type=application/json';
    // Need to set this to allow file_get_contents to ask for URLs
    ini_set('allow_url_fopen', 1);
    // Do the actualy querying of the REST API
    //$resultJson = file_get_contents( $queryUrl );
    $resultJson = wfGetRemoteData( $queryUrl );
    // Convert from JSON to associative array structure 
    $result = json_decode($resultJson, true);
    
    // Get the Gene Id that corresponds to our desired target format
    if ( isset( $result ) ) {
        foreach ($result as $mapping) {
            if ( $mapping['dbname'] == $toFormat ) {
                if ( $toFormat == 'EntrezGene' ) {
                    $arrayKey = 'primary_id';
                } else {
                    $arrayKey = 'display_id';
                }
                $targetId = $mapping[$arrayKey];
            }
        }
    } else {
        $targetId = 'N/A';
    }
    return $targetId;
}

function wfGetRemoteData( $url ) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
