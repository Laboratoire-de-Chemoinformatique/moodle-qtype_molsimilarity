// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Module script to render the Chemdoodle sketcher for the Molsimilarity question type
 *
 * @package qtype
 * @subpackage  molsimilarity
 * @copyright  2021 unistra  {@link http://unistra.fr}
 * @author Louis Plyer <louis.plyer@unistra.fr>, Céline Pervès <cperves@unistra.fr>, Alexandre Varnek <varnek@unistra.fr>,
 * Rachel Schurhammer <rschurhammer@unistra.fr>, Gilles Marcou <g.marcou@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

M.qtype_molsimilarity={
    // Insert for the renderer
    insert_cwc : function(Y, toreplaceid, name, inputname, readonly, dirroot){
        let dirr = JSON.parse(dirroot).dirrMoodle;
        let location_isida = '#' + inputname;

        function style () {
        ChemDoodle.DEFAULT_STYLES.bondLength_2D = 14.4;
        ChemDoodle.DEFAULT_STYLES.bonds_width_2D = .6;
        ChemDoodle.DEFAULT_STYLES.bonds_saturationWidthAbs_2D = 2.6;
        ChemDoodle.DEFAULT_STYLES.bonds_hashSpacing_2D = 2.5;
        ChemDoodle.DEFAULT_STYLES.atoms_font_size_2D = 10;
        ChemDoodle.DEFAULT_STYLES.atoms_font_families_2D = ["Helvetica", "Arial", "sans-serif"];
        ChemDoodle.DEFAULT_STYLES.atoms_displayTerminalCarbonLabels_2D = true;
        ChemDoodle.DEFAULT_STYLES.atoms_useJMOLColors = true;
        ChemDoodle.ELEMENT['H'].jmolColor = 'black';
        ChemDoodle.ELEMENT['S'].jmolColor = '#B9A130';
        window[name].styles.bonds_clearOverlaps_2D = true;
        }

        if (readonly){
            window[name] = new ChemDoodle.ViewerCanvas(toreplaceid, 400, 300);
            window[name].emptyMessage = 'No data loaded';
            style();
        }

        else{
            window[name] = new ChemDoodle.SketcherCanvas(toreplaceid, 550, 300, {useServices:false, oneMolecule:true});
            style();
            let initmol = ChemDoodle.readJSON("{\"m\":[{\"a\":[]}]}");
            let meth = ChemDoodle.readJSON("{\"m\":[{\"a\":[{\"x\":236.75,\"y\":134,\"i\":\"a0\"}]}]}");

            window[name].loadMolecule(initmol['molecules'][0]);
            window[name].click = initcanvas;

            function initcanvas(){
                let json_data = JSON.stringify(new ChemDoodle.io.JSONInterpreter().contentTo(window[name].molecules));
                if (json_data === '{\"m\":[{\"a\":[]}]}') {
                    //console.log('canvas empty');
                    window[name].loadMolecule(meth['molecules'][0]);
                }
            }

            /**
            $(document).on('submit', '#responseform', function() { // error here
                let json_data = JSON.stringify(new ChemDoodle.io.JSONInterpreter().contentTo(window[name].molecules));
                // We check if the answer is not the "empty" molecule used to instantiate the ketcher.
                if (json_data !== '{\"m\":[{\"a\":[]}]}') {
                    let mol = ChemDoodle.writeMOL(window[name].getMolecule());
                    ajax_call(mol, json_data, location_isida, dirr);
                }
                return true;
            });*/

            const moodleform = document.getElementById("responseform");
            moodleform.addEventListener("submit", function (event) {
                let json_data = JSON.stringify(new ChemDoodle.io.JSONInterpreter().contentTo(window[name].molecules));
                // We check if there is an answer, if not, we send an empty json, which can be loaded into the canvas.
                if (json_data !== '{\"m\":[{\"a\":[]}]}') {
                    let mol = ChemDoodle.writeMOL(window[name].getMolecule());
                    ajax_call(mol, json_data, location_isida, dirr);
                }
                return true;
            });
        }
        let lastmol = document.getElementById(inputname).value;
        //console.log(JSON.parse(lastmol).json);
        if(lastmol.length > 0) {
            let cmcmol = ChemDoodle.readJSON(JSON.parse(lastmol).json);
            window[name].loadMolecule(cmcmol['molecules'][0]);
        }
        else { // Case ketcher not instantiated, we use the empty "mol".
            let initmol = ChemDoodle.readJSON("{\"m\":[{\"a\":[]}]}");
            window[name].loadMolecule(initmol['molecules'][0]);
        }
        $(function(){
            if($('.ChemDoodleWebComponent').length){
                $('#techinfo_inner :nth-child(6n)').css('white-space','pre'); // For the preview.
            }});
    },
    // Insert in the form
    insert_form : function (Y, dirroot, sesskey) {
            ChemDoodle.ELEMENT['H'].jmolColor = 'black';
            ChemDoodle.ELEMENT['S'].jmolColor = '#B9A130';
            let sketcher = new ChemDoodle.SketcherCanvas('sketcher', 550, 300, {useServices:false, oneMolecule:true});
            sketcher.styles.atoms_useJMOLColors = true;
            sketcher.styles.bonds_clearOverlaps_2D = true;

            let half_bond = document.getElementById('sketcher_button_bond_half_label');
            half_bond.remove(); // removing the "halfbond"
        
            $().ready(function () {$('[classo=load-molfile]').first().trigger("click")});
            function get_source (parent) {
                let buttonname = parent.attr('name');
                let textfieldid = 'id_answer_' + buttonname.substr(buttonname.length - 2, 1);
                let source = $(`#${textfieldid}`);
                return source;
            }

            $('[classo=set-molfile]').click(function () {
                let dirr = JSON.parse(dirroot).dirrMoodle;
                let parent = $(this);
                let molfile = ChemDoodle.writeMOL(sketcher.getMolecule());
                let json_data = JSON.stringify(new ChemDoodle.io.JSONInterpreter().contentTo(sketcher.molecules));
                let box = get_source(parent);
                console.log(box);
                ajax_call(molfile, json_data, box, dirr);
                if (box.val().length >= 1) {$('[classo = mol_empty]').hide();}
            });

            $('[classo=load-molfile]').click(function () {
                let $parent = $(this);
                let box = get_source($parent);
                if (box.val()){
                    let val = JSON.parse(box.val());
                    let data = ChemDoodle.readJSON(val.json);
                    sketcher.loadMolecule(data['molecules'][0]);
                    $('[classo = mol_empty]').hide();
                }
            });
            $('[classo=clear_answer]').click(function () {
                let $parent = $(this);
                let box = get_source($parent);
                if (box.val()){
                    box.val("");
                }
            });
            $(function(){
                    $('[id^="fitem_id_feedback_"]:not(:first)').css('display','none'); // Because we only need the first specific feedback
                });
        },
    insert_good_answer : function (Y, toreplaceid, name, correct_data) {
        window[name] = new ChemDoodle.ViewerCanvas(toreplaceid, 400, 300);
        window[name].emptyMessage = 'No data loaded';
        let correct_mol = correct_data;
        console.log(correct_mol);
        if (correct_mol.length > 0) {
            let cmcmol = ChemDoodle.readJSON(correct_mol);
            window[name].loadMolecule(cmcmol['molecules'][0]);
        }
        },
    };