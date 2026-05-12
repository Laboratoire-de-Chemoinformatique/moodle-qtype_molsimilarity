# Molsimilarity
Moodle plugin allowing the teacher to ask a chemistry related question in which the expected answer is a chemical structure. The student will answer by drawing the response. The specificity of the plugin is that is allows for soft grading: the grade is computed based on the similarity between the expected and the student's answer. Thus, partially correct answers are adequately awarded.

The similarity between the expected, and the student's answer is computed through a REST server. The server software is provided within this plugin, and it can be configured to fine tune how the similarity measures are computed. The REST server computes the similarity based on ISIDA molecular descriptors and Tanimoto coefficient. The teacher has some control over the way the similarity is translated into a grade, allowing to adjust the level of exigency of a given question.

Both lone pairs and radicals are taken into account. Additionally, if the option is selected, and the two molecules to compare are similar, the stereochemistry will be also taken into account in the grading process, thanks to the use of the INCHI.

## Moodle patch
Before Moodle 5.0 version, while reviewing a molsimilarity question javascript are not loaded.
Please apply the patch to moodle core  with the given patch provided in patch subdirectory
```shell
patch -p1 /www_moodle_path/mod/quiz/reviewquestion.php < /www_moodle_path/moodle2_uds/question/type/molsimilarity/patch/mod_quiz_reviewquestion.patch
```
see https://moodle.atlassian.net/browse/MDL-83392 for fuether information

## Installation
### with Docker
* isida server version are available in github at https://github.com/cperves/docker-isida/
* Pull docker image
```shell
# docker logout to access to anonymous access
docker pull ghcr.io/cperves/isida:version_number
```
  * put version number to last available version number
* run it
```shell
docker container run --publish 9080:9080 --detach ghcr.io/cperves/isida:version_number
# server will run on 9080 port but you can change it to another port --publish newport:9080
``` 
### Manually
You can move the location of the Api_server directory, but you shoudn't move the individual elements inside it (inchi-1/inchi-1.exe, rest_api_multi/rest_api_multi.exe, t0t3l2u4FCUR.xml and the temp_stock subdirectory need to be in the same directory).

The method of fragmentation used to create the ISIDA descriptors can be changed by modifying the file Api_server/t0t3l2u4FCUR.xml. The documentation about ISIDA descriptors can be found in the subdirectory Api_server/Doc.


Under Linux please use rest_api_multi, under Windows, please use rest_api_multi.exe
Make sure that both the rest_api_multi, and the inchi-1 executable are in rwx rights.

The default port used by the server is 9080. Once installed, the ISIDA Server Url (including port) can be modified in the admin settings page for the call from moodle (Administration of the website -> Plugins -> Question type -> Molsimilarity -> ISIDA Server Url.).

If modified, the port must be modified on the server side as well, by the use of the option '--Port='.

For security reasons, the plugin uses JSON Web Tokens (JWT) to securize the transaction between the API and Moodle. Therefore, if you don't use the API server in local, you are highly encouraged to modify the key used to encode the signature of the JWT.  
It can be modified in the Administrator plugin parameters. Administration of the website -> Plugins -> Question type -> Molsimilarity -> ISIDA Server KEY.  
In order for the request to the server to be accepted, the key on server side must be identical, and must be modified in Api_server/JWTKEY.txt. If you wish to use JWT, you need to use the option '--JWTNEEDED' while launching the application.

If you wish to launch the given server in local, without modifying the parameters, please use the following command in a shell, while in the Api_server directory: ./rest_api_multi > /dev/null (/dev/null to remove the warnings)


## API code

The uncompiled files of the API server are available in the Ai_server/Doc subdirectory in the "rest_api_multi.zip" file.  
If you have any question regarding the API server, please email Gilles Marcou: g.marcou@unistra.fr


## Copyright

* Louis Plyer louis.plyer@unistra.fr
* Gilles Marcou g.marcou@unistra.fr
* Céline Perves cperves@unistra.fr
* Rachel Schurhammer rschurhammer@unistra.fr
* Alexandre Varnek varnek@unistra.fr

## Licence

GNU GPL v3 or later  
The Inchi library used by this work is licenced under the IUPAC/InChI-Trust Licence No.1.0  
The rest_api_multi is under GNU GPL v3 or later
