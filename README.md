# Title Block
This module fixes a problem with using Layout Builder to place Drupal core's
page title block. See https://www.drupal.org/project/drupal/issues/2938129.
TL;DR - Until that issue gets fixed, the core title block is blank when
placed with Layout Builder.

This module provides another block that displays the page title, but it
includes the fix from that issue.  So the block isn't blank when displayed.

When that issue gets fixed, any instances of this block should be removed and
replaced with the default block.  Then this module should be uninstalled and
deleted.

