/*! Fabrik */
define(["jquery","fab/element","components/com_fabrik/libs/masked_input/jquery.maskedinput"],function(a,b){return window.FbField=new Class({Extends:b,options:{use_input_mask:!1,input_mask_definitions:""},initialize:function(b,c){var d;this.setPlugin("fabrikfield"),this.parent(b,c),this.options.use_input_mask&&(""!==this.options.input_mask_definitions&&(d=JSON.parse(this.options.input_mask_definitions),a.extend(a.mask.definitions,d)),a("#"+b).mask(this.options.input_mask)),this.options.geocomplete&&(this.gcMade=!1,this.loadFn=function(){this.gcMade===!1&&(a("#"+this.element.id).geocomplete(),this.gcMade=!0)}.bind(this),window.addEvent("google.geolocate.loaded",this.loadFn),Fabrik.loadGoogleMap(!1,"geolocateLoad"))},select:function(){var a=this.getElement();a&&this.getElement().select()},focus:function(){var a=this.getElement();a&&this.getElement().focus()},cloned:function(b){var c=this.getElement();if(this.options.use_input_mask&&c){if(""!==this.options.input_mask_definitions){var d=JSON.parse(this.options.input_mask_definitions);$H(d).each(function(b,c){a.mask.definitions[c]=b})}a("#"+c.id).mask(this.options.input_mask)}this.options.geocomplete&&c&&a("#"+c.id).geocomplete(),this.parent(b)}}),window.FbField});