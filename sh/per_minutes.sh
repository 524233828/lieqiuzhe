#!/bin/sh
cd /home/wwwroot/lieqiuzhe

# 每分钟获取比赛150秒内信息改变
bin/console change_match

# 每分钟更新比赛状态
bin/console fetch_match_id


# 每分钟更新赔率
bin/console fetch_odd