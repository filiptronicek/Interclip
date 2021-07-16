#!/bin/bash
rclone copy --fast-list "$1" "$2" && rm "$1"
