/* 
 * EmailSpamProtection (jQuery Plugin)
 * Version: 1.0
 * Date: 2010 July
 * Author: Mike Unckel
 * Description and Demo: http://unckel.de/labs/jquery-plugin-email-spam-protection
 *
 * HTML: <span class="email">info [at] domain.com</span>
 * JavaScript: $("body").emailSpamProtection("email");
 *
 */

(function($) {
    $.fn.emailSpamProtection = function(className) {
        return $(this).find("." + className).each(function() {
            var $this = $(this);
            var wat = $this.text().replace(" [at] ", "<span>&#64;</span>");
            var wdot = wat.replace(" [dot] ", '<span>.</span>');
            $this.html("<a href=\"#" + $this.text() + "#\">" + wdot + "</a>");
        });
    };
})(jQuery);