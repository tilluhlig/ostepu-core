function deleteProcessor(event) {
    var trig = $(this);
    var container = trig.parents('.processor');
    var container2 = trig.parents('.processor').parent().parent();

    container.slideToggle('fast', function() {
    container[0].parentNode.removeChild(container[0]);
    container2[0].parentNode.removeChild(container2[0]);
    });
    
}

function useProcessor(event) {
var trig = $(this);
trig = trig.parent().parent();

    // append content to last exercise
    $.get("include/CreateSheet/Processor/Processor.template.php", function (data) {

        trig.before(data);
       // trig.hide().fadeOut('fast');
       // trig.parent().parent().find('.mime-field').first().attr("disabled", "disabled");   

        // animate new element
        trig.parent().find('.processor').last().hide().fadeIn('fast');
        
                
        trig.parent().find('.processor').last().children('.content-header').on("click",collapseProcessor);     
        trig.parent().find('.processor').last().children('.content-header').css('cursor','pointer');   
        trig.parent().find('.processor').last().children('.content-header').find('.delete-processor').on("click",deleteProcessor);
        
        trig.parent().find('.processor-type').last().on("change",loadProcessorTemplate);
        trig.parent().find('.processor-type').last().change();
        
        //trig.parent().find('.processor').first().find('.add-attachment').first().on("click",addProcessorAttachment);
        //trig.parent().find('.processor').first().find('.add-attachment').first().click();
        renameProcessor();
    });

}

function loadProcessorTemplate(event){
var trig = $(this);

    if (trig.parent().find('.ProcessorParameterArea').length>0){

        var container = trig.parent().find('.ProcessorParameterArea');
        
        container.slideToggle('fast', function() {
        container[0].parentNode.removeChild(container[0]);
        });
    }

    $.get("include/CreateSheet/Processor/"+trig.find('option:selected').text()+".template.php", function (data) {
    
        trig.parent().find('.processor-parameter-area').first().after(data);

    });
}

function addProcessorAttachment(event){
var trig = $(this);

    $.get("include/CreateSheet/Processor/ProcessorAddAttachment.template.php", function (data) {
    
        trig.before(data);
        renameAttachment(trig.parent());
        
        if (trig.parent().find('.delete-attachment').length >= 2) {
            trig.parent().find('.delete-attachment').first().fadeIn('fast');
        }
        else{
            trig.parent().find('.delete-attachment').first().fadeOut('fast');
        }
        
        trig.parent().find('.delete-attachment').last().on("click",removeAttachment);
        renameProcessor();
    });
}

function renameProcessor(){
    renumberExercises();

    var all = $('.processor-type');

    for (var i = 0; i < all.length; i++) {
        // add a new header text
        var elem = $(all[i]);
        var oldName = elem.attr('name');

            var regex = /exercises\[(.+?)]\[.+?\]\[(.+?)]\[(.+?)]\[[0-9]+\]/gm;
            var nameString = "exercises[$1][subexercises][$2][$3]["+ (i) +"]";

            // match the regex and replace the numbers
            var newName = oldName.replace(regex, nameString);

            // set the new name
            elem.attr('name', newName);
    }
    
    var all2 = $('.processor-parameter');

    for (var i = 0; i < all2.length; i++) {
        // add a new header text
        var elem = $(all2[i]);
        var oldName = elem.attr('name');

            var regex = /exercises\[(.+?)]\[.+?\]\[(.+?)]\[(.+?)]\[[0-9]+\]/gm;
            var nameString = "exercises[$1][subexercises][$2][$3]["+ (i) +"]";

            // match the regex and replace the numbers
            var newName = oldName.replace(regex, nameString);

            // set the new name
            elem.attr('name', newName);
    }
}

function removeAttachment(event){
var trig = $(this);
    
trig.parent().slideToggle('fast', function() {
        trig.parent().remove();
    });

if (trig.parent().parent().find('.processor-attachment').length == 2) {
            trig.parent().parent().find('.processor-attachment').not(trig.parent()).first().find('.delete-attachment').fadeOut('fast');
        }
}

function renameAttachment(trig) {
/*var oldName = trig.parent('.form').parent().parent().find('.text-input').first().attr('name');
var regex = /exercises\[([0-9]+)\]\[.+?\]\[([0-9]+)\]\[(.+?)]/gm;
var nameString = "exercises[$1][subexercises][$2][correct]";
var nameString2 = "exercises[$1][subexercises][$2][choice]";

// match the regex and replace the numbers
var newName = oldName.replace(regex, nameString);
var newName2 = oldName.replace(regex, nameString2);

trig.find('.input-radio').attr('name', newName);
trig.find('.text-input').attr('name', newName2);*/
}

/**
 * toggle function on click to hide/show .content-header elements
 */
function collapseProcessor(event) {
    // trig = event sender
    var trig = $(this);
    // toggle the next available element of .content-body-wrapper near the "trig" with duration "fast"
    if (trig.parent('.processor').length !== 0) {
        trig.parent().children('.content-body-wrapper, .content-footer').first().slideToggle('fast');
        trig.toggleClass( 'inactive',  !trig.hasClass('inactive') );
    }
}