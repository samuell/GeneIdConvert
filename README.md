A Simple MediaWiki extension that converts from Ensembl Gene ID to 
desired target gene ID type, by using the CrossRef endpoint of the
new Ensembl REST API.

Author: Samuel Lampa - samuel.lampa@gmail.com
Created Date: 2013-05-31
 
More info on the REST API Here: http://beta.rest.ensembl.org

Installation
------------

- Place this file into a folder extensions/GeneIdConvert in your
   wiki folder.

- Add the following line to your LocalSettings.php file:
 
````
require_once("$IP/extensions/GeneIdConvert/GeneIdConvert.php");
````

Usage
------------

Example usage of the extension within a MediaWiki article:

````
{{ #geneidconv: ENSG00000157764 | EntrezGene }}
````

... where the first field is the source ID (in Ensembl ID format) 
and the second field is the target ID type.

A few of the avaible options for the target ID (as of 
writing this) can be found below (please note that the lower/upper-
casing might be important):

- OTTG
- ArrayExpress
- EntrezGene
- HGNC
- MIM_GENE
- UniGene
- Uniprot_genename
- WikiGene
