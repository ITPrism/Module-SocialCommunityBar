;(function ( $, window, document, undefined ) {

	"use strict";
	
    // Create the defaults once
    let pluginName = "SocialCommunityBar",
        defaults = {
            resultsLimit: 5
        },
        popoverClicked = false;

    // The actual plugin constructor
    function SocialCommunityBar(element, options) {
        this.element = element;

        this.options = $.extend({}, defaults, options);

        this._defaults = defaults;
        this._name     = pluginName;

        this._numberContainer  = $(this.element).find('#js-sc-ntfy-number');
        this._contentContainer = $(this.element).find('#js-sc-ntfy-content');
        this.init();
        
        this.displayNumber();
    }

    SocialCommunityBar.prototype = {

        init: function() {
        	
        	let self = this;
            
        	$(this.element).on("click", function(event) {
        		event.preventDefault();
        		
        		let timestamp     = new Date().getTime();
        		let resultsLimit  = parseInt(self.options.resultsLimit);
        		
        		$(self._contentContainer).unbind("click");
        		
        		if(!self.popoverClicked) {
        			$.ajax({
        				type: "get",
        				url: "index.php?option=com_socialcommunity&format=raw&view=notifications&layout=raw&t="+timestamp+"&rl="+resultsLimit,
        				dataType: "html"
        			}).done(function(response){
        				
        				$(self._contentContainer).popover({
        					html: true,
        					placement: "bottom",
        					content: response,
        					container: "body"
        				}).popover('show');

        				self.popoverClicked = true;
        			    
        			});
        		} else {
        			self._contentContainer.popover('destroy');
        			self.popoverClicked = false;
        		}

        	});
        	
        },

        displayNumber: function(element, options) {
        	
        	let self = this;
        	
        	$.ajax({
        		type: "GET",
        		url: "index.php?option=com_socialcommunity&format=raw&task=notifications.getNumber",
        		dataType: "text json"
        	}).done(function(response){
        		
        		let results = parseInt(response.data.results);
        		
        		if(results > 0) {
        			$(self._numberContainer).text(results).show();
        			let title = $(document).attr("title");
        			
        			$(document).attr("title", "("+ results + ") "+ title) ;
        		}
        	});
        	
        }
    };

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new SocialCommunityBar( this, options ));
            }
        });
    };

})(jQuery, window, document);

jQuery(document).ready(function() {
    let options = Joomla.getOptions('mod_socialcommunitybar');
    jQuery("#js-sc-ntfy").SocialCommunityBar({
        resultsLimit: options.limit
    });
});