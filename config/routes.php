<?php

route()->get('/demo', 'DemoController@demo')->withAddMiddleware("filter");
route()->get('/', 'WelcomeController@welcome');
route()->get('/hello/foo', 'WelcomeController@sayHello');

route()->post('/test/rsa', 'RSAController@test')->withAddMiddleware(['rsa','verify','reply']);

route()->get("/login", 'LoginController@get')->withAddMiddleware("dispatch");

//比赛列表页
route()->group(["prefix"=>"/match","middleware" => "dispatch"], function(){
    route()->get("/list", 'MatchListController@fetchMatchList')->withAddMiddleware("login");;
    route()->post("/collect", 'MatchListController@collectionMatch')->withAddMiddleware("login");
    route()->delete("/collect", 'MatchListController@collectionMatchCancel')->withAddMiddleware("login");
    route()->get("/league/list", 'MatchListController@fetchLeague');
});

//比赛收藏页
route()->group(["prefix"=>"/match_collect","middleware" => "dispatch"], function(){
    route()->get("/list", 'MatchCollectionController@fetchAction')->withAddMiddleware("login");
});

//首页
route()->group(["prefix" => "/index", "middleware" => "dispatch"], function(){
    route()->get("/banner", 'IndexController@banner');
    route()->get("/top_line", 'IndexController@topLine');
});

//发推荐选择页
route()->group(["prefix" => "/recommend", "middleware" => "dispatch"], function(){
    route()->get("/match_list", 'RecommendMatchChoseController@matchList');
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