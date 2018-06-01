#!/bin/sh
cd /home/wwwroot/lieqiuzhe

# 每天4点更新最近7天的比赛资料
bin/console fetch_match_date

# 更新比赛修改记录
bin/console match_modify

# 更新联赛信息
bin/console fetch_league

# 更新球队信息
bin/console fetch_team