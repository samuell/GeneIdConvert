A Simple MediaWiki extension that converts from Ensembl Gene ID to 
desired target gene ID type, by using the CrossRef endpoint of the
new Ensembl REST API.

Author:  Samuel Lampa - samuel.lampa@gmail.com
Date:    2013-05-31
 
More info on the REST API Here:

    http://beta.rest.ensembl.org

Installation
------------

1. Place this file into a folder extensions/GeneIdConvert in your
   wiki folder.

2. Add the following line to your LocalSettings.php file:
 
````
require_once("$IP/extensions/GeneIdConvert/GeneIdConvert.php"); 
````

3. Example usage of the extension within a MediaWiki article:

````
<geneidconv to="EntrezGene">ENSG00000157764</gene>
````

 A few of the avaible options for the "to" parameter, is (as of 
 writing this):

- OTTG
- ArrayExpress
- EntrezGene
- HGNC
- MIM_GENE
- UniGene
- Uniprot_genename
- WikiGene
