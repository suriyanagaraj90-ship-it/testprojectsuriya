/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

/**
 * @ignore
 * File overview: Clipboard support.
 */

//
// COPY & PASTE EXECUTION FLOWS:
// -- CTRL+C
//		* if ( isCustomCopyCutSupported )
//			* dataTransfer.setData( 'text/html', getSelectedHtml )
//		* else
//			* browser's default behavior
// -- CTRL+X
//		* listen onKey (onkeydown)
//		* fire 'saveSnapshot' on editor
//		* if ( isCustomCopyCutSupported )
//			* dataTransfer.setData( 'text/html', getSelectedHtml )
//			* extractSelectedHtml // remove selected contents
//		* else
//			* browser's default behavior
//		* deferred second 'saveSnapshot' event
// -- CTRL+V
//		* listen onKey (onkeydown)
//		* simulate 'beforepaste' for non-IEs on editable
//		* listen 'onpaste' on editable ('onbeforepaste' for IE)
//		* fire 'beforePaste' on editor
//		* if ( !canceled && ( htmlInDataTransfer || !external paste) && dataTransfer is not empty ) getClipboardDataByPastebin
//		* fire 'paste' on editor
//		* !canceled && fire 'afterPaste' on editor
// -- Copy command
//		* tryToCutCopy
//			* execCommand
//		* !success && notification
// -- Cut command
//		* fixCut
//		* tryToCutCopy
//			* execCommand
//		* !success && notification
// -- Paste command
//		* fire 'paste' on editable ('beforepaste' for IE)
//		* !canceled && execCommand 'paste'
// -- Paste from native context menu & menubar
//		(Fx & Webkits are handled in 'paste' default listener.
//		Opera cannot be handled at all because it doesn't fire any events
//		Special treatment is needed for IE, for which is this part of doc)
//		* listen 'onpaste'
//		* cancel native event
//		* fire 'beforePaste' on editor
//		* if ( !canceled && ( htmlInDataTransfer || !external paste) && dataTransfer is not empty ) getClipboardDataByPastebin
//		* execIECommand( 'paste' ) -> this fires another 'paste' event, so cancel it
//		* fire 'paste' on editor
//		* !canceled && fire 'afterPaste' on editor
//
//
// PASTE EVENT - PREPROCESSING:
// -- Possible dataValue types: auto, text, html.
// -- Possible dataValue contents:
//		* text (possible \n\r)
//		* htmlified text (text + br,div,p - no presentational markup & attrs - depends on browser)
//		* html
// -- Possible flags:
//		* htmlified - if true then content is a HTML even if no markup inside. This flag is set
//			for content from editable pastebins, because they 'htmlify' pasted content.
//
// -- Type: auto:
//		* content: htmlified text ->	filter, unify text markup (brs, ps, divs), set type: text
//		* content: html ->				filter, set type: html
// -- Type: text:
//		* content: htmlified text ->	filter, unify text markup
//		* content: html ->				filter, strip presentational markup, unify text markup
// -- Type: html:
//		* content: htmlified text ->	filter, unify text markup
//		* content: html ->				filter
//
// -- Phases:
//		* if dataValue is empty copy data from dataTransfer to dataValue (priority 1)
//		* filtering (priorities 3-5) - e.g. pastefromword filters
//		* content type sniffing (priority 6)
//		* markup transformations for text (priority 6)
//
// DRAG & DROP EXECUTION FLOWS:
// -- Drag
//		* save to the global object:
//			* drag timestamp (with 'cke-' prefix),
//			* selected html,
//			* drag range,
//			* editor instance.
//		* put drag timestamp into event.dataTransfer.text
// -- Drop
//		* if events text == saved timestamp && editor == saved editor
//			internal drag & drop occurred
//			* getRangeAtDropPosition
//			* create bookmarks for drag and drop ranges starting from the end of the document
//			* dragRange.deleteContents()
//			* fire 'paste' with saved html and drop range
//		* if events text == saved timestamp && editor != saved editor
//			cross editor drag & drop occurred
//			* getRangeAtDropPosition
//			* fire 'paste' with saved html
//			* dragRange.deleteContents()
//			* FF: refreshCursor on afterPaste
//		* if events text != saved timestamp
//			drop form external source occurred
//			* getRangeAtDropPosition
//			* if event contains html data then fire 'paste' with html
//			* else if event contains text data then fire 'paste' with encoded text
//			* FF: refreshCursor on afterPaste

'use strict';

( function() {
	var clipboardIdDataType;

	// Register the plugin.
	CKEDITOR.plugins.add( 'clipboard', {
		requires: 'dialog,notification,toolbar',
		// jscs:disable maximumLineLength
		lang: 'af,ar,az,bg,bn,bs,ca,cs,cy,da,de,de-ch,el,en,en-au,en-ca,en-gb,eo,es,es-mx,et,eu,fa,fi,fo,fr,fr-ca,gl,gu,he,hi,hr,hu,id,is,it,ja,ka,km,ko,ku,lt,lv,mk,mn,ms,nb,nl,no,oc,pl,pt,pt-br,ro,ru,si,sk,sl,sq,sr,sr-latn,sv,th,tr,tt,ug,uk,vi,zh,zh-cn', // %REMOVE_LINE_CORE%
		// jscs:enable maximumLineLength
		icons: 'copy,copy-rtl,cut,cut-rtl,paste,paste-rtl', // %REMOVE_LINE_CORE%
		hidpi: true, // %REMOVE_LINE_CORE%
		init: function( editor ) {
			var filterType,
				filtersFactory = filtersFactoryFactory( editor );

			if ( editor.config.forcePasteAsPlainText ) {
				filterType = 'plain-text';
			} else if ( editor.config.pasteFilter ) {
				filterType = editor.config.pasteFilter;
			}
			// On Webkit the pasteFilter defaults 'semantic-content' because pasted data is so terrible
			// that it must be always filtered.
			else if ( CKEDITOR.env.webkit && !( 'pasteFilter' in editor.config ) ) {
				filterType = 'semantic-content';
			}

			editor.pasteFilter = filtersFactory.get( filterType );

			initPasteClipboard( editor );
			initDragDrop( editor );

			CKEDITOR.dialog.add( 'paste', CKEDITOR.getUrl( this.path + 'dialogs/paste.js' ) );

			// Convert image file (if present) to base64 string for Firefox. Do it as the first
			// step as the conversion is asynchronous and should hold all further paste processing.
			if ( CKEDITOR.env.gecko ) {
				var supportedImageTypes = [ 'image/png', 'image/jpeg', 'image/gif' ],
					latestId;

				editor.on( 'paste', function( evt ) {
					var dataObj = evt.data,
						data = dataObj.dataValue,
						dataTransfer = dataObj.dataTransfer;

					// If data empty check for image content inside data transfer. https://dev.ckeditor.com/ticket/16705
					if ( !data && dataObj.method == 'paste' && dataTransfer && dataTransfer.getFilesCount() == 1 && latestId != dataTransfer.id ) {
						var file = dataTransfer.getFile( 0 );

						if ( CKEDITOR.tools.indexOf( supportedImageTypes, file.type ) != -1 ) {
							var fileReader = new FileReader();

							// Convert image file to img tag with base64 image.
							fileReader.addEventListener( 'load', function() {
								evt.data.dataValue = '<img src="' + fileReader.result + '" />';
								editor.fire( 'paste', evt.data );
							}, false );

							// Proceed with normal flow if reading file was aborted.
							fileReader.addEventListener( 'abort', function() {
								editor.fire( 'paste', evt.data );
							}, false );

							// Proceed with normal flow if reading file failed.
							fileReader.addEventListener( 'error', function() {
								editor.fire( 'paste', evt.data );
							}, false );

							fileReader.readAsDataURL( file );

							latestId = dataObj.dataTransfer.id;

							evt.stop();
						}
					}
				}, null, null, 1 );
			}

			editor.on( 'paste', function( evt ) {
				// Init `dataTransfer` if `paste` event was fired without it, so it will be always available.
				if ( !evt.data.dataTransfer ) {
					evt.data.dataTransfer = new CKEDITOR.plugins.clipboard.dataTransfer();
				}

				// If dataValue is already set (manually or by paste bin), so do not override it.
				if ( evt.data.dataValue ) {
					return;
				}

				var dataTransfer = evt.data.dataTransfer,
					// IE support only text data and throws exception if we try to get html data.
					// This html data object may also be empty if we drag content of the textarea.
					value = dataTransfer.getData( 'text/html' );

				if ( value ) {
					evt.data.dataValue = value;
					evt.data.type = 'html';
				} else {
					// Try to get text data otherwise.
					value = dataTransfer.getData( 'text/plain' );

					if ( value ) {
						evt.data.dataValue = editor.editable().transformPlainTextToHtml( value );
						evt.data.type = 'text';
					}
				}
			}, null, null, 1 );

			editor.on( 'paste', function( evt ) {
				var data = evt.data.dataValue,
					blockElements = CKEDITOR.dtd.$block;

				// Filter webkit garbage.
				if ( data.indexOf( 'Apple-' ) > -1 ) {
					// Replace special webkit's &nbsp; with simple space, because webkit
					// produces them even for normal spaces.
					data = data.replace( /<span class="Apple-converted-space">&nbsp;<\/span>/gi, ' ' );

					// Strip <span> around white-spaces when not in forced 'html' content type.
					// This spans are created only when pasting plain text into Webkit,
					// but for safety reasons remove them always.
					if ( evt.data.type != 'html' ) {
						data = data.replace( /<span class="Apple-tab-span"[^>]*>([^<]*)<\/span>/gi, function( all, spaces ) {
							// Replace tabs with 4 spaces like Fx does.
							return spaces.replace( /\t/g, '&nbsp;&nbsp; &nbsp;' );
						} );
					}

					// This br is produced only when copying & pasting HTML content.
					if ( data.indexOf( '<br class="Apple-interchange-newline">' ) > -1 ) {
						evt.data.startsWithEOL = 1;
						evt.data.preSniffing = 'html'; // Mark as not text.
						data = data.replace( /<br class="Apple-interchange-newline">/, '' );
					}

					// Remove all other classes.
					data = data.replace( /(<[^>]+) class="Apple-[^"]*"/gi, '$1' );
				}

				// Strip editable that was copied from inside. (https://dev.ckeditor.com/ticket/9534)
				if ( data.match( /^<[^<]+cke_(editable|contents)/i ) ) {
					var tmp,
						editable_wrapper,
						wrapper = new CKEDITOR.dom.element( 'div' );

					wrapper.setHtml( data );
					// Verify for sure and check for nested editor UI parts. (https://dev.ckeditor.com/ticket/9675)
					while ( wrapper.getChildCount() == 1 &&
							( tmp = wrapper.getFirst() ) &&
							tmp.type == CKEDITOR.NODE_ELEMENT &&	// Make sure first-child is element.
							( tmp.hasClass( 'cke_editable' ) || tmp.hasClass( 'cke_contents' ) ) ) {
						wrapper = editable_wrapper = tmp;
					}

					// If editable wrapper was found strip it and bogus <br> (added on FF).
					if ( editable_wrapper )
						data = editable_wrapper.getHtml().replace( /<br>$/i, '' );
				}

				if ( CKEDITOR.env.ie ) {
					// &nbsp; <p> -> <p> (br.cke-pasted-remove will be removed later)
					data = data.replace( /^&nbsp;(?: |\r\n)?<(\w+)/g, function( match, elementName ) {
						if ( elementName.toLowerCase() in blockElements ) {
							evt.data.preSniffing = 'html'; // Mark as not a text.
							return '<' + elementName;
						}
						return match;
					} );
				} else if ( CKEDITOR.env.webkit ) {
					// </p><div><br></div> -> </p><br>
					// We don't mark br, because this situation can happen for htmlified text too.
					data = data.replace( /<\/(\w+)><div><br><\/div>$/, function( match, elementName ) {
						if ( elementName in blockElements ) {
							evt.data.endsWithEOL = 1;
							return '</' + elementName + '>';
						}
						return match;
					} );
				} else if ( CKEDITOR.env.gecko ) {
					// Firefox adds bogus <br> when user pasted text followed by space(s).
					data = data.replace( /(\s)<br>$/, '$1' );
				}

				evt.data.dataValue = data;
			}, null, null, 3 );

			editor.on( 'paste', function( evt ) {
				var dataObj = evt.data,
					type = editor._.nextPasteType || dataObj.type,
					data = dataObj.dataValue,
					trueType,
					// Default is 'html'.
					defaultType = editor.config.clipboard_defaultContentType || 'html',
					transferType = dataObj.dataTransfer.getTransferType( editor ),
					isExternalPaste = transferType == CKEDITOR.DATA_TRANSFER_EXTERNAL,
					isActiveForcePAPT = editor.config.forcePasteAsPlainText === true;

				// If forced type is 'html' we don't need to know true data type.
				if ( type == 'html' || dataObj.preSniffing == 'html' ) {
					trueType = 'html';
				} else {
					trueType = recogniseContentType( data );
				}

				delete editor._.nextPasteType;

				// Unify text markup.
				if ( trueType == 'htmlifiedtext' ) {
					data = htmlifiedTextHtmlification( editor.config, data );
				}

				// Strip presentational markup & unify text markup.
				// Forced plain text (dialog or forcePAPT).
				// Note: we do not check dontFilter option in this case, because forcePAPT was implemented
				// before pasteFilter and pasteFilter is automatically used on Webkit&Blink since 4.5, so
				// forcePAPT should have priority as it had before 4.5.
				if ( type == 'text' && trueType == 'html' ) {
					data = filterContent( editor, data, filtersFactory.get( 'plain-text' ) );
				}
				// External paste and pasteFilter exists and filtering isn't disabled.
				// Or force filtering even for internal and cross-editor paste, when forcePAPT is active (#620).
				else if ( isExternalPaste && editor.pasteFilter && !dataObj.dontFilter || isActiveForcePAPT ) {
					data = filterContent( editor, data, editor.pasteFilter );
				}

				if ( dataObj.startsWithEOL ) {
					data = '<br data-cke-eol="1">' + data;
				}
				if ( dataObj.endsWithEOL ) {
					data += '<br data-cke-eol="1">';
				}

				if ( type == 'auto' ) {
					type = ( trueType == 'html' || defaultType == 'html' ) ? 'html' : 'text';
				}

				dataObj.type = type;
				dataObj.dataValue = data;
				delete dataObj.preSniffing;
				delete dataObj.startsWithEOL;
				delete dataObj.endsWithEOL;
			}, null, null, 6 );

			// Inserts processed data into the editor at the end of the
			// events chain.
			editor.on( 'paste', function( evt ) {
				var data = evt.data;
				if ( data.dataValue ) {
					editor.insertHtml( data.dataValue, data.type, data.range );

					// Defer 'afterPaste' so all other listeners for 'paste' will be fired first.
					// Fire afterPaste only if paste inserted some HTML.
					setTimeout( function() {
						editor.fire( 'afterPaste' );
					}, 0 );
				}
			}, null, null, 1000 );

			editor.on( 'pasteDialog', function( evt ) {
				// TODO it's possible that this setTimeout is not needed any more,
				// because of changes introduced in the same commit as this comment.
				// Editor.getClipboardData adds listener to the dialog's events which are
				// fired after a while (not like 'showDialog').
				setTimeout( function() {
					// Open default paste dialog.
					editor.openDialog( 'paste', evt.data );
				}, 0 );
			} );
		}
	} );

	function firePasteEvents( editor, data, withBeforePaste ) {
		if ( !data.type ) {
			data.type = 'auto';
		}

		if ( withBeforePaste ) {
			// Fire 'beforePaste' event so clipboard flavor get customized
			// by other plugins.
			if ( editor.fire( 'beforePaste', data ) === false )
				return false; // Event canceled
		}

		// Do not fire paste if there is no data (dataValue and dataTranfser are empty).
		// This check should be done after firing 'beforePaste' because for native paste
		// 'beforePaste' is by default fired even for empty clipboard.
		if ( !data.dataValue && data.dataTransfer.isEmpty() ) {
			return false;
		}

		if ( !data.dataValue ) {
			data.dataValue = '';
		}

		// Because of FF bug we need to use this hack, otherwise cursor is hidden
		// or it is not possible to move it (https://dev.ckeditor.com/ticket/12420).
		// Also, check that editor.toolbox exists, because the toolbar plugin might not be loaded (https://dev.ckeditor.com/ticket/13305).
		if ( CKEDITOR.env.gecko && data.method == 'drop' && editor.toolbox ) {
			editor.once( 'afterPaste', function() {
				editor.toolbox.focus();
			} );
		}

		return editor.fire( 'paste', data );
	}

	function initPasteClipboard( editor ) {
		var clipboard = CKEDITOR.plugins.clipboard,
			preventBeforePasteEvent = 0,
			preventPasteEvent = 0,
			inReadOnly = 0;

		addListeners();
		addButtonsCommands();

		/**
		 * Gets clipboard data by directly accessing the clipboard (IE only) or opening the paste dialog window.
		 *
		 *		editor.getClipboardData( function( data ) {
		 *			if ( data )
		 *				alert( data.type + ' ' + data.dataValue );
		 *		} );
		 *
		 * @member CKEDITOR.editor
		 * @param {Function/Object} callbackOrOptions For function, see the `callback` parameter documentation. The object was used before 4.7.0 with the `title` property, to set the paste dialog's title.
		 * @param {Function} callback A function that will be executed with the `data` property of the
		 * {@link CKEDITOR.editor#event-paste paste event} or `null` if none of the capturing methods succeeded.
		 * Since 4.7.0 the `callback` should be provided as a first argument, just like in the example above. This parameter will be removed in
		 * an upcoming major release.
		 */
		editor.getClipboardData = function( callbackOrOptions, callback ) {
			var beforePasteNotCanceled = false,
				dataType = 'auto';

			// Options are optional - args shift.
			if ( !callback ) {
				callback = callbackOrOptions;
				callbackOrOptions = null;
			}

			// Listen at the end of listeners chain to see if event wasn't canceled
			// and to retrieve modified data.type.
			editor.on( 'beforePaste', onBeforePaste, null, null, 1000 );

			// Listen with maximum priority to handle content before everyone else.
			// This callback will handle paste event that will be fired if direct
			// access to the clipboard succeed in IE.
			editor.on( 'paste', onPaste, null, null, 0 );

			// If command didn't succeed (only IE allows to access clipboard and only if
			// user agrees) invoke callback with null, meaning that paste is not blocked.
			if ( getClipboardDataDirectly() === false ) {
				// Direct access to the clipboard wasn't successful so remove listener.
				editor.removeListener( 'paste', onPaste );

				// If beforePaste was canceled do not open dialog.
				// Add listeners only if dialog really opened. 'pasteDialog' can be canceled.
				if ( editor._.forcePasteDialog && beforePasteNotCanceled && editor.fire( 'pasteDialog' ) ) {
					editor.on( 'pasteDialogCommit', onDialogCommit );

					// 'dialogHide' will be fired after 'pasteDialogCommit'.
					editor.on( 'dialogHide', function( evt ) {
						evt.removeListener();
						evt.data.removeListener( 'pasteDialogCommit', onDialogCommit );

						// Notify even if user canceled dialog (clicked 'cancel', ESC, etc).
						if ( !evt.data._.committed ) {
							callback( null );
						}
					} );
				} else {
					callback( null );
				}
			}

			function onPaste( evt ) {
				evt.removeListener();
				evt.cancel();
				callback( evt.data );
			}

			function onBeforePaste( evt ) {
				evt.removeListener();
				beforePasteNotCanceled = true;
				dataType = evt.data.type;
			}

			function onDialogCommit( evt ) {
				evt.removeListener();
				// Cancel pasteDialogCommit so paste dialog won't automatically fire
				// 'paste' evt by itself.
				evt.cancel();

				callback( {
					type: dataType,
					dataValue: evt.data.dataValue,
					dataTransfer: evt.data.dataTransfer,
					method: 'paste'
				} );
			}
		};

		function addButtonsCommands() {
			addButtonCommand( 'Cut', 'cut', createCutCopyCmd( 'cut' ), 10, 1 );
			addButtonCommand( 'Copy', 'copy', createCutCopyCmd( 'copy' ), 20, 4 );
			addButtonCommand( 'Paste', 'paste', createPasteCmd(), 30, 8 );

			// Force adding touchend handler to paste button (#595).
			if ( !editor._.pasteButtons ) {
				editor._.pasteButtons = [];
			}
			editor._.pasteButtons.push( 'Paste' );

			function addButtonCommand( buttonName, commandName, command, toolbarOrder, ctxMenuOrder ) {
				var lang = editor.lang.clipboard[ commandName ];

				editor.addCommand( commandName, command );
				editor.ui.addButton && editor.ui.addButton( buttonName, {
					label: lang,
					command: commandName,
					toolbar: 'clipboard,' + toolbarOrder
				} );

				// If the "menu" plugin is loaded, register the menu item.
				if ( editor.addMenuItems ) {
					editor.addMenuItem( commandName, {
						label: lang,
						command: commandName,
						group: 'clipboard',
						order: ctxMenuOrder
					} );
				}
			}
		}

		function addListeners() {
			editor.on( 'key', onKey );
			editor.on( 'contentDom', addPasteListenersToEditable );

			// For improved performance, we're checking the readOnly state on selectionChange instead of hooking a key event for that.
			editor.on( 'selectionChange', function( evt ) {
				inReadOnly = evt.data.selection.getRanges()[ 0 ].checkReadOnly();
				setToolbarStates();
			} );

			// If the "contextmenu" plugin is loaded, register the listeners.
			if ( editor.contextMenu ) {
				editor.contextMenu.addListener( function( element, selection ) {
					inReadOnly = selection.getRanges()[ 0 ].checkReadOnly();
					return {
						cut: stateFromNamedCommand( 'cut' ),
						copy: stateFromNamedCommand( 'copy' ),
						paste: stateFromNamedCommand( 'paste' )
					};
				} );

				// Adds 'touchend' integration with context menu paste item (#1347).
				var pasteListener = null;
				editor.on( 'menuShow', function() {
					// Remove previous listener.
					if ( pasteListener ) {
						pasteListener.removeListener();
						pasteListener = null;
					}

					// Attach new 'touchend' listeners to context menu paste items.
					var item = editor.contextMenu.findItemByCommandName( 'paste' );
					if ( item && item.element ) {
						pasteListener = item.element.on( 'touchend', function() {
							editor._.forcePasteDialog = true;
						} );
					}
				} );
			}

			// Detect if any of paste buttons was touched. In such case we assume that user is using
			// touch device and force displaying paste dialog (#595).
			if ( editor.ui.addButton ) {
				// Waiting for editor instance to be ready seems to be the most reliable way to
				// be sure that paste buttons are already created.
				editor.once( 'instanceReady', function() {
					if ( !editor._.pasteButtons ) {
						return;
					}

					CKEDITOR.tools.array.forEach( editor._.pasteButtons, function( name ) {
						var pasteButton = editor.ui.get( name );
						// Check if button was not removed by `removeButtons` config.
						if ( pasteButton ) {
							var buttonElement = CKEDITOR.document.getById( pasteButton._.id );

							if ( buttonElement ) {
								buttonElement.on( 'touchend', function() {
									editor._.forcePasteDialog = true;
								} );
							}
						}
					} );
				} );
			}
		}

		// Add events listeners to editable.
		function addPasteListenersToEditable() {
			var editable = editor.editable();

			if ( CKEDITOR.plugins.clipboard.isCustomCopyCutSupported ) {
				var initOnCopyCut = function( evt ) {
					// There shouldn't be anything to copy/cut when selection is collapsed (#869).
					if ( editor.getSelection().isCollapsed() ) {
						return;
					}

					// If user tries to cut in read-only editor, we must prevent default action (https://dev.ckeditor.com/ticket/13872).
					if ( !editor.readOnly || evt.name != 'cut' ) {
						clipboard.initPasteDataTransfer( evt, editor );
					}
					evt.data.preventDefault();
				};

				editable.on( 'copy', initOnCopyCut );
				editable.on( 'cut', initOnCopyCut );

				// Delete content with the low priority so one can overwrite cut data.
				editable.on( 'cut', function() {
					// If user tries to cut in read-only editor, we must prevent default action. (https://dev.ckeditor.com/ticket/13872)
					if ( !editor.readOnly ) {
						editor.extractSelectedHtml();
					}
				}, null, null, 999 );
			}

			// We'll be catching all pasted content in one line, regardless of whether
			// it's introduced by a document command execution (e.g. toolbar buttons) or
			// user paste behaviors (e.g. CTRL+V).
			editable.on( clipboard.mainPasteEvent, function( evt ) {
				if ( clipboard.mainPasteEvent == 'beforepaste' && preventBeforePasteEvent ) {
					return;
				}

				// If you've just asked yourself why preventPasteEventNow() is not here, but
				// in listener for CTRL+V and exec method of 'paste' command
				// you've asked the same question we did.
				//
				// THE ANSWER:
				//
				// First thing to notice - this answer makes sense only for IE,
				// because other browsers don't listen for 'paste' event.
				//
				// What would happen if we move preventPasteEventNow() here?
				// For:
				// * CTRL+V - IE fires 'beforepaste', so we prevent 'paste' and pasteDataFromClipboard(). OK.
				// * editor.execCommand( 'paste' ) - we fire 'beforepaste', so we prevent
				//		'paste' and pasteDataFromClipboard() and doc.execCommand( 'Paste' ). OK.
				// * native context menu - IE fires 'beforepaste', so we prevent 'paste', but unfortunately
				//		on IE we fail with pasteDataFromClipboard() here, because of... we don't know why, but
				//		we just fail, so... we paste nothing. FAIL.
				// * native menu bar - the same as for native context menu.
				//
				// But don't you know any way to distinguish first two cases from last two?
				// Only one - special flag set in CTRL+V handler and exec method of 'paste'
				// command. And that's what we did using preventPasteEventNow().

				pasteDataFromClipboard( evt );
			} );

			// It's not possible to clearly handle all four paste methods (ctrl+v, native menu bar
			// native context menu, editor's command) in one 'paste/beforepaste' event in IE.
			//
			// For ctrl+v & editor's command it's easy to handle pasting in 'beforepaste' listener,
			// so we do this. For another two methods it's better to use 'paste' event.
			//
			// 'paste' is always being fired after 'beforepaste' (except of weird one on opening native
			// context menu), so for two methods handled in 'beforepaste' we're canceling 'paste'
			// using preventPasteEvent state.
			//
			// 'paste' event in IE is being fired before getClipboardDataByPastebin executes its callback.
			//
			// QUESTION: Why didn't you handle all 4 paste methods in handler for 'paste'?
			//		Wouldn't this just be simpler?
			// ANSWER: Then we would have to evt.data.preventDefault() only for native
			//		context menu and menu bar pastes. The same with execIECommand().
			//		That would force us to mark CTRL+V and editor's paste command with
			//		special flag, other than preventPasteEvent. But we still would have to
			//		have preventPasteEvent for the second event fired by execIECommand.
			//		Code would be longer and not cleaner.
			if ( clipboard.mainPasteEvent == 'beforepaste' ) {
				editable.on( 'paste', function( evt ) {
					if ( preventPasteEvent ) {
						return;
					}

					// Cancel next 'paste' event fired by execIECommand( 'paste' )
					// at the end of this callback.
					preventPasteEventNow();

					// Prevent native paste.
					evt.data.preventDefault();

					pasteDataFromClipboard( evt );

					// Force IE to paste content into pastebin so pasteDataFromClipboard will work.
					execIECommand( 'paste' );
				} );

				// If mainPasteEvent is 'beforePaste' (IE before Edge),
				// dismiss the (wrong) 'beforepaste' event fired on context/toolbar menu open. (https://dev.ckeditor.com/ticket/7953)
				editable.on( 'contextmenu', preventBeforePasteEventNow, null, null, 0 );

				editable.on( 'beforepaste', function( evt ) {
					// Do not prevent event on CTRL+V and SHIFT+INS because it blocks paste (https://dev.ckeditor.com/ticket/11970).
					if ( evt.data && !evt.data.$.ctrlKey && !evt.data.$.shiftKey )
						preventBeforePasteEventNow();
				}, null, null, 0 );
			}

			editable.on( 'beforecut', function() {
				!preventBeforePasteEvent && fixCut( editor );
			} );

			var mouseupTimeout;

			// Use editor.document instead of editable in non-IEs for observing mouseup
			// since editable won't fire the event if selection process started within
			// iframe and ended out of the editor (https://dev.ckeditor.com/ticket/9851).
			editable.attachListener( CKEDITOR.env.ie ? editable : editor.document.getDocumentElement(), 'mouseup', function() {
				mouseupTimeout = setTimeout( function() {
					setToolbarStates();
				}, 0 );
			} );

			// Make sure that deferred mouseup callback isn't executed after editor instance
			// had been destroyed. This may happen when editor.destroy() is called in parallel
			// with mouseup event (i.e. a button with onclick callback) (https://dev.ckeditor.com/ticket/10219).
			editor.on( 'destroy', function() {
				clearTimeout( mouseupTimeout );
			} );

			editable.on( 'keyup', setToolbarStates );
		}

		// Create object representing Cut or Copy commands.
		function createCutCopyCmd( type ) {
			return {
				type: type,
				canUndo: type == 'cut', // We can't undo copy to clipboard.
				startDisabled: true,
				fakeKeystroke: type == 'cut' ? CKEDITOR.CTRL + 88 /*X*/ :  CKEDITOR.CTRL + 67 /*C*/,
				exec: function() {
					// Attempts to execute the Cut and Copy operations.
					function tryToCutCopy( type ) {
						if ( CKEDITOR.env.ie )
							return execIECommand( type );

						// non-IEs part
						try {
							// Other browsers throw an error if the command is disabled.
							return editor.document.$.execCommand( type, false, null );
						} catch ( e ) {
							return false;
						}
					}

					this.type == 'cut' && fixCut();

					var success = tryToCutCopy( this.type );

					if ( !success ) {
						// Show cutError or copyError.
						editor.showNotification( editor.lang.clipboard[ this.type + 'Error' ] ); // jshint ignore:line
					}

					return success;
				}
			};
		}

		function createPasteCmd() {
			return {
				// Snapshots are done manually by editable.insertXXX methods.
				canUndo: false,
				async: true,
				fakeKeystroke: CKEDITOR.CTRL + 86 /*V*/,

				/**
				 * The default implementation of the paste command.
				 *
				 * @private
				 * @param {CKEDITOR.editor} editor An instance of the editor where the command is being executed.
				 * @param {Object/String} data If `data` is a string, then it is considered content that is being pasted.
				 * Otherwise it is treated as an object with options.
				 * @param {Boolean/String} [data.notification=true] Content for a notification shown after an unsuccessful
				 * paste attempt. If `false`, the notification will not be displayed. This parameter was added in 4.7.0.
				 * @param {String} [data.type='html'] The type of pasted content. There are two allowed values:
				 * * 'html'
				 * * 'text'
				 * @param {String/Object} data.dataValue Content being pasted. If this parameter is an object, it
				 * is supposed to be a `data` property of the {@link CKEDITOR.editor#paste} event.
				 * @param {CKEDITOR.plugins.clipboard.dataTransfer} data.dataTransfer Data transfer instance connected
				 * with the current paste action.
				 * @member CKEDITOR.editor.commands.paste
				 */
				exec: function( editor, data ) {
					data = typeof data !== 'undefined' && data !== null ? data : {};

					var cmd = this,
						notification = typeof data.notification !== 'undefined' ? data.notification : true,
						forcedType = data.type,
						keystroke = CKEDITOR.tools.keystrokeToString( editor.lang.common.keyboard,
							editor.getCommandKeystroke( this ) ),
						msg = typeof notification === 'string' ? notification : editor.lang.clipboard.pasteNotification
							.replace( /%1/, '<kbd aria-label="' + keystroke.aria + '">' + keystroke.display + '</kbd>' ),
						pastedContent = typeof data === 'string' ? data : data.dataValue;

					function callback( data, withBeforePaste ) {
						withBeforePaste = typeof withBeforePaste !== 'undefined' ? withBeforePaste : true;

						if ( data ) {
							data.method = 'paste';

							if ( !data.dataTransfer ) {
								data.dataTransfer = clipboard.initPasteDataTransfer();
							}

							firePasteEvents( editor, data, withBeforePaste );
						} else if ( notification && !editor._.forcePasteDialog ) {
							editor.showNotification( msg, 'info', editor.config.clipboard_notificationDuration );
						}

						// Reset dialog mode (#595).
						editor._.forcePasteDialog = false;

						editor.fire( 'afterCommandExec', {
							name: 'paste',
							command: cmd,
							returnValue: !!data
						} );
					}

					// Force type for the next paste. Do not force if `config.forcePasteAsPlainText` set to true or 'allow-word' (#1013).
					if ( forcedType && editor.config.forcePasteAsPlainText !== true && editor.config.forcePasteAsPlainText !== 'allow-word' ) {
						editor._.nextPasteType = forcedType;
					} else {
						delete editor._.nextPasteType;
					}

					if ( typeof pastedContent === 'string' ) {
						callback( {
							dataValue: pastedContent
						} );
					} else {
						editor.getClipboardData( callback );
					}
				}
			};
		}

		function preventPasteEventNow() {
			preventPasteEvent = 1;
			// For safety reason we should wait longer than 0/1ms.
			// We don't know how long execution of quite complex getClipboardData will take
			// and in for example 'paste' listener execCommand() (which fires 'paste') is called
			// after getClipboardData finishes.
			// Luckily, it's impossible to immediately fire another 'paste' event we want to handle,
			// because we only handle there native context menu and menu bar.
			setTimeout( function() {
				preventPasteEvent = 0;
			}, 100 );
		}

		function preventBeforePasteEventNow() {
			preventBeforePasteEvent = 1;
			setTimeout( function() {
				preventBeforePasteEvent = 0;
			}, 10 );
		}

		// Tries to execute any of the paste, cut or copy commands in IE. Returns a
		// boolean indicating that the operation succeeded.
		// @param {String} command *LOWER CASED* name of command ('paste', 'cut', 'copy').
		function execIECommand( command ) {
			var doc = editor.document,
				body = doc.getBody(),
				enabled = false,
				onExec = function() {
					enabled = true;
				};

			// The following seems to be the only reliable way to detect that
			// clipboard commands are enabled in IE. It will fire the
			// onpaste/oncut/oncopy events only if the security settings allowed
			// the command to execute.
			body.on( command, onExec );

			// IE7: document.execCommand has problem to paste into positioned element.
			if ( CKEDITOR.env.version > 7 ) {
				doc.$.execCommand( command );
			} else {
				doc.$.selection.createRange().execCommand( command );
			}

			body.removeListener( command, onExec );

			return enabled;
		}

		// Cutting off control type element in IE standards breaks the selection entirely. (https://dev.ckeditor.com/ticket/4881)
		function fixCut() {
			if ( !CKEDITOR.env.ie || CKEDITOR.env.quirks )
				return;

			var sel = editor.getSelection(),
				control, range, dummy;

			if ( ( sel.getType() == CKEDITOR.SELECTION_ELEMENT ) && ( control = sel.getSelectedElement() ) ) {
				range = sel.getRanges()[ 0 ];
				dummy = editor.document.createText( '' );
				dummy.insertBefore( control );
				range.setStartBefore( dummy );
				range.setEndAfter( control );
				sel.selectRanges( [ range ] );

				// Clear up the fix if the paste wasn't succeeded.
				setTimeout( function() {
					// Element still online?
					if ( control.getParent() ) {
						dummy.remove();
						sel.selectElement( control );
					}
				}, 0 );
			}
		}

		// Allow to peek clipboard content by redirecting the
		// pasting content into a temporary bin and grab the content of it.
		function getClipboardDataByPastebin( evt, callback ) {
			var doc = editor.document,
				editable = editor.editable(),
				cancel = function( evt ) {
					evt.cancel();
				},
				blurListener;

			// Avoid recursions on 'paste' event or consequent paste too fast. (https://dev.ckeditor.com/ticket/5730)
			if ( doc.getById( 'cke_pastebin' ) )
				return;

			var sel = editor.getSelection();
			var bms = sel.createBookmarks();

			// https://dev.ckeditor.com/ticket/11384. On IE9+ we use native selectionchange (i.e. editor#selectionCheck) to cache the most
			// recent selection which we then lock on editable blur. See selection.js for more info.
			// selectionchange fired before getClipboardDataByPastebin() cached selection
			// before creating bookmark (cached selection will be invalid, because bookmarks modified the DOM),
			// so we need to fire selectionchange one more time, to store current seleciton.
			// Selection will be locked when we focus pastebin.
			if ( CKEDITOR.env.ie )
				sel.root.fire( 'selectionchange' );

			// Create container to paste into.
			// For rich content we prefer to use "body" since it holds
			// the least possibility to be splitted by pasted content, while this may
			// breaks the text selection on a frame-less editable, "div" would be
			// the best one in that case.
			// In another case on old IEs moving the selection into a "body" paste bin causes error panic.
			// Body can't be also used for Opera which fills it with <br>
			// what is indistinguishable from pasted <br> (copying <br> in Opera isn't possible,
			// but it can be copied from other browser).
			var pastebin = new CKEDITOR.dom.element(
				( CKEDITOR.env.webkit || editable.is( 'body' ) ) && !CKEDITOR.env.ie ? 'body' : 'div', doc );

			pastebin.setAttributes( {
				id: 'cke_pastebin',
				'data-cke-temp': '1'
			} );

			var containerOffset = 0,
				offsetParent,
				win = doc.getWindow();

			if ( CKEDITOR.env.webkit ) {
				// It's better to paste close to the real paste destination, so inherited styles
				// (which Webkits will try to compensate by styling span) differs less from the destination's one.
				editable.append( pastebin );
				// Style pastebin like .cke_editable, to minimize differences between origin and destination. (https://dev.ckeditor.com/ticket/9754)
				pastebin.addClass( 'cke_editable' );

				// Compensate position of offsetParent.
				if ( !editable.is( 'body' ) ) {
					// We're not able to get offsetParent from pastebin (body element), so check whether
					// its parent (editable) is positioned.
					if ( editable.getComputedStyle( 'position' ) != 'static' )
						offsetParent = editable;
					// And if not - safely get offsetParent from editable.
					else
						offsetParent = CKEDITOR.dom.element.get( editable.$.offsetParent );

					containerOffset = offsetParent.getDocumentPosition().y;
				}
			} else {
				// Opera and IE doesn't allow to append to html element.
				editable.getAscendant( CKEDITOR.env.ie ? 'body' : 'html', 1 ).append( pastebin );
			}

			pastebin.setStyles( {
				position: 'absolute',
				// Position the bin at the top (+10 for safety) of viewport to avoid any subsequent document scroll.
				top: ( win.getScrollPosition().y - containerOffset + 10 ) + 'px',
				width: '1px',
				// Caret has to fit in that height, otherwise browsers like Chrome & Opera will scroll window to show it.
				// Set height equal to viewport's height - 20px (safety gaps), minimum 1px.
				height: Math.max( 1, win.getViewPaneSize().height - 20 ) + 'px',
				overflow: 'hidden',
				// Reset styles that can mess up pastebin position.
				margin: 0,
				padding: 0
			} );

			// Paste fails in Safari when the body tag has 'user-select: none'. (https://dev.ckeditor.com/ticket/12506)
			if ( CKEDITOR.env.safari )
				pastebin.setStyles( CKEDITOR.tools.cssVendorPrefix( 'user-select', 'text' ) );

			// Check if the paste bin now establishes new editing host.
			var isEditingHost = pastebin.getParent().isReadOnly();

			if ( isEditingHost ) {
				// Hide the paste bin.
				pastebin.setOpacity( 0 );
				// And make it editable.
				pastebin.setAttribute( 'contenteditable', true );
			}
			// Transparency is not enough since positioned non-editing host always shows
			// resize handler, pull it off the screen instead.
			else {
				pastebin.setStyle( editor.config.contentsLangDirection == 'ltr' ? 'left' : 'right', '-10000px' );
			}

			editor.on( 'selectionChange', cancel, null, null, 0 );

			// Webkit fill fire blur on editable when moving selection to
			// pastebin (if body is used). Cancel it because it causes incorrect
			// selection lock in case of inline editor (https://dev.ckeditor.com/ticket/10644).
			// The same seems to apply to Firefox (https://dev.ckeditor.com/ticket/10787).
			if ( CKEDITOR.env.webkit || CKEDITOR.env.gecko )
				blurListener = editable.once( 'blur', cancel, null, null, -100 );

			// Temporarily move selection to the pastebin.
			isEditingHost && pastebin.focus();
			var range = new CKEDITOR.dom.range( pastebin );
			range.selectNodeContents( pastebin );
			var selPastebin = range.select();

			// If non-native paste is executed, IE will open security alert and blur editable.
			// Editable will then lock selection inside itself and after accepting security alert
			// this selection will be restored. We overwrite stored selection, so it's restored
			// in pastebin. (https://dev.ckeditor.com/ticket/9552)
			if ( CKEDITOR.env.ie ) {
				blurListener = editable.once( 'blur', function() {
					editor.lockSelection( selPastebin );
				} );
			}

			var scrollTop = CKEDITOR.document.getWindow().getScrollPosition().y;

			// Wait a while and grab the pasted contents.
			setTimeout( function() {
				// Restore main window's scroll position which could have been changed
				// by browser in cases described in https://dev.ckeditor.com/ticket/9771.
				if ( CKEDITOR.env.webkit )
					CKEDITOR.document.getBody().$.scrollTop = scrollTop;

				// Blur will be fired only on non-native paste. In other case manually remove listener.
				blurListener && blurListener.removeListener();

				// Restore properly the document focus. (https://dev.ckeditor.com/ticket/8849)
				if ( CKEDITOR.env.ie )
					editable.focus();

				// IE7: selection must go before removing pastebin. (https://dev.ckeditor.com/ticket/8691)
				sel.selectBookmarks( bms );
				pastebin.remove();

				// Grab the HTML contents.
				// We need to look for a apple style wrapper on webkit it also adds
				// a div wrapper if you copy/paste the body of the editor.
				// Remove hidden div and restore selection.
				var bogusSpan;
				if ( CKEDITOR.env.webkit && ( bogusSpan = pastebin.getFirst() ) && ( bogusSpan.is && bogusSpan.hasClass( 'Apple-style-span' ) ) )
					pastebin = bogusSpan;

				editor.removeListener( 'selectionChange', cancel );
				callback( pastebin.getHtml() );
			}, 0 );
		}

		// Try to get content directly on IE from clipboard, without native event
		// being fired before. In other words - synthetically get clipboard data, if it's possible.
		// mainPasteEvent will be fired, so if forced native paste:
		// * worked, getClipboardDataByPastebin will grab it,
		// * didn't work, dataValue and dataTransfer will be empty and editor#paste won't be fired.
		// Clipboard data can be accessed directly only on IEs older than Edge.
		// On other browsers we should fire beforePaste event and return false.
		function getClipboardDataDirectly() {
			if ( clipboard.mainPasteEvent == 'paste' ) {
				editor.fire( 'beforePaste', { type: 'auto', method: 'paste' } );
				return false;
			}

			// Prevent IE from pasting at the begining of the document.
			editor.focus();

			// Command will be handled by 'beforepaste', but as
			// execIECommand( 'paste' ) will fire also 'paste' event
			// we're canceling it.
			preventPasteEventNow();

			// https://dev.ckeditor.com/ticket/9247: Lock focus to prevent IE from hiding toolbar for inline editor.
			var focusManager = editor.focusManager;
			focusManager.lock();

			if ( editor.editable().fire( clipboard.mainPasteEvent ) && !execIECommand( 'paste' ) ) {
				focusManager.unlock();
				return false;
			}
			focusManager.unlock();

			return true;
		}

		// Listens for some clipbo