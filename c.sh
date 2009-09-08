#!/bin/sh
ctags `find . |grep php |grep -v tmp|grep -v smarty`
