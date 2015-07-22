# dentist-catalog
Wordpress Plugin for creating catalog of dentists

This plugin was test task for one of my jobs.
I needed develop catalog of dentists with ability to CRUD it from admin panel.
Also, each post should have custom fields (as Doctor name, adress, phone, email). Post have two states: "free" and "$". In first case doctor's email hidden.

After you create posts in admin panel you will be able list of them by url http://site-name.com/dentists

If you have 404 problem on custom post types, after creating custom post types with this plugin, then it is the issue of the URL rewriting.
Solution : Just go to settings->permalinks and click save chabges .
http://wordpress.org/extend/plugins/custom-post-type-cpt-cusom-taxonomy-ct-manager/
