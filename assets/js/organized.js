/*!
 * jQuery namespaced 'Starter' plugin boilerplate
 * Author: @dougneiner
 * Further changes: @addyosmani
 * Licensed under the MIT license
 */

;(function ( $ ) {

    var organized = {

        wrap: $('#organized'),
        form: $('#add-edit-thing'),
        uls: $('.sortable'),
        nonce: organized_plugin.nonce,
        ajax_url: organized_plugin.ajax_url,

        init: function( settings ) {

            organized.formInit();
            organized.dashboardInit();

            organized.wrap
                .on( 'click', '#save-thing', organized.saveThing )
                .on( 'click', '#delete-thing', organized.deleteThing )
                .on( 'click', '#edit-thing', organized.editThing )
                .on( 'click', '#add-file', organized.addFile )
                .on( 'click', '#remove-file', organized.removeFile );

        },

        formInit: function() {

            organized.resetForm();

            organized.form.find( '.datepicker' ).datepicker();
            organized.form.find( '.colorpicker' ).wpColorPicker();

            organized.form.find("#add-todo").todoList({
                removeLabel: organized_plugin.todo_remove,
                newItemPlaceholder: organized_plugin.todo_placeholder,
                editItemTooltip: organized_plugin.todo_tooltip,
                focusOnTitle: true,
                customActions: null,
                items: []
            });

            organized.form.find( ".organized-todo-items" ).sortable({
                items: '.organized-todo-item',
            }).disableSelection();

            organized.form.bind('keypress keydown keyup', function(e){
                if(e.keyCode == 13) {
                    if ( $("#save-thing").is(":focus") || $("#title").is(":focus") || $("#notes").is(":focus") ) {
                        // do nothing, allow enter
                    } else {
                        e.preventDefault();
                        return false;
                    }
                }
            });

        },


        dashboardInit: function() {

            organized.uls.sortable({
                connectWith: ".connected",
                handle: ".handle",
                stop: function(e, ui) {
                    organized.updateThings();
                }
            }).disableSelection();

        },
     
        updateThings: function( positions ) {
            
            // work out the positions and put into array
            var positions = [];
            organized.uls.each(function(){
                positions.push($(this).sortable('toArray', {attribute: 'data-id'}));
            });
            //console.log(positions);
            jQuery.ajax({
                url :       organized.ajax_url,
                type :      'post',
                dataType:   'json',
                data : { 
                    action: "update_things", 
                    positions : positions, 
                    nonce : organized.nonce, 
                },
                success: function(response) {
                    organized.message( response.message );
                }
            });
        },


        getTodos: function() {
            // get todo list data
            var todos = [];
            $(".organized-todo-item").each(function(){
                var item = $(this).find(".organized-todo-item-title-text").text();
                var done = $(this).hasClass( 'organized-todo-item-done' ) ? 'true' : 'false';
                todos.push({ 'item': item, 'done': done });
            });
            return todos;
        },

        
        saveThing: function(e) {
            e.preventDefault(); 

            // get all posted data
            var posted = organized.form.serialize();

            jQuery.ajax({
                url :       organized.ajax_url,
                type :      'post',
                dataType:   'json',
                data : { 
                    action : "save_the_thing", 
                    todos : organized.getTodos(), 
                    posted : posted,
                    nonce : organized.nonce,
                },
                success: function(response) {

                    if(response.result == "success") {
                        
                        // either add new thing
                        // or update existing thing
                        if( response.edit == '0' ) {
                            organized.uls.first().prepend(response.data);
                        } else {
                            organized.uls.find('[data-id="'+response.edit+'"]').replaceWith(response.data);
                        }

                        // update the things
                        organized.updateThings();

                        // reset the form
                        organized.resetForm();

                    }

                    organized.message( response.message );
                }
            });  

        },

        editThing: function(e) {
            e.preventDefault(); 

            // get id of the thing
            var id = $(this).parent('.thing').attr('data-id');
            
            jQuery.ajax({
                url :       organized.ajax_url,
                type :      'post',
                dataType:   'json',
                data : { 
                    action : "edit_the_thing", 
                    id : id, 
                    nonce : organized.nonce,
                },
                success: function(response) {
                    
                    if(response.result == "success") {
                        organized.populateForm( response.data );
                        organized.wrap.find( '.add-thing > h2' ).text( organized_plugin.edit_thing );
                    }

                    organized.message( response.message );
                }
            });

        },

        deleteThing: function(e) {
        
            e.preventDefault(); 

            var elm = $(this);
            var yes = "<span class='confirm-delete'>"+ organized_plugin.delete +"</span>";
            var no = "<span class='cancel-delete'>"+ organized_plugin.cancel +"</span>";
            var thing = $( elm ).parent('.thing');
            var id = $( thing ).attr('data-id');
            
            // show the buttons
            $( thing ).find('.confirm').remove();
            $( thing ).find('.inner').prepend( '<span class="confirm">' + no + yes + '</span>' );

            $(".confirm-delete").click( function(event) {
                $( elm ).parent( '.thing' ).fadeOut("normal", function() {
                    $(this).remove();
                });
                organized.deleteConfirm( id );
            });

            $(".cancel-delete").click( function(event) {
                $( thing ).find( '.confirm' ).fadeOut("normal", function() {
                    $(this).remove();
                });
            });

            // if no action, hide it
            setTimeout( function() {
                $('.confirm').fadeOut('slow');
            }, 5000);
            
            

        },

        deleteConfirm: function( id ) {
        
            jQuery.ajax({
                url :       organized.ajax_url,
                type :      'post',
                dataType:   'json',
                data : { 
                    action : "delete_the_thing", 
                    id : id, 
                    nonce : organized.nonce,
                },
                success: function(response) {
                    
                    if(response.result == "success") {
                        organized.resetForm();
                    }
                    organized.message( response.message );
                }
            });

        },

        message: function( message ) {
            $('.organized-message')
                .html('')
                .appendTo('#organized')
                .fadeIn(600)
                .css({'position':'absolute', 'top' : 22, 'right': 20})
                .prepend( message )
                .delay(1500)
                .fadeOut('slow');
        },

        populateForm: function(data) {

            organized.resetForm();

            var $form = organized.form;

            $.each(data, function(key, value) {
                // console.log(key);
                // console.log(value);
                var $ctrl = $form.find('[name='+key+']');
                if ($ctrl.is('select')){
                    $('option', $ctrl).each(function() {
                        if (this.value == value)
                            this.selected = true;
                    });
                } else if ($ctrl.is('textarea')) {
                    $ctrl.val(value);
                } else {
                    switch($ctrl.attr("type")) {
                        case "text":
                        case "hidden":
                            $ctrl.val(value);   
                            break;
                        case "checkbox":
                            if (value == '1')
                                $ctrl.prop('checked', true);
                            else
                                $ctrl.prop('checked', false);
                            break;
                    } 
                }

                if( key == 'todos' ) {
                    $.each(value, function(i, todo) {
                        var $el = $( organized_plugin.todo_item );
                        $( '.organized-todo-items' ).append( $el );
                        $el.find( '.organized-todo-item-title-text' ).text( todo.item );
                        if( todo.done === 'true' ) {
                            $el.addClass( 'organized-todo-item-done' );
                        }
                    });
                }

                if( key == 'tags' ) {

                    $.each(value, function(i, tag) {
                        // Set the value, creating a new option if necessary
                        if ($('#tags').find("option[value='" + tag + "']").length) {
                            $('#tags').val(tag).trigger('change');
                        } else { 
                            // Create a DOM Option and pre-select by default
                            var newOption = new Option(tag, i, true, true);
                            // Append it to the select
                            $('#tags').append(newOption).trigger('change');
                        }  
                    });

                }

                if( key == 'file_data' && value !== '' ) {
                    organized.insertFileData( value );
                }

                if( key == 'color' && value !== '' ) {
                    $( 'a.wp-color-result' ).css({'background-color':value})
                    $( 'input#color' ).val(value)
                }

            });

        },

        addFile: function( e ) {
            // Stop the anchor's default behavior
            e.preventDefault();
            // Display the media uploader
            organized.mediaUploader();
        },

        removeFile: function( e ) {
            // Stop the anchor's default behavior
            e.preventDefault();
            // Display the media uploader
            organized.resetUploadForm();
        },

        resetUploadForm: function() {
            // First, we'll hide the image
            $( '#file-container' )
                .children( 'img' )
                .remove();
         
            // Then display the previous container
            $( '#file-container' )
                .prev()
                .show();
         
            // Finally, we add the 'hidden' class back to this anchor's parent
            $( '#file-container' )
                .next()
                .hide()
                .addClass( 'hidden' );

            $( '#file_data' ).val('');
        },

        /**
         * Callback function for the 'click' event of the 'Set Footer Image'
         * anchor in its meta box.
         *
         * Displays the media uploader for selecting an image.
         *
         * @since 0.1.0
         */
        mediaUploader: function() {
         
            var file_frame, image_data;

            _wpMediaViewsL10n.insertIntoPost = organized_plugin.insert_file;
            /**
             * If an instance of file_frame already exists, then we can open it
             * rather than creating a new instance.
             */
            if ( undefined !== file_frame ) {
                file_frame.open();
                return;
            }
         
            /**
             * If we're this far, then an instance does not exist, so we need to
             * create our own.
             *
             * Here, use the wp.media library to define the settings of the Media
             * Uploader. We're opting to use the 'post' frame which is a template
             * defined in WordPress core and are initializing the file frame
             * with the 'insert' state.
             *
             * We're also not allowing the user to select more than one image.
             */
            file_frame = wp.media.frames.file_frame = wp.media( {
                frame:    'post',
                state:    'insert',
                multiple: false,
            });
                
            file_frame.on( 'menu:render:default', function( view ) {
                // Store our views in an object.
                var views = {};

                // Unset default menu items
                view.unset( 'library-separator' );
                view.unset( 'gallery' );
                view.unset( 'featured-image' );
                view.unset( 'embed' );

                // Initialize the views in our view object.
                view.set( views );
            } );

            /**
             * Setup an event handler for what to do when an image has been
             * selected.
             *
             * Since we're using the 'view' state when initializing
             * the file_frame, we need to make sure that the handler is attached
             * to the insert event.
             */
            file_frame.on( 'insert', function() {
         
                // Read the JSON data returned from the Media Uploader
                var json = file_frame.state().get( 'selection' ).first().toJSON();
                
                // First, make sure that we have the URL of an image to display
                if ( 0 > $.trim( json.url.length ) ) {
                    return;
                }
             
                // After that, set the properties of the image and display it
                if( json.type === 'image' ) {
                    img = json.url;
                } else {
                    img = json.icon;
                }

                file_data = {};
                file_data.id = json.id;
                file_data.preview = img;
                file_data.filename = json.filename;
                file_data.date = json.dateFormatted;
                file_data.editurl = json.editLink;
                file_data.filesize = json.filesizeHumanReadable;
                file_data.subtype = json.subtype;
                file_data.url = json.url;

                organized.insertFileData( file_data );
                
            });
         
            // Now display the actual file_frame
            file_frame.open();
         
        },


        insertFileData: function( file_data ) {

            $( '#file-container' )
                .append( '<img src="' + file_data.preview + '" />' )
                    .show()
                .parent()
                .removeClass( 'hidden' );
         
            // Next, hide the anchor responsible for allowing the user to select an image
            $( '#file-container' )
                .prev()
                .hide();

            // Display the anchor for the removing the featured image
            $( '#file-container' )
                .next()
                .show();

            // populate our input with image data to be saved
            $( '#file_data' ).val( JSON.stringify( file_data ) );

        },

        resetForm: function() {
            organized.wrap.find( '.add-thing > h2' ).text( organized_plugin.add_thing );
            organized.form.find('input:text, input:hidden, input:password, input:file, textarea, select').val('');
            organized.form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
            organized.form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
            organized.form.find('select').val(null).trigger('change');
            organized.form.find('.organized-todo-items').html(null);
            organized.form.find('.wp-color-result').css({'background-color':'#f7f7f7'});
            organized.resetUploadForm();
            organized.form.find("#title").focus();
        },

    };
     
    organized.init()

})( jQuery );