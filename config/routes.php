<?php

route()->get('/demo', 'DemoController@demo')->withAddMiddleware("filter");
route()->get('/', 'WelcomeController@welcome');
route()->get('/hello/foo', 'WelcomeController@sayHello');

route()->post('/test/rsa', 'RSAController@test')->withAddMiddleware(['rsa','verify','reply']);

route()->get("/login", 'LoginController@get')->withAddMiddleware("dispatch");

//比赛列表页
route()->group(["prefix"=>"/match","middleware" => "dispatch"], function(){
    route()->get("/list", 'MatchListController@fetchMatchList');
    route()->post("/collect", 'MatchListController@collectionMatch')->withAddMiddleware("login");
    route()->delete("/collect", 'MatchListController@collectionMatchCancel')->withAddMiddleware("login");
    route()->get("/league/list", 'MatchListController@fetchLeague');
});

//比赛详情页
route()->group(["prefix"=>"/match","middleware" => "dispatch"], function(){
    route()->get("/detail", 'MatchDetailController@fetchMatchDetail');
    route()->get("/advices", 'MatchDetailController@fetchMatchAdvices');
    route()->get("/recommendlist", 'MatchDetailController@fetchrecommendList');
    route()->post("/give/ticket", 'MatchDetailController@giveTicket')->withAddMiddleware("login");
});

//比赛收藏页
route()->group(["prefix"=>"/match_collect","middleware" => "dispatch"], function(){
    route()->get("/list", 'MatchCollectionController@fetchAction')->withAddMiddleware("login");
});

//首页
route()->group(["prefix" => "/index", "middleware" => "dispatch"], function(){
    route()->get("/banner", 'IndexController@banner');
    route()->get("/top_line", 'IndexController@topLine');
    route()->get("/adventure", 'IndexController@adventure');
    route()->get("/recommend", 'IndexController@recommend');
    route()->get("/user_info", 'IndexController@userInfo')->withAddMiddleware("login");
    route()->get("/ticket_rank", 'IndexController@ticketRank');
    route()->get("/hit_rate_rank", 'IndexController@hitRateRank');
});

//分析师
route()->group(["prefix" => "/analyst", "middleware" => "dispatch"], function(){
    route()->get("/detail", 'AnalystController@fetchAnalystInfo');
    route()->get("/recommendlist", 'AnalystController@fetchAnalystRecommendList');
    route()->post("/follow", 'AnalystController@analystFollow')->withAddMiddleware("login");
    route()->post("/unfollow", 'AnalystController@analystUnfollow')->withAddMiddleware("login");
});

//发推荐选择页
route()->group(["prefix" => "/recommend", "middleware" => "dispatch"], function(){
    route()->get("/match_list", 'RecommendMatchChoseController@matchList');
    route()->get("/odd", 'RecommendController@matchInfo')->withAddMiddleware("login");
    route()->post("/add", 'RecommendController@addRecommend')->withAddMiddleware("login");
    route()->get("/detail", 'RecommendController@RecommendDetail');

    //更多推荐
    route()->get("/list", 'RecommendController@fetchRecommendList');

    //获取当前所有推荐的联赛列表
    route()->get("/filter", 'RecommendController@filterLeagueAndMatch');
});

//注册页
route()->group(["prefix" => "/register", "middleware" => "dispatch"], function(){
    route()->get("/code/send", 'RegisterController@sendCode');
    route()->post("/code/valid", 'RegisterController@validCode');
    route()->post("/info", 'RegisterController@addInfo')->withAddMiddleware("login");
});

//购买
route()->group(["prefix" => "/buy", "middleware" => "dispatch"], function(){
    route()->post("/user_level", 'BuyController@userLevel')->withAddMiddleware("login");
    route()->post("/analyst_level", 'BuyController@analystLevel')->withAddMiddleware("login");
    route()->post("/coin", 'BuyController@coin')->withAddMiddleware("login");

    route()->get("/analyst_level/price", 'BuyController@analystLevelPriceList');
    route()->get("/user_level/price", 'BuyController@userLevelPriceList');

    route()->post("/notify", 'CommonController@orderNotify');
    route()->get("/notify", 'CommonController@orderNotify');
});

//微信客服消息接口
route()->group(["prefix"=>"/wechat","middleware" => "dispatch"],function(){
    route()->get("/customer","WechatController@wxapp");
    route()->post("/customer","WechatController@wxapp");
    route()->put("/customer","WechatController@wxapp");
});

//微信app tab 接口
route()->group(["prefix" => "/wxapp", "middleware" => "dispatch"],function (){
    route()->get("/tab/list", "Wxapp\WxappTabController@fetchTab");
    route()->post("/tab", "Wxapp\WxappTabController@addTab");
    route()->post("/tab/update", "Wxapp\WxappTabController@updateTab");
    route()->delete("/tab", "Wxapp\WxappTabController@deleteTab");
});

//个人中心页
route()->group(["prefix"=>"/user","middleware" => "dispatch"], function(){
    route()->get("/info", 'UserCenterController@getInfo')->withAddMiddleware("login");
    route()->get("/myfollow", 'UserCenterController@getMyFollows')->withAddMiddleware("login");
    route()->post("/modify", 'UserCenterController@updateUserInfo')->withAddMiddleware("login");
    route()->post("/bind_phone", 'UserCenterController@bindPhone')->withAddMiddleware("login");
    route()->get("/bind_code/send", 'UserCenterController@sendBindCode')->withAddMiddleware("login");
    route()->get("/forget_code/send", 'UserCenterController@sendForgetCode');
    route()->post("/forget_code/valid", 'UserCenterController@validCode');
    route()->post("/change/password", 'UserCenterController@updateUserPassword')->withAddMiddleware("login");
    route()->get("/read/history", 'UserCenterController@fetchRecommendReadHistoryList')->withAddMiddleware("login");
});

//搜索页
route()->group(["prefix"=>"/search","middleware" => "dispatch"], function(){
    route()->get("/index", 'SearchController@index');
    route()->get("/keyword", 'SearchController@keywords');
    route()->get("/blurkeyword", 'SearchController@blurKeywords');
});

//
route()->group(["prefix"=>"/common","middleware" => "dispatch"], function(){
    route()->post("/upload", 'CommonController@uploadImage');
});

//课程
route()->group(['prefix' => '/index', 'middleware' => 'dispatch'],function(){
    route()->get("/class","Lesson\IndexController@listClass");
});

route()->group(['prefix' => '/lesson', 'middleware' => 'dispatch'],function(){
    //旧的课程banner，app不适用，只能用于微信公众号
//    route()->get("/banner","Lesson\IndexController@listBanner");
    //新的课程banner
    route()->get("/banner","Lesson\IndexController@listBanner");
});

//文章详情页
route()->group(['prefix' => '/article', 'middleware' => 'dispatch'],function(){
    route()->get("/info","Lesson\ArticleController@getArticle");
});

//课程详情页
route()->group(['prefix' => '/class', 'middleware' => 'dispatch'],function(){
    route()->get("/info","Lesson\ClassController@getClass");
    route()->get("/try","Lesson\ClassController@getClassTry");
    route()->get("/chapter","Lesson\ClassController@getClassChapter");
    route()->post("/buyClass","Lesson\ClassController@createOrder")->withAddMiddleware("login");
    route()->get("/is_buy","Lesson\ClassController@isBuy")->withAddMiddleware("login");
});

//个人中心首页
route()->group(['prefix' => '/me', 'middleware' => 'dispatch'],function(){
    route()->get("/info","Lesson\MeController@getUser")->withAddMiddleware("login");
});

//我的课程详情
route()->group(['prefix' => '/my_class', 'middleware' => 'dispatch'],function(){
    route()->get("/info","Lesson\MyClassController@getClassChapter")->withAddMiddleware("login");
    route()->post("/learn_percent","Lesson\MyClassController@updateLearnPercent")->withAddMiddleware("login");

    route()->get("/list","Lesson\MyClassListController@listUserClass")->withAddMiddleware("login");
});

//交易明细
route()->group(['prefix' => '/transaction', 'middleware' => 'dispatch'],function(){

    route()->get("/order","BuyController@fetchOrderList")->withAddMiddleware("login");
    route()->get("/bill","BuyController@fetchBillList")->withAddMiddleware("login");

});

//成为分析师
route()->group(['prefix' => '/analyst', 'middleware' => 'dispatch'],function(){

    route()->post("/application","AnalystApplicationController@addAnalystApplication")->withAddMiddleware("login");
});

//视频
route()->group(['prefix' => '/video', 'middleware' => 'dispatch'],function(){

    route()->get("/cate","VideoController@fetchCate");
    route()->get("/list","VideoController@fetchVideo");
    route()->post("/collect","VideoController@collectVideo")->withAddMiddleware("login");
    route()->delete("/collect","VideoController@uncollectedVideo")->withAddMiddleware("login");
    route()->get("/collect","VideoController@collectVideoList")->withAddMiddleware("login");
});

//系统通知
route()->group(['prefix' => '/system_notice', 'middleware' => 'dispatch'],function(){

    route()->get("/list","SystemNoticeController@fetchAction");
});

//验证码系统
route()->group(['prefix' => '/valid_code', 'middleware' => 'dispatch'],function(){

    route()->post("/send","ValidCodeController@sendCode");
    route()->post("/check","ValidCodeController@checkCode");
});

//后台

//登录后台
route()->post('/admin/login', 'Admin\LoginController@login');

route()->group(['prefix' => '/admin', 'middleware' => 'admin_dispatch'],function(){

    //上传图片
    route()->post("/upload_image","Admin\CommController@uploadImage");

    //上传图片
    route()->post("/upload_video","Admin\CommController@uploadVideo");

    //页面列表
    route()->get("/page","Admin\AdventureController@fetchPage");

    //广告
    route()->get("/adventure","Admin\AdventureController@listAction");
    route()->get("/adventure/get","Admin\AdventureController@getAction");
    route()->post("/adventure","Admin\AdventureController@addAction");
    route()->post("/adventure/update","Admin\AdventureController@updateAction");
    route()->post("/adventure/delete","Admin\AdventureController@deleteAction");

    //分析师申请
    route()->get("/application","Admin\AnalystApplicationController@listAction");
    route()->get("/application/get","Admin\AnalystApplicationController@getAction");
    route()->post("/application/pass","Admin\AnalystApplicationController@passAction");
    route()->post("/application/refuse","Admin\AnalystApplicationController@deleteAction");

    //首页banner
    route()->get("/banner","Admin\BannerController@listAction");
    route()->get("/banner/get","Admin\BannerController@getAction");
    route()->post("/banner","Admin\BannerController@addAction");
    route()->post("/banner/update","Admin\BannerController@updateAction");
    route()->post("/banner/delete","Admin\BannerController@deleteAction");

    //分析师
    route()->get("/analyst","Admin\AnalystController@listAction");
    route()->post("/analyst/update","Admin\AnalystController@updateAction");
    route()->post("/analyst","Admin\AnalystController@addAction");

    //比赛
    route()->get("/match","Admin\MatchController@listAction");
    route()->post("/match/recommend","Admin\MatchController@matchRecommend");
    
    //订单
    route()->get("/order", "Admin\OrderController@listAction");

    //系统通知
    route()->get("/notice","Admin\SystemNoticeController@listAction");
    route()->get("/notice/get","Admin\SystemNoticeController@getAction");
    route()->post("/notice","Admin\SystemNoticeController@addAction");
    route()->post("/notice/update","Admin\SystemNoticeController@updateAction");
    route()->post("/notice/delete","Admin\SystemNoticeController@deleteAction");

    //球稳头条
    route()->get("/top_line","Admin\TopLineController@listAction");
    route()->get("/top_line/get","Admin\TopLineController@getAction");
    route()->post("/top_line","Admin\TopLineController@addAction");
    route()->post("/top_line/update","Admin\TopLineController@updateAction");
    route()->post("/top_line/delete","Admin\TopLineController@deleteAction");

    //用户列表
    route()->get("/user","Admin\UserController@listAction");

    //视频
    route()->get("/video","Admin\VideoController@listAction");
    route()->get("/video/get","Admin\VideoController@getAction");
    route()->post("/video","Admin\VideoController@addAction");
    route()->post("/video/update","Admin\VideoController@updateAction");
    route()->post("/video/delete","Admin\VideoController@deleteAction");

    //视频分类
    route()->get("/video_cate","Admin\VideoCateController@listAction");
    route()->get("/video_cate/get","Admin\VideoCateController@getAction");
    route()->post("/video_cate","Admin\VideoCateController@addAction");
    route()->post("/video_cate/update","Admin\VideoCateController@updateAction");
    route()->post("/video_cate/delete","Admin\VideoCateController@deleteAction");

    //视频分类下的视频
    route()->get("/video_cate/video","Admin\VideoCateController@listVideoByCate");
    route()->post("/video_cate/video","Admin\VideoCateController@addVideoByCate");
    route()->post("/video_cate/video/delete","Admin\VideoCateController@deleteVideoByCate");

    //用户等级商品
    route()->get("/user_level","Admin\UserLevelController@listAction");
    route()->post("/user_level/update","Admin\UserLevelController@updateAction");

    //分析师等级等级商品
    route()->get("/analyst_level","Admin\AnalystLevelController@listAction");
    route()->post("/analyst_level/update","Admin\AnalystLevelController@updateAction");

    //发推荐
    route()->get("/recommend/odd","Admin\RecommendController@matchInfo");
    route()->post("/recommend/add","Admin\RecommendController@addRecommend");
    route()->get("/recommend/list","Admin\RecommendController@RecommendList");

    //收入统计
    route()->get("/income_static","Admin\IncomeStaticController@incomeStatic");

    //等级配置
    route()->get("/level","Admin\IconController@listAction");
    route()->post("/level/update","Admin\IconController@updateAction");

});
