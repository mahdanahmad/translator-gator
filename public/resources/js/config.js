app.factory('config', ['$http', '$state', function($http, $state) {
    return {
        baseColor       : "#E6E7E9",
        batteryRed      : 33,
        batteryYellow   : 66,
        batteryGreen    : 100,
        firstHint       : "<div>Welcome to Translator Gator!</div><div>By playing this game, you are <strong>supporting research on sustainable development and humanitarian action in Indonesia.</strong> For your support you will be rewarded with phone credit (pulsa).</div><div>Pulse Lab Jakarta is a joint project between United Nations and Government of Indonesia. We will share the translated dictionary with the public, as a social good. </div>",
        secondHint      : "<div><h3><strong>Four Missions</strong></h3></div><div><ul><li><strong>Translate</strong> English keywords into Indonesian including bahasa alay/gaul and local languages.</li><li><strong>Synonyms</strong> - provide alternative translations of words.</li><li><strong>Evaluate</strong> other players’ translations.</li><li><strong>Classify</strong> a given translation.</li></ul></div><div class='red-alert'>During the missions you can stop anytime you want, having a coffee break, but don’t forget to come back.</div><div><strong>IMPORTANT</strong> - We translate not only words listed in a dictionary but also <u>informal words</u> such as slang, jargon and abbreviated words, frequently used on social media.</div>",
        thirdHint1      : "<div><strong>The battery shows your lives. When you first login, you'll be given full lives.</strong></div>",
        thirdHint2      : "<div><strong>Please be careful in completing your missions!</strong></div><div>The battery shows your lives. Your lives will be gradually reduced whenever other players disagree with your translations. Once the battery life disappears you will be blocked from playing the game for <span class='red-alert'>2 hours</span>.</div><div><strong>TIP</strong> - It is better to <strong>skip a task when you are not sure of the answer.</strong></div>",
        fourthHint      : "<div><strong>Points & Rewards</strong></div><div>When you finish each task, you will earn points and can exchange the points for phone credit once or twice a month. You can earn more points by participating in our events - of which you will be informed.</div><div>1 point = IDR 3. For example: if you play for an hour, you'll be able to translate around 300 keywords and get IDR 10,000.</div>",
        fifthHint       : "<div><h3><strong>Redeem</strong></h3></div><div><ul><li>You will see the Redeem button on your profile page when you can redeem your points with phone credit.</li><li>You can only redeem your points for phone credit during the second and fourth week of each month. You will be given three days to send your request to the system.</li><li>Then you can decide whether you want to redeem your points or wait until you get a bigger score.</li><li>You can send credit to any mobile number.</li><li>Please allow 2 - 3 working days before the credit is added to your designated mobile number.</li></ul></div>",
        sixthHint       : "<div><h3><strong>WARNING</strong></h3></div><div><strong>Your account will be deleted permanently without prior notification should any suspicious activity be detected.</strong></div>",
        translateHead   : "Please translate English word to Bahasa <span class=\"capitalize\">((language))</span>",
        alternativeHead : "Please provide an alternative word of <span class=\"capitalize\">((translated))</span> in Bahasa <span class=\"capitalize\">((language))</span>",
        voteHead        : "Do you agree or disagree with below translation?",
        categorizeHead  : "Please categorize below word to four categories below",
        kicked_msg      : "<strong>Sorry that you've been kicked out from Translator Gator.</strong> <br /> This happens because too many people disagree with your translations. Please try again after ((time)) hours.",
        forgot_head     : "To reset your password, enter the email address you use to sign in to Translator Gator.",
        forgot_404      : "Your email has not been registered in our system.",
        forgot_message  : "An email is already sent to ((email))!<br />Please check your inbox.",
        reset_head      : "One step away! Input your new password.",
        unconfirm_head  : "Your account has been created",
        unconfirm_msg   : "<li>Thank you for registering with Translator Gator. A verification link has been sent to your registered email address. Please click on the link to activate your account.</li><li>IMPORTANT! Your account will not be activated until you verify your email address.</li>",
        fb_link         : "((baseURL))/auth/register?((referral))",
//        fb_picture      : "http://crowdsource.mahdanahmad.me/resources/img/sample-image.jpg",
        fb_picture      : "((baseURL))/fb-image.jpg",
        fb_name         : "Crowdsource Translator by Pulselab",
        fb_caption      : "I score ((point)) points at #TranslatorGator! Help #UN research and win rewards too! ((baseURL))/auth/register?((referral)) #crowdsource #SDGs",
        fb_description  : "I score ((point)) points at #TranslatorGator! Help #UN research and win rewards too! ((baseURL))/auth/register?((referral)) #crowdsource #SDGs",
        twitter_text    : "I score ((point)) points at %23TranslatorGator! Help %23UN research and win rewards too!",
        twitter_hashtag : "crowdsource,SDGs",
        twitter_url     : "((baseURL))%2Fauth%2Fregister%3F((referral))",
        path_client_id  : "07d40b8c9a99f0be23cf23095eba08080298a989",
        path_client_sct : "6b25bfa3cd29578b2877c152a4279e0195b331f8",
        redeem          : [10000, 25000, 50000, 100000],
    };
}]);
