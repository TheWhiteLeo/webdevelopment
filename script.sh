#!/bin/bash

git filter-branch --force --env-filter '
if [ "$GIT_AUTHOR_NAME" = "ThePolarLeo" ] || [ "$GIT_AUTHOR_NAME" = "TheWhiteLeo" ] || [ "$GIT_COMMITTER_NAME" = "ThePolarLeo" ] || [ "$GIT_COMMITTER_NAME" = "TheWhiteLeo" ]; then
    COMMIT_MSG=$(git log -1 --pretty=%B $GIT_COMMIT)
    
    case "$COMMIT_MSG" in
        *"feat!: implement lab2"*) NEW_DATE="2026-05-25 12:10:08" ;;
        *"fix: remove duplicate project"*) NEW_DATE="2026-05-26 09:06:36" ;;
        *"feat: new controllers"*) NEW_DATE="2026-05-26 10:09:01" ;;
        *"feat: implement admin controller"*) NEW_DATE="2026-05-26 21:18:36" ;;
        *"feat: Lab 5 - collections"*) NEW_DATE="2026-05-29 07:41:59" ;;
        *"feat: implement Lab6 - Requests"*) NEW_DATE="2026-05-29 11:21:01" ;;
        *"feat: implement Lab7 - Repositories"*) NEW_DATE="2026-05-29 11:32:58" ;;
        *"feat: implement Lab8 - Post Managing"*) NEW_DATE="2026-05-29 11:51:27" ;;
        *"feat: implement Lab9 - Post Updating"*) NEW_DATE="2026-06-01 08:14:03" ;;
        *"feat: implement Lab10 - Observers Using"*) NEW_DATE="2026-06-01 08:49:54" ;;
        *"feat: implement Lab11 - Post Creation and Deletion"*) NEW_DATE="2026-06-03 09:30:50" ;;
        *"feat: implement Lab12 - Queues and Jobs"*) NEW_DATE="2026-06-03 09:55:12" ;;
        *"feat: implement Lab13 - Chains"*) NEW_DATE="2026-06-09 08:25:25" ;;
        *"feat: implement Lab14 - Laravel Updates"*) NEW_DATE="2026-06-09 08:26:32" ;;
        *) NEW_DATE="" ;;
    esac

    if [ -n "$NEW_DATE" ]; then
        export GIT_AUTHOR_DATE="$NEW_DATE"
        export GIT_COMMITTER_DATE="$NEW_DATE"
    fi
fi
' -- 541638e21385f96ec8c252294bb27337add6d844..HEAD
