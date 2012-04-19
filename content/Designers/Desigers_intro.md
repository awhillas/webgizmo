# Gizmo for Designers

WebGizmo, at the moment, uses simple [PHP/HTML templates](/Designers/Templates). There are a few examples in the /templates/html folder. A good example to start with is the /yui template (the default) which uses [Yahoo's YUI grid](http://developer.yahoo.com/yui/grids/) layout system. 

## Rapid prototyping with Auto-Includes

Gizmo will auto-include **CSS**, **Javascript** and **Fonts** placed in the right spot. Gizmo looks for the following folders and then will automatically include any files of the respective type (file extension) in that folder:

	/templates
		/html
			/example_theme
				/fonts
				/css
				/js

This can save you time dicking around including files in headers and lets you quickly mix-and-match CSS and Javascript files. 

This might come in handy if you have a library Javascript effect or different palettes of colour schemes in CSS you can quickly try them out and then remove them just as quickly.

### Note on Fonts

At the moment fonts are expected to be a [@font-face kit](http://www.fontsquirrel.com/fontface) from [fontsquirrel.com](http://fontsquirrel.com) which are "100% free for commercial use". Download @font-face kits and unzip them in the fonts folder. This should create a new folder with the kits goodies inside. Gizmo looks inside these folders for the `stylesheet.css` and auto-includes this. To use the font you just have to reference the font-family in your stylesheet.

## The Future

In the future design and layout will follow the example of the content interface and will mostly be managed via files and folders, thus allowing web designers to control layout, fonts, background images all though file/folder names.