$(document).ready(function () {
    'use strict';
    if ($("#elm1").length > 0) {
      tinymce.init({
        selector: "textarea#elm1",           
        height: 300,
        plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
         toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor",
        style_formats: [
          { title: 'Bold text', inline: 'b' },
          { title: 'Red text', inline: 'span', styles: { color: '#ff0000' } },
          { title: 'Red header', block: 'h1', styles: { color: '#ff0000' } },
          { title: 'Example 1', inline: 'span', classes: 'example1' },
          { title: 'Example 2', inline: 'span', classes: 'example2' },
          { title: 'Table styles' },
          { title: 'Table row 1', selector: 'tr', classes: 'tablerow1' }
        ]
      });
    }

    if ($(".elm1_editor").length > 0) {
      tinymce.init({
        selector: "textarea.elm1_editor",           
        height: 300,
        plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
         toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor",
        style_formats: [
          { title: 'Bold text', inline: 'b' },
          { title: 'Red text', inline: 'span', styles: { color: '#ff0000' } },
          { title: 'Red header', block: 'h1', styles: { color: '#ff0000' } },
          { title: 'Example 1', inline: 'span', classes: 'example1' },
          { title: 'Example 2', inline: 'span', classes: 'example2' },
          { title: 'Table styles' },
          { title: 'Table row 1', selector: 'tr', classes: 'tablerow1' }
        ]
      });
    }


    $(".select2, .select3, .select4, .select5, .select6, .select7, .select8").select2();
    $(".select2-limiting, .select3-limiting, .select4-limiting, .select5-limiting, .select6-limiting, .select7-limiting, .select8-limiting").select2({
      maximumSelectionLength: 2
    });


    jQuery('#datepicker-autoclose').datepicker({
        autoclose: true,
        todayHighlight: true
    });
    jQuery('.datepicker_trans').datepicker({
        autoclose: true,
        todayHighlight: true
    });       

    //Category
    $('#type_id, #location_id').on('change', function () {
        var url = $(this).val(); // get selected value
       
        if (url) { // require a URL
            window.location = url; // redirect
        }
        return false;
    });

    //Colors
    $('#gateway_select').on('change', function () {
        var url = $(this).val(); // get selected value
       
        if (url) { // require a URL
            window.location = url; // redirect
        }
        return false;
    });
    
    $("#admin_usertype").on('change',function(){         
      var type=$("#admin_usertype").val();
    
          if(type=="Admin")
          {
              $("#master_admin_id").show();
              $("#sub_admin_id").hide();
          }
          else
          {
              $("#master_admin_id").hide();
              $("#sub_admin_id").show();
          }
    
    });

    $("select").on("select2:select", function (evt) {
      var element = evt.params.data.element;
      var $element = $(element);
      
      $element.detach();
      $(this).append($element);
      $(this).trigger("change");
    }); 
 
});