<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<textarea name="editor1" id="editor1" rows="10" cols="80">
This is my textarea to be replaced with CKEditor.
</textarea>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="ckeditor.js"></script>
<script>
	CKEDITOR.replace( 'editor1',{
  							extraPlugins: 'notificationaggregator,notification,filetools,widgetselection,lineutils,widget,uploadwidget,base64image,btbutton,bootstrapVisibility,bootstrapTabs,btgrid,accordionList,collapsibleItem',
  							contentsCss: [ 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css','https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' ],
  							on: {
								instanceReady: loadBootstrap,
								mode: loadBootstrap
							} 
	});
	function loadBootstrap(event) {
		if (event.name == 'mode' && event.editor.mode == 'source')
		return;
		var jQueryScriptTag = document.createElement('script');
		var bootstrapScriptTag = document.createElement('script');
		bootstrapScriptTag.src = 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js';
		var editorHead = event.editor.document.$.head;
		editorHead.appendChild(jQueryScriptTag);
		jQueryScriptTag.onload = function() {
			editorHead.appendChild(bootstrapScriptTag);
		};
	}
</script>
</body>
</html>