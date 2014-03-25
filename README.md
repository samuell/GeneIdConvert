A Simple MediaWiki extension that converts from Ensembl Gene ID to 
desired target gene ID type, by using the CrossRef endpoint of the
new Ensembl REST API.

Author:  Samuel Lampa - samuel.lampa@gmail.com
Created Date:    2013-05-31
 
More info on the REST API Here:

    http://beta.rest.ensembl.org

Installation
------------

- Place this file into a folder extensions/GeneIdConvert in your
   wiki folder.

- Add the following line to your LocalSettings.php file:
 
````
require_once("$IP/extensions/GeneIdConvert/GeneIdConvert.php");
````

- Example usage of the extension within a MediaWiki article:

````
{{ #geneidconv: ENSG00000157764 | EntrezGene }}
````

... where the first field is the source ID (in Ensembl ID format) 
and the second field is the target id type.

A few of the avaible options for the "to" parameter, is (as of 
writing this) are the following:

- OTTG
- ArrayExpress
- EntrezGene
- HGNC
- MIM_GENE
- UniGene
- Uniprot_genename
- WikiGene
