=== WPAchievements Free ===
Contributors: netreviewde
Donate link: https://wpachievements.net
Tags: achievements, badges, gamification, quests, ranks, points, gamify
Requires at least: 4.6
Tested up to: 4.9
Stable tag: 1.2.0
Requires PHP: 5.4
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

WPAchievements is a powerful WordPress Achievements, Quests & Ranks Plugin. WordPress Gamification has never been so easy!

== Description ==

WPAchievements is a powerful WordPress Achievements, Quests & Ranks Plugins. It is a perfect extension for your WordPress powered website to improve your user's experiences and increase user interactivity. With WPAchievements you can create and manage user achievements, quests and ranks with ease. _WordPress Gamification_ has never been so easy!

WPAchievements Free is a fully functional but limited version of [WPAchievements](https://wpachievements.net).

Let's take a look at the features that WPAchievements (WordPress Gamification Plugin) provides:

* Add Achievements & Quests to your website for a wide range of activities
* Reward users with points when they gain Achievements & Quests
* Restrict content by gained achievements, solved quests or user ranks
* Responsive Custom Achievements Page
* Publish to a user's BuddyPress stream when they gain Achievements & Quests
* Achievements & Quests can be shared to users Facebook and Twitter
* Add and Manage Ranks to your website
* Limit Achievements to specific Ranks
* Publish to a user's BuddyPress stream when they gain a new Rank
* Lock content so only specific Ranks can view it
* Easily manage each of your users Achievements

=Custom Widgets=
WPAchievement comes with several useful widgets, so you can display leaderbords and user achievements everywhere. Following Widgets are available:

* _Leaderboard_: Shows a leaderboard of achievements gained by users.
* _My Achievements_: Shows a list of achievements gained by the user.
* _My Quests_: Shows a list of quests gained by the user.
* _My Rank_: Shows the current rank of the user.

= Custom Shortcodes =
You are able to use plugin shortcodes to show special and customized plugin output in posts and pages on your site. Following shortcodes are available:

* _[wpa_achievements]_ Copy this to any post/page to display all available achievements.
* _[wpa_quests]_ Copy this to any post/page to display all available quests.
* _[wpa_myachievements]_ Copy this to any post/page to display a list of achievement images that the user has gained.
* _[wpa_rank_achievements]_ Copy this to any post/page to display a list of achievement available for the choosen rank.
* _[wpa_myquests]_ Copy this to any post/page to display a list of quest images that the user has gained.
* _[wpa_myranks]_ Copy this to any post/page to display the current rank information of the user.
* _[wpa_mypoints]_ Copy this to any post/page to display user's points.
* _[wpa_leaderboard_list]_ Copy this to any post/page to display an unformatted leaderboard list.
* _[wpa_custom_achievement]_ Copy this to any post/page to trigger a custom achievement.
* _[wpa_leaderboard_widget]_ Copy this to any post/page to display a standard leaderboard.
* _[wpa_leaderboard]_ Copy this to any post/page to display an advanced leaderboard data table.
* _[wpa_activity_code]_ Copy this to any post/page to display an input form for activity code validation.
* _[wpa_quest_steps]_ Copy this to any post/page to display the interactive quest progress map.
* _[wpa_achievement]_ Displays an individual achievement description on any page or post.
* _[wpa_quest]_ Displays an individual quest description on any page or post.
* _[wpa_if_achievement]_ Conditional Shortcode - Display the content if the user has gained a certain achievement.
* _[wpa_if_quest]_ Conditional Shortcode - Display the content if the user has solved a certain quest.
* _[wpa_if_rank]_ Conditional Shortcode - Display the content if the user has reached a specific rank.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/wpachievements` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the WPAchievements->Settings screen to configure the plugin

== Changelog ==

= v1.2.0 - Based on WPAchievements v8.12.1 =
  - New: Award achievements to users directly from achievements edit page
  - New: Option to allow negative points
  - New: Shortcode to display user points [wpa_mypoints]
  - Tweak: Init hooks earlier to avoid conflicts with some Themes and Plugins
  - Tweak: Optimized CSS on quest edit page
  - Fix: Achievement and Quest points not awarded
  - Fix: Issue with User Pro when assigning achievements manually
  - Fix: Achievement parameter for 'wpachievements_admin_add_achievement' action

= v1.1.0 - Based on WPAchievements v8.11.2 =
  - New: Completely reimplemented and fully responsive [wpa_achievements] and [wpa_quests] outputs
  - Tweak: Shortcodes [wpa_myachievements] & [wpa_myquests] generates responsive output
  - Tweak: Widget "My Achievements" & "My Quests" generates responsive output
  - Tweak: Shortcode [wpa_achievements] & [wpa_quest] output message if no achievement or quest is available
  - Fix: Default setting value for achievement page
  - Fix: Display available Achievements and Quests on every page if "None" is selected
  - Fix: Undefined variable myranks if wpa_myrank shortcode is used without title
  - Fix: Can't hide title for wpa_myachievements shortcode

= v1.0.0 - Based on WPAchievements v8.10.0 =
  - Initial Version