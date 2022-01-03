#!/bin/bash
file=`basename $1`
filename=${file%.*}
HandBrakeCLI -i "$1" -e x264 -b 978 -T -2 -O -f mp4 -r 29.97 -a 1 -E faac -B 160 -6 dpl2 -R 48 -D 0.0 -f mp4 -Y 720 -x cabac=0:ref=5:me=umh:bframes=0:subme=6:8x8dct=0:trellis=0:analyse=all -o "$filename"_720.mp4

