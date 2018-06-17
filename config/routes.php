<?php

route()->get('/demo', 'DemoController@demo')->withAddMiddleware("filter");
route()->get('/', 'WelcomeController@welcome');
route()->get('/hello/foo', 'WelcomeController@sayHello');

route()->post('/test/rsa', 'RSAController@test')->withAddMiddleware(['rsa','verify','reply']);

route()->get("/login", 'LoginController@get')->withAddMiddleware("dispatch");

//比赛列表页
route()->group(["prefix"=>"/match","middleware" => "dispatch"], function(){
    route()->get("/list", 'MatchListController@fetchMatchList')->withAddMiddleware("login");
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
    route()->get("/odd", 'RecommendController@matchInfo');
    route()->post("/add", 'RecommendController@addRecommend')->withAddMiddleware("login");
    route()->get("/detail", 'RecommendController@RecommendDetail');
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
    route()->get("/modify", 'UserCenterController@updateUserInfo')->withAddMiddleware("login");
});

//搜索页
route()->group(["prefix"=>"/search","middleware" => "dispatch"], function(){
    route()->get("/index", 'SearchController@index');
    route()->get("/keyword", 'SearchController@keywords');
});

//
route()->group(["prefix"=>"/common","middleware" => "dispatch"], function(){
    route()->post("/upload", 'CommonController@uploadImage');
});