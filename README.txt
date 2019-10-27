Project moved
-------------

The GitHub repo is abandoned. Further development takes place at https://www.drupal.org/project/metis

Metis
-----

Metis automates the inclusion of the so called "Metis pixel" from the German
association VG Wort. The pixel is included as an image that is 1px wide and
high and it allows VG Wort to count visits to nodes. Based on that count, VG
Wort pays authors royalties obtained through the sale of printers, copiers,
and other technical devices that might be used to copy texts.

Metis stands for "Reporting System for Texts on Websites" (Meldesystem fuer
Texte auf Internetseiten).

More information about VG Wort and Metis (in German):

- http://www.vgwort.de/
- https://tom.vgwort.de/
- https://de.wikipedia.org/wiki/Meldesystem_f%C3%BCr_Texte_auf_Internetseiten

The module allows to

- Add Metis codes (Zaehlmarken) to your Drupal installation
- Add the Metis codes to nodes as a field with a simple checkbox
- Display a table of nodes with their respective Metis codes


Installation
------------

Metis can be installed like any other Drupal module -- place it in the
modules' directory for your site and enable it on the `admin/build/modules`
page.

After installing the module, you need to add a field of the type "Metis"
to the nodes you want to add Metis codes to.

Metis can be configured through the settings page at `admin/config/search/metis`.


Maintainers
-----------

- sanduhrs
