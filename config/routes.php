<?php

route()->get('/demo', 'DemoController@demo')->withAddMiddleware("filter");
route()->get('/', 'WelcomeController@welcome');
route()->get('/hello/foo', 'WelcomeController@sayHello');

route()->post('/test/rsa', 'RSAController@test')->withAddMiddleware(['rsa','verify','reply']);

route()->get("/login", 'LoginController@get')->withAddMiddleware("dispatch");

route()->group(["prefix"=>"/match","middleware" => "dispatch"], function(){
    route()->get("/list", 'MatchListController@fetchMatchList')->withAddMiddleware("login");;
    route()->post("/collect", 'MatchListController@collectionMatch')->withAddMiddleware("login");
    route()->delete("/collect", 'MatchListController@collectionMatchCancel')->withAddMiddleware("login");
    route()->get("/league/list", 'MatchListController@fetchLeague');
});

route()->group(["prefix"=>"/match_collect","middleware" => "dispatch"], function(){
    route()->get("/list", 'MatchCollectionController@fetchAction')->withAddMiddleware("login");;
});