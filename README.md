# wpmelb-nov

## Files to accompany my WordPress Melbourne meetup talk (Nov 2012) on WordPress Queries

At the WordPress Melbourne meetup (http://www.wpmelb.org/) in November 2012, I'll be presenting "WordPress Queries - The Right Way".
I'll be discussing the right way to change your templates main query, using the pre_get_posts hook, rather than creating a secondary 
query using query_posts() or even WP_Query().

These files are changes (& additions) that I made to the standard Twenty Twelve theme so as to provide a quick demonstration.

The code contains the pre_get_posts hook along with a sample Custom Post Type to show how easy it is to use with CPT's as well 
as "std" WordPress queries.

#Installation Notes

1. Add code within `functions.php` to the `functions.php` file in the standard WordPress Twenty Twelve theme
2. Add `archive-movie.php` and `content-movie.php` to the root Twenty Twelve folder
3. Activate Twenty Twelve theme

