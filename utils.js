/**
 * Utils script to call the External Web Service for the Molsimilarity question type
 *
 * @package qtype
 * @subpackage  molsimilarity
 * @copyright  2021 unistra  {@link http://unistra.fr}
 * @author Louis Plyer <louis.plyer@unistra.fr>, Céline Pervès <cperves@unistra.fr>, Alexandre Varnek <varnek@unistra.fr>,
 * Rachel Schurhammer <rschurhammer@unistra.fr>, Gilles Marcou <g.marcou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * @param molfile $molfile of the given molecule
 * @param json_data $json_data Json containing info about the lone pairs ( for more info, visit https://web.chemdoodle.com/docs/chemdoodle-json-format )
 * @param location_isida
 */
function ajax_call (molfile, json_data, location_isida, dirroot) {
    let request;
    console.log(molfile);
    if (request){
        request.abort();
    }
    let get_data_mol = '';
    let get_data_json = '';
    $.ajax({
        async: false,
        dataType: 'json',
        url: dirroot + '/lib/ajax/service.php?sesskey='+M.cfg.sesskey,
        type: 'post',
        processData: false,
        data: JSON.stringify([{
            methodname: 'qtype_molsimilarity_modify_molfile',
            args: {
                molfile: molfile,
                json_data: json_data,
                sesskey: M.cfg.sesskey
            }
        }])
    })
    .done(function (response) {
        console.log('Correction ongoing.');
        get_data_mol = response[0].data.mol_file;
        get_data_json = response[0].data.json;
        // console.log(get_data);
        // console.log(response.json);
        //$(location_isida).val(JSON.stringify(get_data));
        $(location_isida).val(JSON.stringify(response[0].data));
        console.log(response[0].data.mol_file);
        console.log($(location_isida).val());
    })
    .fail(function (response){
        console.error('The following error occurred : %o', response);
    })
    /*.always(function (response) {
        get_data = (response);
        // console.log(get_data);
        // console.log(response.json);
        $(location_isida).val(JSON.stringify(get_data));
        console.log(response.mol_file);
        console.log($(location_isida).val());
    })*/
}
