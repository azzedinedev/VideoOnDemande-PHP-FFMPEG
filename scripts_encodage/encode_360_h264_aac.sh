#!/bin/bash
file=`basename $1`
filename=${file%.*}
HandBrakeCLI -i "$1" -e x264 -b 360 -T -2 -O -I -f mp4 -r 25 -a 1 -E faac -B 64 -6 dpl2 -R 48 -D 0.0 -f mp4 -X 640 -m -x cabac=0:ref=1:me=umh:bframes=3:subme=6:8x8dct=0:trellis=0:analyse=all -o "$filename"_360.mp4

