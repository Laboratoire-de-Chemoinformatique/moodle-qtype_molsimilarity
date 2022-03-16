# Molsimilarity question type

Moodle plugin allowing the teacher to ask a chemistry related question by drawing the expected answer. The student will answer by drawing the response.
Then, the similarity between the expected, and the student answers is computed by the use of a REST server. One server is given within this plugin, and similarity measures can be configured.
The REST server computes the similarity by the use of ISIDA descriptors and Tanimoto coefficient. 

The method of fragmentation used to create the ISIDA descriptors can be changed by modifying the file `Api_server/t0t3l2u4FCUR.xml`. The documentation about ISIDA descriptors
can be found in the subdirectory `Api_server`.

Both lone pairs and radicals are taken into account. Additionally, if the option is selected, and the two molecules to compare are similar,
the stereochemistry will be also taken into account in the grading process, thanks to the use of the INCHI.

Under Linux please use rest_api_multi, under Windows, please use rest_api_multi.exe


If you wish to launch the given server in local, without modifying the parameters, please use the following command in a shell:
`./rest_api_multi > /dev/null` (/dev/null to remove the warnings)

The default port used by the server is 9080. 
Once installed, the ISIDA Server Url (including port) can be modified in the admin settings page for the call from moodle (Administration of the website -> Plugins -> Question type -> Molsimilarity -> ISIDA Server Url.).
If modified, the port must be modified on the server side as well, by assigning the port value to the variable `portIsida` in a shell.

For security reasons, the plugin uses JSON Web Tokens (JWT) to securize the transaction between the API and Moodle.
Therefore, if you don't use the API server in local, you are highly encouraged to modify the key used to encode the signature of the JWT. 
It can be modified in the Administrator plugin parameters. 
Administration of the website -> Plugins -> Question type -> Molsimilarity -> ISIDA Server KEY.
In order for the request to the server to be accepted, the key on server side must be identical, and must be modified in `Api_server/JWTKEY.txt`.

## Installation

You can move the location of the Api_server directory, but you shoudn't move the individual elements inside it 
(`inchi-1`/`inchi-1.exe`, `rest_api_multi`, `t0t3l2u4FCUR.xml` and the `temp_stock` subdirectory need to be in the same directory). 

### API code 

If you wish to have access to the uncompiled files of the API, please send an email to Gilles Marcou: g.marcou@unistra.fr

### Copyright

    Louis Plyer louis.plyer@unistra.fr
    Gilles Marcou g.marcou@unistra.fr
    Céline Perves cperves@unistra.fr
    Rachel Schurhammer rschurhammer@unistra.fr
    Alexandre Varnek varnek@unistra.fr

### Copyright

    Louis Plyer louis.plyer@unistra.fr
    Gilles Marcou g.marcou@unistra.fr
    Céline Perves cperves@unistra.fr
    Rachel Schurhammer rschurhammer@unistra.fr
    Alexandre Varnek varnek@unistra.fr

### Licence

    GNU GPL v3 or later, IUPAC/InChI-Trust Licence No.1.0

