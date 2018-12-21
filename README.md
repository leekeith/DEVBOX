# DEVBOX

2015 Keith Lee, University of British Columbia

## Introduction

DEVBOX, under development at UBC Vancouver, is an all-in-one software 
development education platform designed to ease new students into
writing code.  This GitHub repository contains many of the custom
drivers, programs, and projects required to run the prototype 
application on the Altera/Teraisic DE1-SoC.

## Resources
### Arduino Runtime

This package is used in the Arduino simulation portion of the IDE.
It maps the majority of Arduino C's custom instructions to the I/O's 
and to a visual representation of the virtual Arduino Uno's pin state
and the state of the hard I/Os on the development board.  This requires
the devbox_ioctl kernel module.

### devbox_ioctl

This kernel module and library provides user-space access to the DE1-SoC's
hard I/Os.  A small collection of instructions provide specialized access
to the LEDs, switches and push-buttons.  Future elaborations include the
addition of the 7-segment displays and the 40-pin GPIOs.

### pages/www

The primary user interface to the DEVBOX training tool.  This site is hosted
on the local network and accessed via the device's IP address.  It contains
interactive tutorials, example programs, help, templates and a bare-bones IDE.

### fbDraw

This is an abstraction-level library for manipulating the framebuffer via
`/dev/fb0`.  There are instructions for drawing characters, lines, squares
and single pixels.  It requires rw privileges to the device file.

### TutoriML

DEVBOX's Tutorial Markdown Layer is a file parser that converts a combination
of markdown-syntax documentation and verbatim code snippets to provide an
interactive tutorial experience.  This folder contains example documents.
There are also .tml files in the `./pages/www/tutorials/c` folder.

### Dev_Box_HW

The Quartus project files for the DEVBOX FPGA hardware.  Compilation requires
Quartus II >=13.1 with SoC EDS

## Installation

The contents of this repo do not yet represent a full install. The
full installation process will be documented and provided in the
near future.
