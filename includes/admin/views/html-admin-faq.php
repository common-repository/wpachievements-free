<?php
/**
 * Admin View: Faq Page
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
?>
<div id="wpa_faq" class="wrap">
<h1 class="wpa_main_title">Frequently Asked Questions</h1>
<div class="wpa_pull_left">
  <h3>Purchase Code Issues</h3>
  <div class="wpa_faq_question">
    <h3 class="small_text" title="Click to view answer...">It says that my "Purchase Code" is invalid, what should I do?</h3>
    <div class="wpa_faq_answer">
      <p>Firstly, check that the purchase code you have entered is correct; it should look like this: xxxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx</p>
      <p>If the code is correct then follow these steps:</p>
      <ol>
        <li><span>Log in to your WordPress admin area</span></li>
        <li><span>Navigate to "Plugins" -> "Installed Plugins"</span></li>
        <li><span>Locate "WPAchievements" in your plugin list</span></li>
        <li><span>Click the "Check for updates" link</span></li>
        <li><span>If an update is available then navigate back to WPAchievements and click the "Update now" link</span></li>
      </ol>
    </div>
  </div>
  <div class="wpa_faq_question">
    <h3 title="Click to view answer...">It says that my "Purchase Code" is in use on another website, what should I do?</h3>
    <div class="wpa_faq_answer">
      <p>Firstly, If you wish to use WPAchievements on more then one website then you will need to purcase a new license for each website.</p>
      <p>If you have entered your purchase code on another website, such as a test install, and wish to move WPAchievements a new website, then simply follow these steps:</p>
      <ol>
        <li><span>Send an email trough the <a href="http://codecanyon.net/item/wpachievements-wordpress-achievements-plugin/4265703/support">contact form</a> that contains:</span>
          <ul>
            <li><span>Website URL that the purchase code is currently used on</span></li>
            <li><span>Website URL that you wish to use the purchase code on</span></li>
            <li><span>Your purchase code</span></li>
          </ul>
        </li>
        <li><span>Wait until you near from us and then follow these steps:</span>
          <ul>
            <li><span>Log in to your WordPress admin area</span></li>
            <li><span>Navigate to "Plugins" -> "Installed Plugins"</span></li>
            <li><span>Locate "WPAchievements" in your plugin list</span></li>
            <li><span>Click the "Check for updates" link</span></li>
            <li><span>If an update is available then navigate back to WPAchievements and click the "Update now" link</span></li>
          </ul>
        </li>
      </ol>
    </div>
  </div>
</div>
<div class="wpa_pull_left">
  <h3>LearnDash Issues</h3>
  <div class="wpa_faq_question">
    <h3 class="small_text" title="Click to view answer...">Achievements for specific quizzes that dont work, why?</h3>
    <div class="wpa_faq_answer">
      <p>If you have created achievements for specific quizzes then you have to make sure that you have entered the correct Quiz ID, this can be located by:</p>
      <ol>
        <li><span>The ID for advanced quizzes can be easily found by going to "Advanced Quiz" and looking at the "ID" column</span></li>
        <li><span>The ID for standard quizzes are more complicated, follow these steps:</span>
          <ul>
            <li><span>Navigate to "Quizzes"</span></li>
            <li><span>Find the quiz that you wish to get the ID for and click to "Edit" the quiz</span></li>
            <li><span>Look at the URL and you will see something like this: "post.php?post=1234&amp;action=edit"</span></li>
            <li><span>The quiz ID is the number that appears after "post.php?post=", in this example it is: 1234</span></li>
          </ul>
        </li>
      </ol>
    </div>
  </div>
  <div class="wpa_faq_question">
    <h3 title="Click to view answer...">When a user completes a quiz they do not get points or achievements until they move to another page, why?</h3>
    <div class="wpa_faq_answer">
      <p>LearnDash quizzes use AJAX to submit quiz results, this means that the results are handled in the background while the user stays on the same page. To overcome this WPAchievements has the ability to run "Automatic Checks", this enabled WPAchievements to check for achievements without needing a page to refresh.</p>
      <p>To activate WPAChievements "Automatic Checks", follow these steps:</p>
      <ol>
        <li><span>Log in to your WordPress admin area</span></li>
        <li><span>Navigate to "WPAchievements" -> "Settings"</span></li>
        <li><span>Click on the "Achievement Popup" tab</span></li>
        <li><span>Change the "Popup Automatic Checks" to the number of seconds that you wish WPAchievements to wait inbetween checks.</span></li>
        <li><span>Click "Save All Changes"</span></li>
      </ol>
      <p><strong>Important Note:</strong> Unless you have a powerful server, we recommend setting the time to around 10-15 seconds so that your server does not become overwhelmed.</p>
    </div>
  </div>
</div>
<div class="wpa_pull_left">
  <h3>Translation Issues</h3>
  <div class="wpa_faq_question">
    <h3 class="small_text" title="Click to view answer...">How to translate the plugin in my language?</h3>
    <div class="wpa_faq_answer">
      <p>Here an example to translate the plugin to german using Poedit:</p>
      <ol>
      <li><span>Install <a href="https://poedit.net/" target="_blank">Poedit</a>.</span></li>
      <li><span>Open Poedit and open the wpachievements language file in following folder: wpachievements/lang/wpachievements-en_EN.po</span></li>
      <li><span>A property box will pop up. Select the tranlsation language. In this example it will be german.</span></li>
      <li><span>Start translating all textlines an phrases.</span></li>
      <li><span>Go to Files-> Compile to MO...</span></li>
      <li><span>Save the new file to wpachievements/lang/ and name it for german: wpachievements-de_DE.mo</span></li>
      <li><span>Upload the new created file to your website via ftp.</span></li>

      <li><span>Here some additional tutorials:</span>
        <ul>
          <li><span><a href="https://premium.wpmudev.org/blog/how-to-translate-a-wordpress-plugin/" target="_blank">https://premium.wpmudev.org/blog/how-to-translate-a-wordpress-plugin/</a></span></li>
          <li><span><a href="https://www.hostinger.com/tutorials/wordpress/how-to-translate-wordpress-theme-using-poedit" target="_blank">https://www.hostinger.com/tutorials/wordpress/how-to-translate-wordpress-theme-using-poedit</a>
            </span></li>
          <li><span><a href="http://www.wpbeginner.com/wp-tutorials/how-to-translate-a-wordpress-plugin-in-your-language/" target="_blank">http://www.wpbeginner.com/wp-tutorials/how-to-translate-a-wordpress-plugin-in-your-language/</a></span></li>
        </ul>
      </li>
      </ol>
    </div>
  </div>
</div>
</div>
<div class="clear"></div>

<h2>Available Shortcodes</h2>
<div id="wpa_change_log_outter">
<div id="wpa_change_log">

  <h2 style="font-weight:bold;">My Achievements</h2>
  <p>Copy this to any post/page to display a list of achievement images that the user has gained. <code class="wpa_code_blue">[wpa_myachievements]</code></p>
  <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
  <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
    <thead>
      <tr>
        <th>Parameter</th>
        <th>Description</th>
      </tr>
    </thead>
    <tbody>
      <tr class="alternate">
        <th>user_id</th>
        <td class="wpa_doc_desc">The ID of the user to list achievement images for. If blank it defaults to current logged in user.</td>
      </tr>
      <tr>
        <th>show_title</th>
        <td class="wpa_doc_desc">Whether to display the title: "My Achievements". Example: "True" to show or "False" to hide. If blank it defaults to true.</td>
      </tr>
      <tr class="alternate">
        <th>title_class</th>
        <td class="wpa_doc_desc">This class will be added to the title and will allow the use of custom CSS.</td>
      </tr>
      <tr>
        <th>title_heading</th>
        <td class="wpa_doc_desc">Select the heading level of the title from h1 to h6.</td>
      </tr>
      <tr class="alternate">
        <th>image_holder_class</th>
        <td class="wpa_doc_desc">This class will be added to the achievement image holder and will allow the use of custom CSS.</td>
      </tr>
      <tr>
        <th>list_class</th>
        <td class="wpa_doc_desc">This class will be added to the achievement list holder and will allow the use of custom CSS.</td>
      </tr>
      <tr class="alternate">
        <th>list_element_class</th>
        <td class="wpa_doc_desc">This class will be added to the achievement list element in the list and will allow the use of custom CSS.</td>
      </tr>           
      <tr>
        <th>image_class</th>
        <td class="wpa_doc_desc">This class will be added to the achievement images in the list and will allow the use of custom CSS.</td>
      </tr>
      <tr class="alternate">
        <th>image_width</th>
        <td class="wpa_doc_desc">This is the width of each achievement image. Value needs to be in "px". Default is "30"</td>
      </tr>
      <tr>
        <th>achievement_limit</th>
        <td class="wpa_doc_desc">Limit the number of achievement images shown. If blank it will show all achievements available.</td>
      </tr>
    </tbody>
  </table>
  <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
  <pre class="wpa_code wpa_code_green">[wpa_myachievements user_id="1" show_title="true" achievement_limit="30"]</pre>
  <pre class="wpa_code wpa_code_green">[wpa_myachievements user_id="2" show_title="false" image_width="20" achievement_limit="10"]</pre>
  <div class="wpa_shortcode_sep"></div>

  <h2 style="font-weight:bold;">Achievements by Rank</h2>
  <p>Copy this to any post/page to display a list of achievement available for the choosen rank. <code class="wpa_code_blue">[wpa_rank_achievements]</code></p>
  <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
  <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
    <thead>
      <tr>
        <th>Parameter</th>
        <th>Description</th>
      </tr>
    </thead>
    <tbody>
      <tr class="alternate">
        <th>user_id</th>
        <td class="wpa_doc_desc">The ID of the user to get the rank to list achievement images for. If blank "rank" parameter will be used.</td>
      </tr>
      <tr>
        <th>rank</th>
        <td class="wpa_doc_desc">The rank to list achievement images for. If blank achievements will not be shown.</td>
      </tr>
      <tr class="alternate">
        <th>show_title</th>
        <td class="wpa_doc_desc">Whether to display the title: "My Achievements". Example: "True" to show or "False" to hide. If blank it defaults to true.</td>
      </tr>
      <tr>
        <th>title_class</th>
        <td class="wpa_doc_desc">This class will be added to the title and will allow the use of custom CSS.</td>
      </tr>
      <tr class="alternate">
        <th>image_holder_class</th>
        <td class="wpa_doc_desc">This class will be added to the achievement image holder and will allow the use of custom CSS.</td>
      </tr>
      <tr>
        <th>image_class</th>
        <td class="wpa_doc_desc">This class will be added to the achievement images in the list and will allow the use of custom CSS.</td>
      </tr>
      <tr class="alternate">
        <th>image_width</th>
        <td class="wpa_doc_desc">This is the width of each achievement image. Value needs to be in "px". Default is "30"</td>
      </tr>
      <tr>
        <th>achievement_limit</th>
        <td class="wpa_doc_desc">Limit the number of achievement images shown. If blank it will show all achievements available.</td>
      </tr>
    </tbody>
  </table>
  <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
  <pre class="wpa_code wpa_code_green">[wpa_rank_achievements rank="Newbie" show_title="true" achievement_limit="30"]</pre>
  <pre class="wpa_code wpa_code_green">[wpa_rank_achievements user_id="1" show_title="false" image_width="20" achievement_limit="10"]</pre>
  <div class="wpa_shortcode_sep"></div>

  <h2 style="font-weight:bold;">My Quests</h2>
  <p>Copy this to any post/page to display a list of quest images that the user has gained. <code class="wpa_code_blue">[wpa_myquests]</code></p>
  <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
  <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
    <thead>
      <tr>
        <th>Parameter</th>
        <th>Description</th>
      </tr>
    </thead>
    <tbody>
      <tr class="alternate">
        <th>user_id</th>
        <td class="wpa_doc_desc">The ID of the user to list quest images for. If blank it defaults to current logged in user.</td>
      </tr>
      <tr>
        <th>show_title</th>
        <td class="wpa_doc_desc">Whether to display the title: "My Quests". Example: "True" to show or "False" to hide. If blank it defaults to true.</td>
      </tr>
      <tr class="alternate">
        <th>title_class</th>
        <td class="wpa_doc_desc">This class will be added to the title and will allow the use of custom CSS.</td>
      </tr>
      <tr>
        <th>title_heading</th>
        <td class="wpa_doc_desc">Select the heading level of the title from h1 to h6.</td>
      </tr>
      <tr class="alternate">
        <th>image_holder_class</th>
        <td class="wpa_doc_desc">This class will be added to the achievement image holder and will allow the use of custom CSS.</td>
      </tr>
      <tr>
        <th>list_class</th>
        <td class="wpa_doc_desc">This class will be added to the achievement list holder and will allow the use of custom CSS.</td>
      </tr>
      <tr class="alternate">
        <th>list_element_class</th>
        <td class="wpa_doc_desc">This class will be added to the achievement list element in the list and will allow the use of custom CSS.</td>
      </tr>           
      <tr>
        <th>image_class</th>
        <td class="wpa_doc_desc">This class will be added to the achievement images in the list and will allow the use of custom CSS.</td>
      </tr>
      <tr class="alternate">
        <th>image_width</th>
        <td class="wpa_doc_desc">This is the width of each achievement image. Value needs to be in "px". Default is "30"</td>
      </tr>
      <tr>
        <th>achievement_limit</th>
        <td class="wpa_doc_desc">Limit the number of achievement images shown. If blank it will show all achievements available.</td>
      </tr>
    </tbody>
  </table>
  <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
  <pre class="wpa_code wpa_code_green">[wpa_myquests user_id="1" show_title="false" image_width="30" quest_limit="30"]</pre>
  <pre class="wpa_code wpa_code_green">[wpa_myquests user_id="2" show_title="true" image_class="custom_image_class" quest_limit="10"]</pre>
  <div class="wpa_shortcode_sep"></div>

  <h2 style="font-weight:bold;">My Rank</h2>
  <p>Copy this to any post/page to display the current rank information of the user. <code class="wpa_code_blue">[wpa_myranks]</code></p>
  <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
  <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
    <thead>
      <tr>
        <th>Parameter</th>
        <th>Description</th>
      </tr>
    </thead>
    <tbody>
      <tr class="alternate">
        <th>user_id</th>
        <td class="wpa_doc_desc">The ID of the user to get the rank for. If blank it defaults to current logged in user.</td>
      </tr>
      <tr>
        <th>rank_image</th>
        <td class="wpa_doc_desc">Whether to show the rank image, if one is available. "True" to show or "False" to hide. If blank it defaults to true.</td>
      </tr>
      <tr class="alternate">
        <th>show_title</th>
        <td class="wpa_doc_desc">Whether to display the title: "My Rank". Example: "True" to show or "False" to hide. If blank it defaults to true.</td>
      </tr>
      <tr>
        <th>title_class</th>
        <td class="wpa_doc_desc">This class will be added to the title and will allow the use of custom CSS.</td>
      </tr>
    </tbody>
  </table>
  <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
  <pre class="wpa_code wpa_code_green">[wpa_myranks user_id="1" show_title="false" rank_image="true"]</pre>
  <pre class="wpa_code wpa_code_green">[wpa_myranks user_id="2" show_title="true" rank_image="false" title_class="custom_title_class"]</pre>
  <div class="wpa_shortcode_sep"></div>

 <h2 style="font-weight:bold;">My Points</h2>
  <p>Copy this to any post/page to display the current user points. <code class="wpa_code_blue">[wpa_mypoints]</code></p>
  <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
  <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
    <thead>
      <tr>
        <th>Parameter</th>
        <th>Description</th>
      </tr>
    </thead>
    <tbody>
      <tr class="alternate">
        <th>user_id</th>
        <td class="wpa_doc_desc">The ID of the user to get the points for. If blank it defaults to current logged in user.</td>
      </tr>
    </tbody>
  </table>
  <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
  <pre class="wpa_code wpa_code_green">[wpa_mypoints]</pre>
  <pre class="wpa_code wpa_code_green">[wpa_myranks user_id="2"]</pre>
  <div class="wpa_shortcode_sep"></div>

  <h2 style="font-weight:bold;">Interactive Quest Steps</h2>
  <p>Copy this to any post/page to display the quest progress for a logged in user. This will show the user which steps he needs to fulfill in order to complete the quest. <code class="wpa_code_blue">[wpa_quest_steps]</code></p>
  <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
  <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
    <thead>
      <tr>
        <th>Parameter</th>
        <th>Description</th>
      </tr>
    </thead>
    <tbody>
      <tr class="alternate">
        <th>quest_id</th>
        <td class="wpa_doc_desc">The ID of the quest. This parameter can't be empty.</td>
      </tr>
      <tr>
        <th>show_title</th>
        <td class="wpa_doc_desc">Whether to display the title: "My Quests". Example: "True" to show or "False" to hide. If blank it defaults to true.</td>
      </tr>
      <tr class="alternate">
        <th>title_class</th>
        <td class="wpa_doc_desc">This class will be added to the title and will allow the use of custom CSS.</td>
      </tr>
       <tr>
        <th>limit_rank</th>
        <td class="wpa_doc_desc">Limit visibility of the quest progress to user rank. Example: "True" to limit or "False" for no limitation. If blank it defaults to false.</td>
      </tr>
    </tbody>
  </table>
  <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
  <pre class="wpa_code wpa_code_green">[wpa_quest_steps quest_id="1" show_title="false" limit_rank="false"]</pre>
  <pre class="wpa_code wpa_code_green">[wpa_myquests quest_id="2"]</pre>
  <div class="wpa_shortcode_sep"></div>

  <h2 style="font-weight:bold;">Unformatted Leaderboard List</h2>
  <p>Copy this to any post/page to display an unformatted leaderboard list. <code class="wpa_code_blue">[wpa_leaderboard_list]</code></p>
  <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
  <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
    <thead>
      <tr>
        <th>Parameter</th>
        <th>Description</th>
      </tr>
    </thead>
    <tbody>
      <tr class="alternate">
        <th>user_position</th>
        <td class="wpa_doc_desc">Whether to show the trophy icons/place numbering. Example: "True" to show or "False" to hide. If blank it defaults to true.</td>
      </tr>
      <tr>
        <th>user_ranking</th>
        <td class="wpa_doc_desc">Whether to show the users rank information. Example: "True" to show or "False" to hide. If blank it defaults to true.</td>
      </tr>
      <tr class="alternate">
        <th>type</th>
        <td class="wpa_doc_desc">Whether to order the leaderboard by amount of points or achievements. Example: "Points" or "Achievements". If blank it defaults to Achievements.</td>
      </tr>
      <tr>
        <th>limit</th>
        <td class="wpa_doc_desc">Limit the number of users shown. If blank it will show all users available.</td>
      </tr>
      <tr>
        <th>list_class</th>
        <td class="wpa_doc_desc">This class will be added to the leaderboard list and will allow the use of custom CSS.</td>
      </tr>
    </tbody>
  </table>
  <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
  <pre class="wpa_code wpa_code_green">[wpa_leaderboard_list user_position="true" user_ranking="false" type="points" limit="10"]</pre>
  <pre class="wpa_code wpa_code_green">[wpa_leaderboard_list user_position="false" user_ranking="true" type="achievements" limit="10"]</pre>
  <div class="wpa_shortcode_sep"></div>

  <h2 style="font-weight:bold;">Custom Achievement Trigger</h2>
  <p>Copy this to any post/page to trigger a custom achievement. <code class="wpa_code_blue">[wpa_custom_achievement]</code></p>
  <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
  <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
    <thead>
      <tr>
        <th>Parameter</th>
        <th>Description</th>
      </tr>
    </thead>
    <tbody>
      <tr class="alternate">
        <th>trigger_id</th>
        <td class="wpa_doc_desc">This is the unique "Trigger ID" that is used when creating the custom achievement in the "Achievements" area.</td>
      </tr>
      <tr>
        <th>type</th>
        <td class="wpa_doc_desc">Whether to produce a button or trigger the achievement when the post/page loads. Example: "Button" or "Instant". If blank it defaults to Button.</td>
      </tr>
      <tr class="alternate">
        <th>text</th>
        <td class="wpa_doc_desc">If the type "Button" is choosen then this text is displayed within the button.</td>
      </tr>
    </tbody>
  </table>
  <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
  <pre class="wpa_code wpa_code_green">[wpa_custom_achievement trigger_id="unique_trigger_id" type="button" text="Click for Achievement"]</pre>
  <pre class="wpa_code wpa_code_green">[wpa_custom_achievement trigger_id="unique_trigger_id" type="instant"]</pre>
  <div class="wpa_shortcode_sep"></div>

  <h2 style="font-weight:bold;">Standard Leaderboard</h2>
  <p>Copy this to any post/page to display a standard leaderboard. <code class="wpa_code_blue">[wpa_leaderboard_widget]</code></p>
  <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
  <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
    <thead>
      <tr>
        <th>Parameter</th>
        <th>Description</th>
      </tr>
    </thead>
    <tbody>
      <tr class="alternate">
        <th>type</th>
        <td class="wpa_doc_desc">Whether to order the leaderboard by amount of points or achievements. Example: "Points" or "Achievements". If blank it defaults to Achievements.</td>
      </tr>
      <tr>
        <th>limit</th>
        <td class="wpa_doc_desc">Limit the number of users shown. If blank it will show all users available.</td>
      </tr>
    </tbody>
  </table>
  <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
  <pre class="wpa_code wpa_code_green">[wpa_leaderboard_widget type="points" limit="10"]</pre>
  <pre class="wpa_code wpa_code_green">[wpa_leaderboard_widget type="achievements" limit="10"]</pre>
  <div class="wpa_shortcode_sep"></div>

  <h2 style="font-weight:bold;">Leaderboard Data Table</h2>
  <p>Copy this to any post/page to display an advanced leaderboard data table. <code class="wpa_code_blue">[wpa_leaderboard]</code></p>
  <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
  <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
    <thead>
      <tr>
        <th>Parameter</th>
        <th>Description</th>
      </tr>
    </thead>
    <tbody>
      <tr class="alternate">
        <th>position_numbers</th>
        <td class="wpa_doc_desc">Whether to show leaderboard position numbering. Example: "True" to show or "False" to hide. If blank it defaults to true.</td>
      </tr>
      <tr>
        <th>columns</th>
        <td class="wpa_doc_desc">Select which columns to display. Available Inputs: avatar,points,rank,achievements,quests. If blank it defaults to true.</td>
      </tr>
      <tr class="alternate">
        <th>limit</th>
        <td class="wpa_doc_desc">Limit the number of users shown. If blank it will show all users available.</td>
      </tr>
      <tr>
        <th>achievement_limit</th>
        <td class="wpa_doc_desc">Limit the number of achievements shown. If blank it will show all achievements available.</td>
      </tr>
      <tr class="alternate">
        <th>quest_limit</th>
        <td class="wpa_doc_desc">Limit the number of quests shown. If blank it will show all quests available.</td>
      </tr>
      <tr>
        <th>list_class</th>
        <td class="wpa_doc_desc">This class will be added to the leaderboard list and will allow the use of custom CSS.</td>
      </tr>
    </tbody>
  </table>
  <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
  <pre class="wpa_code wpa_code_green">[wpa_leaderboard position_numbers="true" achievement_limit="10" quest_limit="10" limit="10" columns="avatar,points,rank,achievements,quests"]</pre>
  <pre class="wpa_code wpa_code_green">[wpa_leaderboard position_numbers="false" limit="10" list_class="my_custom_class" columns="avatar,points,achievements"]</pre>
  <div class="wpa_shortcode_sep"></div>

  <h2 style="font-weight:bold;">Our Achievements</h2>
  <p>Copy this to any post/page to display all available achievements. <code class="wpa_code_blue">[wpa_achievements]</code></p>
  <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
  <pre class="wpa_code wpa_code_green">[wpa_achievements]</pre>
  <div class="wpa_shortcode_sep"></div>

  <h2 style="font-weight:bold;">Our Quests</h2>
  <p>Copy this to any post/page to display all available quests. <code class="wpa_code_blue">[wpa_quests]</code></p>
  <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
  <pre class="wpa_code wpa_code_green">[wpa_quests]</pre>
  <div class="wpa_shortcode_sep"></div>

  <h2 style="font-weight:bold;">Single Achievement / Quest</h2>
  <p>Show a certain achievement by ID on any page or post. <code class="wpa_code_blue">[wpa_achievement] and [wpa_quest]</code></p>
  <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
  <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
    <thead>
      <tr>
        <th>Parameter</th>
        <th>Description</th>
      </tr>
    </thead>
    <tbody>
      <tr class="alternate">
        <th>post_id</th>
        <td class="wpa_doc_desc">The Post ID of an achievement or quest.</td>
      </tr>
      <tr>
        <th>show_title</th>
        <td class="wpa_doc_desc">Set "true" in order to display the title of an achievement or quest.</td>
      </tr>
      <tr class="alternate">
        <th>show_description</th>
        <td class="wpa_doc_desc">Whether to display the achievement/quest description (post content). If blank it defaults to true.</td>
      </tr>
      <tr>
        <th>show_image</th>
        <td class="wpa_doc_desc">Whether to display the achievement/quest badge. If blank it defaults to true.</td>
      </tr>
      <tr class="alternate">
        <th>show_trigger</th>
        <td class="wpa_doc_desc">Whether to display the achievement trigger or required quest steps. If blank it defaults to true.</td>
      </tr>
      <tr>
        <th>trigger_title</th>
        <td class="wpa_doc_desc">Set the title for required steps to gain this achievement or quest.</td>
      </tr>
    </tbody>
  </table>
  <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
  <div>Reaplce the post id with your correct post ID for an achievement or quest.</div>
  <pre class="wpa_code wpa_code_green">[wpa_achievement post_id="123" show_title="true" show_description="true" show_image="true" show_trigger="true" trigger_title="How to gain this achievement?"]</pre>
  <pre class="wpa_code wpa_code_green">[wpa_quest post_id="456" show_title="true" show_description="true" show_image="true" show_trigger="true" trigger_title="How to solve this quest?"]</pre>
  <div class="wpa_shortcode_sep"></div>

  <h2 style="font-weight:bold;">Conditional Shortcodes</h2>
  <p>The conditional shortcodes allow you to display content or content parts dependent of gaines achievements, solved quests or user rank. <code class="wpa_code_blue">[wpa_if_achievement] or [wpa_if_quest] or [wpa_if_rank]</code></p>
  <h4 style="font-weight:bold;margin-bottom:5px;">Available Parameters</h4>
  <table class="wpa_doc_params" cellspacing="0" cellpadding="0">
    <thead>
      <tr>
        <th>Parameter</th>
        <th>Description</th>
      </tr>
    </thead>
    <tbody>
      <tr class="alternate">
        <th>post_id</th>
        <td class="wpa_doc_desc">The Post ID of an achievement or quest. (Not applicable for wpa_if_rank shortcode)</td>
      </tr>
      <tr>
        <th>rank</th>
        <td class="wpa_doc_desc">The minimal rank required to view the content. (Only for wpa_if_rank.)</td>
      </tr>
      <tr class="alternate">
        <th>condition</th>
        <td class="wpa_doc_desc">Wheater the exact rank is required or minimual rank. Values: equal or minimal. (Only for wpa_if_rank.)</td>
      </tr>
    </tbody>
  </table>
  <h4 style="font-weight:bold;margin-bottom:5px;">Examples</h4>
  <div>Display content only if user has gained an achievement. Otherwise display the alternative content:</div>
  <pre class="wpa_code wpa_code_green">[wpa_if_achievement post_id="123"]<br />Display this if user has gained the achievement.<br />[wpa_else_achivement]<br />Otherwise display this content.<br />[/wpa_if_achievement]</pre>
  <div>Display content only if user has gaisolved a quest. Otherwise display the alternative content:</div>
  <pre class="wpa_code wpa_code_green">[wpa_if_quest post_id="123"]<br />Display this if user has gained the achievement.<br />[wpa_else_quest]<br />Otherwise display this content.<br />[/wpa_if_quest]</pre>
  <div>Display content only if user has a rank. Otherwise display the alternative content:</div>
  <pre class="wpa_code wpa_code_green">[wpa_if_rank rank="Expert" condition="equal"]<br />Display this if user has the rank Expert.<br />[wpa_else_rank]<br />Otherwise display this content.<br />[/wpa_if_rank]</pre>
  <div class="wpa_shortcode_sep"></div>
</div>
</div>
